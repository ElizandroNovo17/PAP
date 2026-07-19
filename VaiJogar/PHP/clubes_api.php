<?php
// ============================================================
//  PHP/clubes_api.php
//  API de dados de clubes, escalões e horários
//  Retorna informações completas da BD
// ============================================================

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexao.php';

function err($m) {
    echo json_encode(['ok'=>false,'erro'=>$m,'encontrado'=>false]); 
    exit;
}

$action = $_GET['action'] ?? '';

// ════════════════════════════════════════════════════════════
//  AÇÃO: clube — retorna dados completos de 1 clube
// ════════════════════════════════════════════════════════════
if ($action === 'clube') {
    $nome      = trim($_GET['nome'] ?? '');
    $modalidade = trim($_GET['modalidade'] ?? '');
    
    if (!$nome || !$modalidade) {
        err('Nome e modalidade são obrigatórios');
    }
    
    // Buscar clube
    $stmt = $conn->prepare("
        SELECT id, nome, modalidade, localizacao, latitude, longitude,
               recinto, divisao, descricao, imagem_url, telefone, email,
               website, facebook, instagram, inscricao_preco
        FROM clubes
        WHERE nome = ? AND modalidade = ?
        LIMIT 1
    ");
    if (!$stmt) {
        err('Erro na query: ' . $conn->error);
    }
    $stmt->bind_param('ss', $nome, $modalidade);
    $stmt->execute();
    $result = $stmt->get_result();
    $clube = $result->fetch_assoc();
    
    if (!$clube) {
        err('Clube não encontrado: ' . $nome);
    }
    
    $club_id = (int)$clube['id'];
    
    // Buscar escalões deste clube
    $stmt_esc = $conn->prepare("
        SELECT id, nome, idade, vagas_totais, vagas_ocupadas,
               (vagas_totais - vagas_ocupadas) AS vagas_livres
        FROM escaloes
        WHERE club_id = ?
        ORDER BY id
    ");
    if (!$stmt_esc) {
        err('Erro escalões: ' . $conn->error);
    }
    $stmt_esc->bind_param('i', $club_id);
    $stmt_esc->execute();
    $escaloes = $stmt_esc->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Para cada escalão, buscar seus horários
    $horarios_por_escalao = [];
    foreach ($escaloes as &$esc) {
        $esc_id = (int)$esc['id'];
        $stmt_hor = $conn->prepare("
            SELECT id, dia_semana, 
                   TIME_FORMAT(hora_inicio, '%H:%i') AS hora_inicio,
                   TIME_FORMAT(hora_fim, '%H:%i') AS hora_fim,
                   vagas_disponiveis
            FROM horarios
            WHERE escalao_id = ? AND ativo = 1
            ORDER BY FIELD(dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo')
        ");
        if (!$stmt_hor) {
            continue; // Pula se houver erro
        }
        $stmt_hor->bind_param('i', $esc_id);
        $stmt_hor->execute();
        $esc['horarios'] = $stmt_hor->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Retornar dados completos
    $clube['encontrado'] = true;
    $clube['club_id'] = $club_id;
    $clube['escaloes'] = $escaloes;
    
    echo json_encode($clube);
    exit;
}

// ════════════════════════════════════════════════════════════
//  AÇÃO: modalidades — lista clubes por modalidade
// ════════════════════════════════════════════════════════════
if ($action === 'modalidades') {
    $mod = trim($_GET['modalidade'] ?? '');
    
    if (!$mod) {
        err('Modalidade é obrigatória');
    }
    
    $stmt = $conn->prepare("
        SELECT id, nome, modalidade, latitude, longitude,
               localizacao, imagem_url, recinto, divisao,
               inscricao_preco
        FROM clubes
        WHERE modalidade = ? AND ativo = 1
        ORDER BY nome
    ");
    if (!$stmt) {
        err('Erro: ' . $conn->error);
    }
    $stmt->bind_param('s', $mod);
    $stmt->execute();
    $clubes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'ok' => true,
        'modalidade' => $mod,
        'total' => count($clubes),
        'clubes' => $clubes
    ]);
    exit;
}

// ════════════════════════════════════════════════════════════
//  AÇÃO: planos — lista planos disponíveis
// ════════════════════════════════════════════════════════════
if ($action === 'planos') {
    $stmt = $conn->prepare("
        SELECT id, nome, preco, dias_maximos, sessoes_por_semana, descricao
        FROM planos
        WHERE ativo = 1
        ORDER BY preco
    ");
    if (!$stmt) {
        err('Erro: ' . $conn->error);
    }
    $stmt->execute();
    $planos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'ok' => true,
        'planos' => $planos
    ]);
    exit;
}

// ════════════════════════════════════════════════════════════
//  AÇÃO: escaloes — lista escalões de um clube
// ════════════════════════════════════════════════════════════
if ($action === 'escaloes') {
    $club_id = (int)($_GET['club_id'] ?? 0);
    
    if (!$club_id) {
        err('club_id é obrigatório');
    }
    
    $stmt = $conn->prepare("
        SELECT id, nome, idade, vagas_totais, vagas_ocupadas,
               (vagas_totais - vagas_ocupadas) AS vagas_livres
        FROM escaloes
        WHERE club_id = ?
        ORDER BY id
    ");
    if (!$stmt) {
        err('Erro: ' . $conn->error);
    }
    $stmt->bind_param('i', $club_id);
    $stmt->execute();
    $escaloes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Buscar horários para cada escalão
    foreach ($escaloes as &$esc) {
        $esc_id = (int)$esc['id'];
        $stmt_hor = $conn->prepare("
            SELECT id, dia_semana,
                   TIME_FORMAT(hora_inicio, '%H:%i') AS hora_inicio,
                   TIME_FORMAT(hora_fim, '%H:%i') AS hora_fim,
                   vagas_disponiveis
            FROM horarios
            WHERE escalao_id = ? AND ativo = 1
            ORDER BY FIELD(dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo')
        ");
        $stmt_hor->bind_param('i', $esc_id);
        $stmt_hor->execute();
        $esc['horarios'] = $stmt_hor->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    echo json_encode([
        'ok' => true,
        'club_id' => $club_id,
        'escaloes' => $escaloes
    ]);
    exit;
}

// ════════════════════════════════════════════════════════════
//  AÇÃO: horarios — lista horários de um escalão
// ════════════════════════════════════════════════════════════
if ($action === 'horarios') {
    $escalao_id = (int)($_GET['escalao_id'] ?? 0);
    
    if (!$escalao_id) {
        err('escalao_id é obrigatório');
    }
    
    $stmt = $conn->prepare("
        SELECT id, dia_semana,
               TIME_FORMAT(hora_inicio, '%H:%i') AS hora_inicio,
               TIME_FORMAT(hora_fim, '%H:%i') AS hora_fim,
               vagas_disponiveis
        FROM horarios
        WHERE escalao_id = ? AND ativo = 1
        ORDER BY FIELD(dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo')
    ");
    if (!$stmt) {
        err('Erro: ' . $conn->error);
    }
    $stmt->bind_param('i', $escalao_id);
    $stmt->execute();
    $horarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'ok' => true,
        'escalao_id' => $escalao_id,
        'horarios' => $horarios
    ]);
    exit;
}

// ════════════════════════════════════════════════════════════
//  AÇÃO desconhecida
// ════════════════════════════════════════════════════════════
err('Ação desconhecida: ' . htmlspecialchars($action));
?>