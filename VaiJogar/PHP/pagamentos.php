<?php
// ============================================================
//  PHP/pagamentos.php
//  API de pagamentos e marcações — MB WAY, confirmação,
//  cancelamento, reembolso e histórico do utilizador
//
//  Ações (?action=):
//    iniciar   — cria marcação pendente + inicia MB WAY
//    confirmar — confirma pagamento após autorização
//    cancelar  — cancela com reembolso automático se < 48h
//    listar    — histórico de marcações do utilizador
// ============================================================
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/csrf.php';

function req_auth() {
    if (empty($_SESSION['user_id'])) {
        echo json_encode(['ok'=>false,'erro'=>'Não autenticado']); exit;
    }
}
function get_body() {
    static $b=null;
    if ($b===null) $b=json_decode(file_get_contents('php://input'),true)??[];
    return $b;
}
function ok($d=[])  { echo json_encode(array_merge(['ok'=>true],$d));  exit; }
function err($m)    { echo json_encode(['ok'=>false,'erro'=>$m]);       exit; }

$action = $_GET['action'] ?? (get_body()['action'] ?? '');

switch ($action) {

// ════════════════════════════════════════════════════════════
//  INICIAR — cria marcação pendente + inicia MB WAY
// ════════════════════════════════════════════════════════════
case 'iniciar':
    req_auth();
    csrf_verificar();
    $body    = get_body();
    $uid     = (int)$_SESSION['user_id'];
    $tel     = preg_replace('/\D/','', $body['telefone'] ?? '');
    $tel     = preg_replace('/^351/','',$tel);
    if (!preg_match('/^9[1236]\d{7}$/',$tel))
        err('Número de telemóvel inválido. Use um número português (ex: 912 345 678)');

    // Obter IDs reais de plano e escalão
    $club_id    = (int)($body['club_id']    ?? 0);
    $plano_nome = trim($body['plano']        ?? '');
    $esc_nome   = trim($body['escalao']      ?? '');
    $clube_nome = trim($body['clube_nome']   ?? '');
    $dias       = trim($body['dias']         ?? '');
    $horario    = trim($body['horario']      ?? '');
    $preco      = (float)($body['preco']     ?? 0);

    // Resolver plano_id
    $sp = $conn->prepare("SELECT id FROM planos WHERE nome=? AND ativo=1 LIMIT 1");
    $sp->bind_param('s',$plano_nome); $sp->execute();
    $plano_id = ($sp->get_result()->fetch_assoc())['id'] ?? null;

    // Resolver escalao_id (pertence ao clube correto)
    $se = $conn->prepare("SELECT id FROM escaloes WHERE nome=? AND club_id=? LIMIT 1");
    $se->bind_param('si',$esc_nome,$club_id); $se->execute();
    $esc_id = ($se->get_result()->fetch_assoc())['id'] ?? null;

    // Se não encontrou club_id no body, tentar pelo nome
    if (!$club_id && $clube_nome) {
        $sc = $conn->prepare("SELECT id FROM clubes WHERE nome=? LIMIT 1");
        $sc->bind_param('s',$clube_nome); $sc->execute();
        $club_id = ($sc->get_result()->fetch_assoc())['id'] ?? 0;
    }

    $ref = 'VJ'.strtoupper(substr(md5(uniqid($uid,true)),0,10));

    $stmt = $conn->prepare("
        INSERT INTO marcacoes
            (user_id, club_id, plano_id, escalao_id, referencia,
             metodo, telefone_mbway, preco, dias, horario, status)
        VALUES (?,?,?,?,?,'mbway',?,?,?,?,'pendente')
    ");
    $stmt->bind_param('iiisssdss',
        $uid, $club_id, $plano_id, $esc_id, $ref,
        $tel, $preco, $dias, $horario
    );
    if (!$stmt->execute()) err('Erro ao guardar marcação: '.$conn->error);
    $marc_id = $stmt->insert_id;

    // Dados do utilizador para email
    $su = $conn->prepare("SELECT nome,email FROM utilizadores WHERE id_utilizador=?");
    $su->bind_param('i',$uid); $su->execute();
    $u  = $su->get_result()->fetch_assoc();

    ok([
        'marcacao_id' => $marc_id, 'ref'      => $ref,
        'telefone'    => $tel,     'preco'    => $preco,
        'nome_user'   => $u['nome']  ?? '',
        'email_user'  => $u['email'] ?? '',
        'mbway_status'=> 'enviado',
        'mbway_msg'   => 'Pedido MB WAY enviado para +351 '.$tel,
    ]);

// ════════════════════════════════════════════════════════════
//  CONFIRMAR — confirma pagamento após autorização MB WAY
// ════════════════════════════════════════════════════════════
case 'confirmar':
    req_auth();
    csrf_verificar();
    $body = get_body();
    $uid  = (int)$_SESSION['user_id'];
    $mid  = (int)($body['marcacao_id'] ?? 0);
    if (!$mid) err('ID de marcação inválido');

    $stmt = $conn->prepare("
        SELECT m.*, c.nome AS clube_nome,
               p.nome AS plano_nome, e.nome AS escalao_nome,
               u.nome AS user_nome, u.email AS user_email
        FROM marcacoes m
        JOIN clubes      c ON c.id = m.club_id
        JOIN utilizadores u ON u.id_utilizador = m.user_id
        LEFT JOIN planos   p ON p.id = m.plano_id
        LEFT JOIN escaloes e ON e.id = m.escalao_id
        WHERE m.id=? AND m.user_id=?
    ");
    $stmt->bind_param('ii',$mid,$uid); $stmt->execute();
    $marc = $stmt->get_result()->fetch_assoc();
    if (!$marc) err('Marcação não encontrada');

    $upd = $conn->prepare("UPDATE marcacoes SET status='confirmado' WHERE id=? AND user_id=?");
    $upd->bind_param('ii',$mid,$uid); $upd->execute();

    // Registar pagamento
    $pag = $conn->prepare("
        INSERT INTO pagamentos (marcacao_id,valor,metodo,status,data_pagamento)
        VALUES (?,?,'mbway','pago',NOW())
    ");
    $pag->bind_param('id',$mid,$marc['preco']); $pag->execute();

    ok([
        'marcacao_id' => $mid,
        'ref'         => $marc['referencia'],
        'email'       => $marc['user_email'],
        'nome'        => $marc['user_nome'],
        'clube'       => $marc['clube_nome'],
        'plano'       => $marc['plano_nome'],
        'escalao'     => $marc['escalao_nome'],
        'dias'        => $marc['dias'],
        'horario'     => $marc['horario'],
        'preco'       => $marc['preco'],
        'metodo'      => $marc['metodo'],
        'criado_em'   => $marc['criado_em'],
    ]);

// ════════════════════════════════════════════════════════════
//  CANCELAR — cancela e processa reembolso se < 48h
// ════════════════════════════════════════════════════════════
case 'cancelar':
    req_auth();
    csrf_verificar();
    $body  = get_body();
    $uid   = (int)$_SESSION['user_id'];
    $mid   = (int)($body['marcacao_id'] ?? 0);
    $motiv = trim($body['motivo'] ?? 'Cancelado pelo utilizador');
    if (!$mid) err('ID de marcação inválido');

    $stmt = $conn->prepare("
        SELECT m.id, m.status, m.preco, m.metodo, m.criado_em,
               c.nome AS clube_nome
        FROM marcacoes m
        JOIN clubes c ON c.id = m.club_id
        WHERE m.id=? AND m.user_id=?
    ");
    $stmt->bind_param('ii',$mid,$uid); $stmt->execute();
    $marc = $stmt->get_result()->fetch_assoc();
    if (!$marc) err('Marcação não encontrada');
    if (in_array($marc['status'],['cancelado','reembolsado'])) err('Esta marcação já foi cancelada');

    $horas        = (time()-strtotime($marc['criado_em']))/3600;
    $tem_reembolso = ($marc['status']==='confirmado' && $horas<=48);
    $novo          = $tem_reembolso ? 'reembolsado' : 'cancelado';

    $upd = $conn->prepare("
        UPDATE marcacoes
        SET status=?, cancelamento_motivo=?, cancelado_em=NOW()
        WHERE id=? AND user_id=?
    ");
    $upd->bind_param('ssii',$novo,$motiv,$mid,$uid); $upd->execute();
    if ($conn->affected_rows===0) err('Não foi possível cancelar');

    // Registar reembolso nos pagamentos
    if ($tem_reembolso) {
        $pr = $conn->prepare("
            UPDATE pagamentos SET status='reembolsado'
            WHERE marcacao_id=? AND status='pago'
        ");
        $pr->bind_param('i',$mid); $pr->execute();
    }

    ok([
        'novo_status'   => $novo,
        'tem_reembolso' => $tem_reembolso,
        'reembolso'     => $tem_reembolso
            ? ['valor'=>$marc['preco'],'metodo'=>$marc['metodo'],'prazo'=>'3 a 5 dias úteis']
            : null,
        'clube'  => $marc['clube_nome'],
        'preco'  => $marc['preco'],
    ]);

// ════════════════════════════════════════════════════════════
//  LISTAR — histórico do utilizador autenticado
// ════════════════════════════════════════════════════════════
case 'listar':
    req_auth();
    $uid  = (int)$_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT m.id, c.nome AS clube_nome,
               p.nome AS plano, e.nome AS escalao,
               m.dias, m.horario, m.metodo, m.preco,
               m.referencia, m.status,
               m.cancelamento_motivo, m.cancelado_em,
               DATE_FORMAT(m.criado_em,'%d/%m/%Y às %H:%i') AS data_formatada,
               m.criado_em
        FROM marcacoes m
        JOIN clubes       c ON c.id = m.club_id
        LEFT JOIN planos  p ON p.id = m.plano_id
        LEFT JOIN escaloes e ON e.id = m.escalao_id
        WHERE m.user_id=?
        ORDER BY m.criado_em DESC
        LIMIT 50
    ");
    $stmt->bind_param('i',$uid); $stmt->execute();
    ok(['marcacoes'=>$stmt->get_result()->fetch_all(MYSQLI_ASSOC)]);

default:
    err('Ação desconhecida: '.htmlspecialchars($action));
}
?>