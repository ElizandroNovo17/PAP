<?php
// ============================================================
//  PHP/admin_api.php
//  API de administração — utilizadores, clubes, marcações,
//  escalões e horários
// ============================================================

session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexao.php';

// ════════════════════════════════════════════════════════════
//  HELPERS
// ════════════════════════════════════════════════════════════

function ok($data = []) {
    echo json_encode(array_merge(['ok' => true, 'sucesso' => true], $data));
    exit;
}

function err($msg) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'sucesso' => false, 'erro' => $msg]);
    exit;
}

function get_body() {
    static $b = null;
    if ($b === null) {
        $b = json_decode(file_get_contents('php://input'), true) ?? [];
    }
    return $b;
}

function sanitize($v) {
    return htmlspecialchars(strip_tags(trim($v ?? '')), ENT_QUOTES, 'UTF-8');
}

// ════════════════════════════════════════════════════════════
//  VERIFICAR AUTENTICAÇÃO E PERMISSÃO ADMIN
// ════════════════════════════════════════════════════════════

$user_id = $_SESSION['user_id'] ?? $_SESSION['utilizador_id'] ?? 0;
if (!$user_id) {
    err('Não autenticado');
}

$stmt = $conn->prepare("SELECT role FROM utilizadores WHERE id_utilizador = ?");
if (!$stmt) {
    err('Erro na BD: ' . $conn->error);
}
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || $user['role'] !== 'admin') {
    err('Acesso negado — apenas admins');
}

// ════════════════════════════════════════════════════════════
//  ROTEADOR
// ════════════════════════════════════════════════════════════

$action = $_GET['action'] ?? $_POST['action'] ?? get_body()['action'] ?? '';

