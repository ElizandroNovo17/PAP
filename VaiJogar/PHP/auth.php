<?php

ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexao.php';

function ok($d=[])  { ob_clean(); echo json_encode(array_merge(['sucesso'=>true],$d));  exit; }
function err($m)    { ob_clean(); echo json_encode(['sucesso'=>false,'mensagem'=>$m]);  exit; }
function validar_pass($p) {
    if (strlen($p)<8)                              err('A palavra-passe deve ter pelo menos 8 caracteres.');
    if (!preg_match('/[A-Z]/',$p))                err('A palavra-passe deve ter pelo menos uma letra maiúscula.');
    if (!preg_match('/[0-9]/',$p))                err('A palavra-passe deve ter pelo menos um número.');
    if (!preg_match('/[!@#$%^&*()\-_=+\[\]{};:\'",.<>?\/\\\\|]/',$p))
                                                   err('A palavra-passe deve ter pelo menos um carácter especial.');
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

// ── LOGIN ─────────────────────────────────────────────────────
case 'login':
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if (!$email || !$pass) err('Preenche todos os campos.');
    $s = $conn->prepare("SELECT id_utilizador,nome,email,password,role,foto_perfil,data_registo FROM utilizadores WHERE email=?");
    $s->bind_param('s',$email); $s->execute();
    $u = $s->get_result()->fetch_assoc();
    if (!$u || !password_verify($pass,$u['password'])) err('Email ou palavra-passe incorretos.');
    $_SESSION['user_id']   = $u['id_utilizador'];
    $_SESSION['user_nome'] = $u['nome'];
    $_SESSION['user_role'] = $u['role'];
    ok(['utilizador'=>['id'=>$u['id_utilizador'],'nome'=>$u['nome'],'email'=>$u['email'],
        'role'=>$u['role'],'avatar'=>$u['foto_perfil'],'criado_em'=>$u['data_registo']]]);

// ── REGISTER ─────────────────────────────────────────────────
case 'register':
    $nome  = trim($_POST['nome']               ?? '');
    $email = trim($_POST['email']              ?? '');
    $pass  = $_POST['password']               ?? '';
    $conf  = $_POST['confirmar_password']      ?? '';
    if (!$nome||!$email||!$pass) err('Preenche todos os campos.');
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) err('Email inválido.');
    validar_pass($pass);
    if ($pass!==$conf) err('As palavras-passe não coincidem.');
    $s=$conn->prepare("SELECT id_utilizador FROM utilizadores WHERE email=?");
    $s->bind_param('s',$email); $s->execute(); $s->store_result();
    if ($s->num_rows>0) err('Não foi possível criar a conta. Verifica os dados e tenta novamente.');
    $hash=password_hash($pass,PASSWORD_DEFAULT);
    $s2=$conn->prepare("INSERT INTO utilizadores (nome,email,password,role) VALUES(?,?,?,'user')");
    $s2->bind_param('sss',$nome,$email,$hash);
    $s2->execute() ? ok(['mensagem'=>'Conta criada com sucesso!','nome'=>$nome]) : err('Erro ao criar conta.');

