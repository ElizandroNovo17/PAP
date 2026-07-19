<?php
session_start();
require __DIR__ . "/conexao.php";
header('Content-Type: application/json; charset=utf-8');

define('GOOGLE_CLIENT_ID', '915645442918-44a3b8u3c7ohjcsv76s55svv5b9rqn4o.apps.googleusercontent.com');

$credential = $_POST['credential'] ?? '';
if (!$credential) { echo json_encode(["sucesso"=>false,"mensagem"=>"Token em falta."]); exit; }

// Verificar token Google usando cURL (mais fiável que file_get_contents)
$url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($credential);

if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
} else {
    $response = @file_get_contents($url);
}

if (!$response) {
    echo json_encode(["sucesso"=>false,"mensagem"=>"Não foi possível verificar o token Google. Tenta novamente."]);
    exit;
}

$data = json_decode($response, true);

if (!isset($data['sub'])) {
    echo json_encode(["sucesso"=>false,"mensagem"=>"Resposta inválida do Google: " . ($data['error_description'] ?? 'erro desconhecido')]);
    exit;
}

// Verificar audience
if (($data['aud'] ?? '') !== GOOGLE_CLIENT_ID) {
    echo json_encode(["sucesso"=>false,"mensagem"=>"Token não pertence a esta aplicação."]);
    exit;
}

$google_id = $data['sub']     ?? '';
$email     = $data['email']   ?? '';
$nome      = $data['name']    ?? 'Utilizador Google';
$avatar    = $data['picture'] ?? null;

if (!$google_id || !$email) {
    echo json_encode(["sucesso"=>false,"mensagem"=>"Dados Google inválidos."]);
    exit;
}

// Verificar se já existe conta
$stmt = $conn->prepare("SELECT id_utilizador, nome, email, role, foto_perfil, data_registo, google_id FROM utilizadores WHERE google_id=? OR email=?");
$stmt->bind_param("ss", $google_id, $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user) {
    // Atualizar google_id se em falta
    if (!$user['google_id']) {
        $gid = $conn->real_escape_string($google_id);
        $uid = (int)$user['id_utilizador'];
        $conn->query("UPDATE utilizadores SET google_id='$gid' WHERE id_utilizador=$uid");
    }
    $_SESSION['user_id']   = $user['id_utilizador'];
    $_SESSION['user_nome'] = $user['nome'];
    $_SESSION['user_role'] = $user['role'];
    echo json_encode([
        "sucesso"    => true,
        "utilizador" => [
            "id"        => $user['id_utilizador'],
            "nome"      => $user['nome'],
            "email"     => $user['email'],
            "role"      => $user['role'],
            "avatar"    => $user['foto_perfil'],
            "criado_em" => $user['data_registo']
        ]
    ]);
} else {
    // Criar nova conta
    $hash = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
    $stmt2 = $conn->prepare("INSERT INTO utilizadores (nome, email, password, role, foto_perfil, google_id, ativo) VALUES (?,?,?,'user',?,?,1)");
    $stmt2->bind_param("sssss", $nome, $email, $hash, $avatar, $google_id);
    if ($stmt2->execute()) {
        $newId = $conn->insert_id;
        $_SESSION['user_id']   = $newId;
        $_SESSION['user_nome'] = $nome;
        $_SESSION['user_role'] = 'user';
        echo json_encode([
            "sucesso"    => true,
            "novo"       => true,
            "utilizador" => [
                "id"        => $newId,
                "nome"      => $nome,
                "email"     => $email,
                "role"      => "user",
                "avatar"    => $avatar,
                "criado_em" => date("Y-m-d H:i:s")
            ]
        ]);
    } else {
        echo json_encode(["sucesso"=>false,"mensagem"=>"Erro ao criar conta: " . $conn->error]);
    }
}
?>