switch ($action) {

// ════════════════════════════════════════════════════════════
//  UTILIZADORES
// ════════════════════════════════════════════════════════════

case 'users_list':
    $stmt = $conn->prepare("
        SELECT id_utilizador AS id, nome, email, role, 
               DATE_FORMAT(data_registo, '%d/%m/%Y %H:%i') AS data_registo
        FROM utilizadores
        ORDER BY data_registo DESC
        LIMIT 500
    ");
    if (!$stmt) err('Erro: ' . $conn->error);
    $stmt->execute();
    $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    ok(['utilizadores' => $users]);

case 'users_role':
    $uid = (int)($_POST['user_id'] ?? get_body()['user_id'] ?? 0);
    $role = $_POST['role'] ?? get_body()['role'] ?? 'user';
    
    if (!$uid) err('user_id é obrigatório');
    if (!in_array($role, ['user', 'admin'])) err('Role inválido');
    if ($uid === (int)$user_id) err('Não podes alterar o teu próprio role');
    
    $stmt = $conn->prepare("UPDATE utilizadores SET role = ? WHERE id_utilizador = ?");
    if (!$stmt) err('Erro: ' . $conn->error);
    $stmt->bind_param('si', $role, $uid);
    $stmt->execute();
    $success = $stmt->affected_rows > 0;
    $stmt->close();
    
    ok(['alterado' => $success]);

case 'users_delete':
    $uid = (int)($_POST['user_id'] ?? get_body()['user_id'] ?? 0);
    
    if (!$uid) err('user_id é obrigatório');
    if ($uid === (int)$user_id) err('Não podes apagar a tua própria conta');
    
    $stmt = $conn->prepare("DELETE FROM utilizadores WHERE id_utilizador = ? AND role != 'admin'");
    if (!$stmt) err('Erro: ' . $conn->error);
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $success = $stmt->affected_rows > 0;
    $stmt->close();
    
    ok(['apagado' => $success]);

// ════════════════════════════════════════════════════════════
//  CLUBES
// ════════════════════════════════════════════════════════════

case 'clubes_list':
    $modalidade = $_GET['modalidade'] ?? '';
    $q = '%' . ($_GET['q'] ?? '') . '%';
    
    if ($modalidade) {
        $stmt = $conn->prepare("
            SELECT id, nome, modalidade, divisao, localizacao, ativo
            FROM clubes
            WHERE modalidade = ? AND (nome LIKE ? OR localizacao LIKE ?)
            ORDER BY nome
            LIMIT 500
        ");
        $stmt->bind_param('sss', $modalidade, $q, $q);
    } else {
        $stmt = $conn->prepare("
            SELECT id, nome, modalidade, divisao, localizacao, ativo
            FROM clubes
            WHERE nome LIKE ? OR localizacao LIKE ?
            ORDER BY modalidade, nome
            LIMIT 500
        ");
        $stmt->bind_param('ss', $q, $q);
    }
    
    if (!$stmt) err('Erro: ' . $conn->error);
    $stmt->execute();
    $clubes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    ok(['clubes' => $clubes, 'total' => count($clubes)]);

case 'clubes_create':
    $nome = sanitize($_POST['nome'] ?? get_body()['nome'] ?? '');
    $modalidade = sanitize($_POST['modalidade'] ?? get_body()['modalidade'] ?? '');
    $localizacao = sanitize($_POST['localizacao'] ?? get_body()['localizacao'] ?? '');
    $recinto = sanitize($_POST['recinto'] ?? get_body()['recinto'] ?? '');
    $divisao = sanitize($_POST['divisao'] ?? get_body()['divisao'] ?? '');
    $descricao = sanitize($_POST['descricao'] ?? get_body()['descricao'] ?? '');
    $telefone = sanitize($_POST['telefone'] ?? get_body()['telefone'] ?? '');
    $email = sanitize($_POST['email'] ?? get_body()['email'] ?? '');
    $website = sanitize($_POST['website'] ?? get_body()['website'] ?? '');
    $facebook = sanitize($_POST['facebook'] ?? get_body()['facebook'] ?? '');
    $instagram = sanitize($_POST['instagram'] ?? get_body()['instagram'] ?? '');
    $imagem_url = sanitize($_POST['imagem_url'] ?? get_body()['imagem_url'] ?? '');
    $inscricao_preco = sanitize($_POST['inscricao_preco'] ?? get_body()['inscricao_preco'] ?? '');
    $latitude = (float)($_POST['latitude'] ?? get_body()['latitude'] ?? 0);
    $longitude = (float)($_POST['longitude'] ?? get_body()['longitude'] ?? 0);
    $ativo = isset($_POST['ativo']) || get_body()['ativo'] ? 1 : 0;
    
    if (!$nome || !$modalidade) err('Nome e modalidade são obrigatórios');
    
    $stmt = $conn->prepare("
        INSERT INTO clubes 
        (nome, modalidade, localizacao, recinto, divisao, descricao, 
         telefone, email, website, facebook, instagram, imagem_url, 
         inscricao_preco, latitude, longitude, ativo)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");
    if (!$stmt) err('Erro: ' . $conn->error);
    
    $stmt->bind_param(
        'sssssssssssssddii',
        $nome, $modalidade, $localizacao, $recinto, $divisao, $descricao,
        $telefone, $email, $website, $facebook, $instagram, $imagem_url,
        $inscricao_preco, $latitude, $longitude, $ativo
    );
    
    if (!$stmt->execute()) {
        $stmt->close();
        err('Erro ao criar clube: ' . $conn->error);
    }
    
    $id = $conn->insert_id;
    $stmt->close();
    ok(['id' => $id, 'nome' => $nome]);

case 'clubes_update':
    $id = (int)($_POST['id'] ?? get_body()['id'] ?? 0);
    if (!$id) err('id é obrigatório');
    
    $nome = sanitize($_POST['nome'] ?? get_body()['nome'] ?? '');
    $modalidade = sanitize($_POST['modalidade'] ?? get_body()['modalidade'] ?? '');
    $localizacao = sanitize($_POST['localizacao'] ?? get_body()['localizacao'] ?? '');
    $recinto = sanitize($_POST['recinto'] ?? get_body()['recinto'] ?? '');
    $divisao = sanitize($_POST['divisao'] ?? get_body()['divisao'] ?? '');
    $descricao = sanitize($_POST['descricao'] ?? get_body()['descricao'] ?? '');
    $telefone = sanitize($_POST['telefone'] ?? get_body()['telefone'] ?? '');
    $email = sanitize($_POST['email'] ?? get_body()['email'] ?? '');
    $website = sanitize($_POST['website'] ?? get_body()['website'] ?? '');
    $facebook = sanitize($_POST['facebook'] ?? get_body()['facebook'] ?? '');
    $instagram = sanitize($_POST['instagram'] ?? get_body()['instagram'] ?? '');
    $imagem_url = sanitize($_POST['imagem_url'] ?? get_body()['imagem_url'] ?? '');
    $inscricao_preco = sanitize($_POST['inscricao_preco'] ?? get_body()['inscricao_preco'] ?? '');
    $latitude = (float)($_POST['latitude'] ?? get_body()['latitude'] ?? 0);
    $longitude = (float)($_POST['longitude'] ?? get_body()['longitude'] ?? 0);
    $ativo = isset($_POST['ativo']) || get_body()['ativo'] ? 1 : 0;
    
    $stmt = $conn->prepare("
        UPDATE clubes SET 
            nome=?, modalidade=?, localizacao=?, recinto=?, divisao=?, 
            descricao=?, telefone=?, email=?, website=?, facebook=?, 
            instagram=?, imagem_url=?, inscricao_preco=?, 
            latitude=?, longitude=?, ativo=?
        WHERE id=?
    ");
    if (!$stmt) err('Erro: ' . $conn->error);
    
    $stmt->bind_param(
        'sssssssssssssddii',
        $nome, $modalidade, $localizacao, $recinto, $divisao, $descricao,
        $telefone, $email, $website, $facebook, $instagram, $imagem_url,
        $inscricao_preco, $latitude, $longitude, $ativo, $id
    );
    
    if (!$stmt->execute()) {
        $stmt->close();
        err('Erro ao atualizar: ' . $conn->error);
    }
    $stmt->close();
    ok(['alterado' => true]);

case 'clubes_delete':
    $id = (int)($_POST['id'] ?? get_body()['id'] ?? 0);
    if (!$id) err('id é obrigatório');
    
    $stmt = $conn->prepare("DELETE FROM clubes WHERE id = ?");
    if (!$stmt) err('Erro: ' . $conn->error);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $success = $stmt->affected_rows > 0;
    $stmt->close();
    
    ok(['apagado' => $success]);

case 'clubes_toggle':
    $id = (int)($_POST['id'] ?? get_body()['id'] ?? 0);
    if (!$id) err('id é obrigatório');
    
    $stmt = $conn->prepare("UPDATE clubes SET ativo = NOT ativo WHERE id = ?");
    if (!$stmt) err('Erro: ' . $conn->error);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    
    $stmt2 = $conn->prepare("SELECT ativo FROM clubes WHERE id = ?");
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $result = $stmt2->get_result()->fetch_assoc();
    $stmt->close();
    $stmt2->close();
    
    ok(['ativo' => (bool)$result['ativo']]);

// ════════════════════════════════════════════════════════════
//  MARCAÇÕES (admin view)
// ════════════════════════════════════════════════════════════

case 'marc_list':
    $stmt = $conn->prepare("
        SELECT m.id, m.referencia, m.status, m.metodo, m.preco,
               m.dias, m.horario, m.cancelamento_motivo,
               DATE_FORMAT(m.criado_em, '%d/%m/%Y %H:%i') AS data_criacao,
               c.nome AS clube_nome, c.modalidade,
               p.nome AS plano, e.nome AS escalao,
               u.nome AS user_nome, u.email AS user_email
        FROM marcacoes m
        JOIN clubes c ON c.id = m.club_id
        JOIN utilizadores u ON u.id_utilizador = m.user_id
        LEFT JOIN planos p ON p.id = m.plano_id
        LEFT JOIN escaloes e ON e.id = m.escalao_id
        ORDER BY m.criado_em DESC
        LIMIT 500
    ");
    if (!$stmt) err('Erro: ' . $conn->error);
    $stmt->execute();
    $marcacoes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    ok(['marcacoes' => $marcacoes]);

case 'marc_confirmar':
    $mid = (int)($_POST['marcacao_id'] ?? get_body()['marcacao_id'] ?? 0);
    if (!$mid) err('marcacao_id é obrigatório');
    
    // Verificar se existe
    $stmt = $conn->prepare("SELECT id, status, preco FROM marcacoes WHERE id = ?");
    $stmt->bind_param('i', $mid);
    $stmt->execute();
    $marc = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$marc) err('Marcação não encontrada');
    if ($marc['status'] !== 'pendente') err('Marcação não está pendente');
    
    // Atualizar status
    $stmt = $conn->prepare("UPDATE marcacoes SET status = 'confirmado' WHERE id = ?");
    $stmt->bind_param('i', $mid);
    $stmt->execute();
    $stmt->close();
    
    // Registar pagamento
    $stmt = $conn->prepare("
        INSERT INTO pagamentos (marcacao_id, valor, metodo, status, data_pagamento)
        VALUES (?, ?, 'mbway', 'pago', NOW())
    ");
    $preco = $marc['preco'];
    $stmt->bind_param('id', $mid, $preco);
    $stmt->execute();
    $stmt->close();
    
    ok(['novo_status' => 'confirmado']);

case 'marc_cancelar':
    $mid = (int)($_POST['marcacao_id'] ?? get_body()['marcacao_id'] ?? 0);
    if (!$mid) err('marcacao_id é obrigatório');
    
    $stmt = $conn->prepare("SELECT id, status, criado_em FROM marcacoes WHERE id = ?");
    $stmt->bind_param('i', $mid);
    $stmt->execute();
    $marc = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$marc) err('Marcação não encontrada');
    if (in_array($marc['status'], ['cancelado', 'reembolsado'])) err('Marcação já está cancelada');
    
    // Determinar se faz reembolso (< 48h desde criação)
    $horas = (time() - strtotime($marc['criado_em'])) / 3600;
    $novo_status = ($marc['status'] === 'confirmado' && $horas <= 48) ? 'reembolsado' : 'cancelado';
    $motivo = 'Cancelado pelo administrador';
    
    $stmt = $conn->prepare("
        UPDATE marcacoes 
        SET status = ?, cancelamento_motivo = ?, cancelado_em = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param('ssi', $novo_status, $motivo, $mid);
    $stmt->execute();
    $stmt->close();
    
    // Atualizar pagamento se reembolso
    if ($novo_status === 'reembolsado') {
        $stmt = $conn->prepare("
            UPDATE pagamentos SET status = 'reembolsado'
            WHERE marcacao_id = ? AND status = 'pago'
        ");
        $stmt->bind_param('i', $mid);
        $stmt->execute();
        $stmt->close();
    }
    
    ok(['novo_status' => $novo_status, 'reembolso' => $novo_status === 'reembolsado']);

// ════════════════════════════════════════════════════════════
//  ESCALÕES
// ════════════════════════════════════════════════════════════

case 'escaloes_list':
    $club_id = (int)($_GET['club_id'] ?? 0);
    
    if ($club_id) {
        $stmt = $conn->prepare("
            SELECT e.id, e.nome, e.idade, e.vagas_totais, e.vagas_ocupadas,
                   (e.vagas_totais - e.vagas_ocupadas) AS vagas_livres,
                   c.nome AS clube_nome
            FROM escaloes e
            JOIN clubes c ON c.id = e.club_id
            WHERE e.club_id = ?
            ORDER BY e.id
        ");
        $stmt->bind_param('i', $club_id);
    } else {
        $stmt = $conn->prepare("
            SELECT e.id, e.nome, e.idade, e.vagas_totais, e.vagas_ocupadas,
                   (e.vagas_totais - e.vagas_ocupadas) AS vagas_livres,
                   c.nome AS clube_nome
            FROM escaloes e
            JOIN clubes c ON c.id = e.club_id
            ORDER BY c.nome, e.id
            LIMIT 500
        ");
    }
    
    if (!$stmt) err('Erro: ' . $conn->error);
    $stmt->execute();
    $escaloes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    ok(['escaloes' => $escaloes]);

// ════════════════════════════════════════════════════════════
//  HORÁRIOS
// ════════════════════════════════════════════════════════════

// ════════════════════════════════════════════════════════════
//  PLANOS
// ════════════════════════════════════════════════════════════

case 'planos_list':
    $planos = $conn->query("SELECT * FROM planos ORDER BY preco ASC")->fetch_all(MYSQLI_ASSOC);
    $extras = $conn->query("SELECT * FROM extras ORDER BY plano_id, id")->fetch_all(MYSQLI_ASSOC);
    ok(['planos' => $planos, 'extras' => $extras]);

case 'planos_update':
    $id    = (int)($_POST['id'] ?? get_body()['id'] ?? 0);
    if (!$id) err('id é obrigatório');
    $nome  = sanitize($_POST['nome']  ?? get_body()['nome']  ?? '');
    $preco = (float)($_POST['preco'] ?? get_body()['preco'] ?? 0);
    $dias  = (int)($_POST['dias_maximos'] ?? get_body()['dias_maximos'] ?? 1);
    $sess  = sanitize($_POST['sessoes_por_semana'] ?? get_body()['sessoes_por_semana'] ?? '');
    $desc  = sanitize($_POST['descricao'] ?? get_body()['descricao'] ?? '');
    $ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : (int)(get_body()['ativo'] ?? 1);
    $stmt  = $conn->prepare("UPDATE planos SET nome=?,preco=?,dias_maximos=?,sessoes_por_semana=?,descricao=?,ativo=? WHERE id=?");
    if (!$stmt) err('Erro: '.$conn->error);
    $stmt->bind_param('sdissii', $nome, $preco, $dias, $sess, $desc, $ativo, $id);
    $stmt->execute();
    $stmt->close();
    ok(['alterado' => true]);

case 'planos_create':
    $nome  = sanitize($_POST['nome']  ?? get_body()['nome']  ?? '');
    $preco = (float)($_POST['preco'] ?? get_body()['preco'] ?? 0);
    $dias  = (int)($_POST['dias_maximos'] ?? get_body()['dias_maximos'] ?? 1);
    $sess  = sanitize($_POST['sessoes_por_semana'] ?? get_body()['sessoes_por_semana'] ?? '');
    $desc  = sanitize($_POST['descricao'] ?? get_body()['descricao'] ?? '');
    if (!$nome) err('Nome é obrigatório');
    $stmt = $conn->prepare("INSERT INTO planos (nome,preco,dias_maximos,sessoes_por_semana,descricao,ativo) VALUES(?,?,?,?,?,1)");
    if (!$stmt) err('Erro: '.$conn->error);
    $stmt->bind_param('sdiss', $nome, $preco, $dias, $sess, $desc);
    $stmt->execute();
    $new_id = $conn->insert_id;
    $stmt->close();
    ok(['criado' => true, 'id' => $new_id]);

case 'planos_delete':
    $id = (int)($_POST['id'] ?? get_body()['id'] ?? 0);
    if (!$id) err('id é obrigatório');
    $stmt = $conn->prepare("DELETE FROM planos WHERE id=?");
    if (!$stmt) err('Erro: '.$conn->error);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    ok(['apagado' => true]);

case 'extras_create':
    $plano_id = (int)($_POST['plano_id'] ?? get_body()['plano_id'] ?? 0);
    $nome     = sanitize($_POST['nome']  ?? get_body()['nome']  ?? '');
    $preco    = (float)($_POST['preco'] ?? get_body()['preco'] ?? 0);
    $desc     = sanitize($_POST['descricao'] ?? get_body()['descricao'] ?? '');
    if (!$plano_id || !$nome) err('plano_id e nome são obrigatórios');
    $stmt = $conn->prepare("INSERT INTO extras (plano_id,nome,preco,descricao) VALUES(?,?,?,?)");
    if (!$stmt) err('Erro: '.$conn->error);
    $stmt->bind_param('isds', $plano_id, $nome, $preco, $desc);
    $stmt->execute();
    $stmt->close();
    ok(['criado' => true]);

case 'extras_delete':
    $id = (int)($_POST['id'] ?? get_body()['id'] ?? 0);
    if (!$id) err('id é obrigatório');
    $stmt = $conn->prepare("DELETE FROM extras WHERE id=?");
    if (!$stmt) err('Erro: '.$conn->error);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    ok(['apagado' => true]);

// ════════════════════════════════════════════════════════════
//  HORÁRIOS (editar vagas/horas, adicionar, apagar)
// ════════════════════════════════════════════════════════════

case 'horarios_list':
    $club_id = (int)($_GET['club_id'] ?? 0);
    $escalao_id = (int)($_GET['escalao_id'] ?? 0);
    
    $where = 'WHERE 1=1';
    $params = [];
    $types = '';
    
    if ($club_id) {
        $where .= ' AND h.club_id = ?';
        $params[] = $club_id;
        $types .= 'i';
    }
    
    if ($escalao_id) {
        $where .= ' AND h.escalao_id = ?';
        $params[] = $escalao_id;
        $types .= 'i';
    }
    
    $query = "
        SELECT h.id, h.dia_semana,
               TIME_FORMAT(h.hora_inicio, '%H:%i') AS hora_inicio,
               TIME_FORMAT(h.hora_fim, '%H:%i') AS hora_fim,
               h.vagas_disponiveis, h.ativo,
               c.nome AS clube_nome, e.nome AS escalao_nome
        FROM horarios h
        JOIN clubes c ON c.id = h.club_id
        JOIN escaloes e ON e.id = h.escalao_id
        $where
        ORDER BY c.nome, e.id, 
                 FIELD(h.dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'),
                 h.hora_inicio
        LIMIT 500
    ";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) err('Erro: ' . $conn->error);
    
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $horarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    ok(['horarios' => $horarios]);

case 'horarios_update':
    $id = (int)($_POST['id'] ?? get_body()['id'] ?? 0);
    if (!$id) err('id é obrigatório');
    
    $hora_inicio = sanitize($_POST['hora_inicio'] ?? get_body()['hora_inicio'] ?? '');
    $hora_fim = sanitize($_POST['hora_fim'] ?? get_body()['hora_fim'] ?? '');
    $vagas = (int)($_POST['vagas_disponiveis'] ?? get_body()['vagas_disponiveis'] ?? 0);
    $ativo = isset($_POST['ativo']) || get_body()['ativo'] ? 1 : 0;
    
    $stmt = $conn->prepare("
        UPDATE horarios 
        SET hora_inicio = ?, hora_fim = ?, vagas_disponiveis = ?, ativo = ?
        WHERE id = ?
    ");
    if (!$stmt) err('Erro: ' . $conn->error);
    
    $stmt->bind_param('sssii', $hora_inicio, $hora_fim, $vagas, $ativo, $id);
    
    if (!$stmt->execute()) {
        $stmt->close();
        err('Erro ao atualizar: ' . $conn->error);
    }
    $stmt->close();
    
    ok(['alterado' => true]);

case 'horarios_delete':
    $id = (int)($_POST['id'] ?? get_body()['id'] ?? 0);
    if (!$id) err('id é obrigatório');
    
    $stmt = $conn->prepare("DELETE FROM horarios WHERE id = ?");
    if (!$stmt) err('Erro: ' . $conn->error);
    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $success = $stmt->affected_rows > 0;
    $stmt->close();
    
    ok(['apagado' => $success]);

case 'horarios_create':
    $club_id    = (int)($_POST['club_id']    ?? get_body()['club_id']    ?? 0);
    $escalao_id = (int)($_POST['escalao_id'] ?? get_body()['escalao_id'] ?? 0);
    $dia        = sanitize($_POST['dia_semana']     ?? get_body()['dia_semana']     ?? '');
    $inicio     = sanitize($_POST['hora_inicio']    ?? get_body()['hora_inicio']    ?? '');
    $fim        = sanitize($_POST['hora_fim']       ?? get_body()['hora_fim']       ?? '');
    $vagas      = (int)($_POST['vagas_disponiveis'] ?? get_body()['vagas_disponiveis'] ?? 15);
    if (!$club_id || !$escalao_id || !$dia || !$inicio) err('Campos obrigatórios em falta');
    $stmt = $conn->prepare("INSERT INTO horarios (club_id,escalao_id,dia_semana,hora_inicio,hora_fim,vagas_disponiveis,ativo) VALUES(?,?,?,?,?,?,1)");
    if (!$stmt) err('Erro: '.$conn->error);
    $stmt->bind_param('iisssi', $club_id, $escalao_id, $dia, $inicio, $fim, $vagas);
    $stmt->execute();
    $new_id = $conn->insert_id;
    $stmt->close();
    ok(['criado' => true, 'id' => $new_id]);

case 'escaloes_update':
    $id             = (int)($_POST['id']             ?? get_body()['id']             ?? 0);
    $vagas_totais   = (int)($_POST['vagas_totais']   ?? get_body()['vagas_totais']   ?? 0);
    $vagas_ocupadas = (int)($_POST['vagas_ocupadas'] ?? get_body()['vagas_ocupadas'] ?? 0);
    if (!$id) err('id é obrigatório');
    $stmt = $conn->prepare("UPDATE escaloes SET vagas_totais=?, vagas_ocupadas=? WHERE id=?");
    if (!$stmt) err('Erro: '.$conn->error);
    $stmt->bind_param('iii', $vagas_totais, $vagas_ocupadas, $id);
    $stmt->execute();
    $stmt->close();
    ok(['alterado' => true]);

// ════════════════════════════════════════════════════════════
//  AÇÃO desconhecida
// ════════════════════════════════════════════════════════════

default:
    err('Ação desconhecida: ' . htmlspecialchars($action));
}
?>