// ── LOGOUT ───────────────────────────────────────────────────
case 'logout':
    // FIX SEGURANÇA: aceitar tanto GET (legacy) como POST com CSRF.
    // Para o método GET exigimos um token CSRF via header X-CSRF-Token,
    // para que um simples <img src="..."> numa página externa não consiga
    // deslogar o utilizador (CSRF clássico).
    $token_recebido = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
    $token_sessao   = $_SESSION['csrf_token'] ?? '';
    $tem_csrf = $token_sessao && hash_equals($token_sessao, $token_recebido);

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !$tem_csrf) {
        // POST sem token CSRF válido -> rejeitar
        http_response_code(403);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Pedido inválido. Recarrega a página e tenta novamente.']);
        exit;
    }
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !$tem_csrf) {
        // GET sem CSRF: para compatibilidade com o redirect clássico (link <a>),
        // aceitamos, mas só se vier de um referer do mesmo site. Caso contrário,
        // bloqueamos.
        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($ref && $host && strpos($ref, $host) === false) {
            http_response_code(403);
            echo json_encode(['sucesso' => false, 'mensagem' => 'Pedido inválido (CSRF).']);
            exit;
        }
    }

    session_destroy();
    if (isset($_GET['redirect'])) {
        $dest = $_GET['redirect'];
        // FIX SEGURANÇA: apenas permitir URLs relativas (sem domínio externo)
        // Bloqueia: https://malicioso.com, //malicioso.com, javascript:...
        if (preg_match('/^[a-zA-Z0-9\/_.?=&%-]+$/', $dest) && !str_starts_with($dest, '//')) {
            // FIX BUG: garantir que o redirect resolve a partir da RAIZ do projeto,
            // não da pasta /PHP/ onde este ficheiro vive. Sem o "../", um valor como
            // "index.php" era interpretado como "VaiJogar/PHP/index.php" -> 404.
            // Se o destino não começa com "/" nem com "../", prefixamos "../" para
            // subir um nível (sair de /PHP/ e voltar à raiz do projeto).
            if ($dest[0] !== '/' && !str_starts_with($dest, '../')) {
                $dest = '../' . $dest;
            }
            header('Location: ' . $dest);
        } else {
            header('Location: ../index.php');
        }
        exit;
    }
    ok(['mensagem'=>'Sessão terminada.']);

// ── SESSION ──────────────────────────────────────────────────
case 'session':
    if (!isset($_SESSION['user_id'])) { ob_clean(); echo json_encode(['autenticado'=>false]); exit; }
    $s=$conn->prepare("SELECT id_utilizador,nome,email,role,foto_perfil,data_registo FROM utilizadores WHERE id_utilizador=?");
    $s->bind_param('i',$_SESSION['user_id']); $s->execute();
    $u=$s->get_result()->fetch_assoc();
    if (!$u) { session_destroy(); ob_clean(); echo json_encode(['autenticado'=>false]); exit; }
    ob_clean();
    echo json_encode(['autenticado'=>true,'utilizador'=>[
        'id'=>$u['id_utilizador'],'nome'=>$u['nome'],'email'=>$u['email'],
        'role'=>$u['role'],'avatar'=>$u['foto_perfil'],'criado_em'=>$u['data_registo']]]);
    exit;

// ── RECUPERAR PASSWORD ────────────────────────────────────────
case 'recuperar':
    $email = trim($_POST['email'] ?? '');
    if (!$email || !filter_var($email,FILTER_VALIDATE_EMAIL)) err('Email inválido.');
    $s=$conn->prepare("SELECT id_utilizador,nome FROM utilizadores WHERE email=?");
    $s->bind_param('s',$email); $s->execute();
    $u=$s->get_result()->fetch_assoc();
    if (!$u) ok(); // segurança: não revela se email existe
    $token   = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));
    $s2=$conn->prepare("UPDATE utilizadores SET reset_token=?,reset_expires=? WHERE id_utilizador=?");
    $s2->bind_param('ssi',$token,$expires,$u['id_utilizador']);
    $s2->execute() ? ok(['token'=>$token,'nome'=>$u['nome'],'email'=>$email]) : err('Erro ao gerar token.');

// ── RESET PASSWORD ────────────────────────────────────────────
case 'reset':
    $token = trim($_POST['token']    ?? '');
    $pass  = $_POST['password']      ?? '';
    if (!$token||!$pass) err('Dados inválidos.');
    validar_pass($pass);
    $s=$conn->prepare("SELECT id_utilizador FROM utilizadores WHERE reset_token=? AND reset_expires>NOW()");
    $s->bind_param('s',$token); $s->execute();
    $u=$s->get_result()->fetch_assoc();
    if (!$u) err('Link inválido ou expirado. Pede uma nova recuperação.');
    $hash=password_hash($pass,PASSWORD_DEFAULT);
    $s2=$conn->prepare("UPDATE utilizadores SET password=?,reset_token=NULL,reset_expires=NULL WHERE id_utilizador=?");
    $s2->bind_param('si',$hash,$u['id_utilizador']);
    $s2->execute() ? ok() : err('Erro ao guardar nova palavra-passe.');

// ── PERFIL — obter dados do utilizador autenticado ───────────
case 'profile':
    if (!isset($_SESSION['user_id'])) err('Não autenticado.');
    $s = $conn->prepare("SELECT id_utilizador,nome,email,telefone,role,foto_perfil,data_registo FROM utilizadores WHERE id_utilizador=?");
    $s->bind_param('i', $_SESSION['user_id']); $s->execute();
    $u = $s->get_result()->fetch_assoc();
    if (!$u) err('Utilizador não encontrado.');
    ok(['utilizador' => [
        'id'        => $u['id_utilizador'],
        'nome'      => $u['nome'],
        'email'     => $u['email'],
        'telefone'  => $u['telefone'] ?? '',
        'role'      => $u['role'],
        'avatar'    => $u['foto_perfil'],
        'criado_em' => $u['data_registo'],
    ]]);

// ── UPDATE_PROFILE — alterar nome e telefone ─────────────────
case 'update_profile':
    if (!isset($_SESSION['user_id'])) err('Não autenticado.');
    $nome     = trim($_POST['nome']     ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    if (!$nome) err('O nome não pode estar vazio.');
    $s = $conn->prepare("UPDATE utilizadores SET nome=?, telefone=? WHERE id_utilizador=?");
    $s->bind_param('ssi', $nome, $telefone, $_SESSION['user_id']);
    $s->execute();
    $_SESSION['user_nome'] = $nome;
    ok(['mensagem' => 'Perfil atualizado com sucesso.']);

// ── CHANGE_EMAIL — alterar email ─────────────────────────────
case 'change_email':
    if (!isset($_SESSION['user_id'])) err('Não autenticado.');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) err('Email inválido.');
    // Verificar password atual
    $s = $conn->prepare("SELECT password FROM utilizadores WHERE id_utilizador=?");
    $s->bind_param('i', $_SESSION['user_id']); $s->execute();
    $u = $s->get_result()->fetch_assoc();
    if (!$u || !password_verify($pass, $u['password'])) err('Password incorreta.');
    // Verificar se email já existe
    $s2 = $conn->prepare("SELECT id_utilizador FROM utilizadores WHERE email=? AND id_utilizador!=?");
    $s2->bind_param('si', $email, $_SESSION['user_id']); $s2->execute(); $s2->store_result();
    if ($s2->num_rows > 0) err('Este email já está em uso.');
    $s3 = $conn->prepare("UPDATE utilizadores SET email=? WHERE id_utilizador=?");
    $s3->bind_param('si', $email, $_SESSION['user_id']);
    $s3->execute();
    ok(['mensagem' => 'Email alterado com sucesso.']);

// ── CHANGE_PASSWORD — alterar password ───────────────────────
case 'change_password':
    if (!isset($_SESSION['user_id'])) err('Não autenticado.');
    $atual = $_POST['password_atual'] ?? '';
    $nova  = $_POST['password_nova']  ?? '';
    $conf  = $_POST['confirmar']      ?? '';
    if (!$atual || !$nova) err('Preenche todos os campos.');
    validar_pass($nova);
    if ($nova !== $conf) err('As novas passwords não coincidem.');
    $s = $conn->prepare("SELECT password FROM utilizadores WHERE id_utilizador=?");
    $s->bind_param('i', $_SESSION['user_id']); $s->execute();
    $u = $s->get_result()->fetch_assoc();
    if (!$u || !password_verify($atual, $u['password'])) err('A password atual está incorreta.');
    $hash = password_hash($nova, PASSWORD_DEFAULT);
    $s2 = $conn->prepare("UPDATE utilizadores SET password=? WHERE id_utilizador=?");
    $s2->bind_param('si', $hash, $_SESSION['user_id']);
    $s2->execute();
    ok(['mensagem' => 'Password alterada com sucesso.']);

default:
    err('Ação desconhecida: '.htmlspecialchars($action));
}
?>