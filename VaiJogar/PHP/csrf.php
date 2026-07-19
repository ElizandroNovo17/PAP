<?php
// ============================================================
//  PHP/csrf.php
//  Funções de proteção CSRF
//  Incluir em todos os ficheiros PHP que processam formulários
// ============================================================

/**
 * Gera (ou devolve existente) um token CSRF para a sessão atual.
 * Chamar no início de qualquer página que tenha formulário.
 */
function csrf_gerar(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Devolve o campo hidden HTML com o token CSRF.
 * Usar dentro de qualquer <form>:
 *   <?= csrf_campo() ?>
 */
function csrf_campo(): string {
    $token = csrf_gerar();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verifica se o token CSRF do pedido é válido.
 * Chama err() automaticamente se inválido.
 * Usar no início de qualquer ação POST.
 */
function csrf_verificar(): void {
    $token_recebido = $_POST['csrf_token']
        ?? $_SERVER['HTTP_X_CSRF_TOKEN']   // header enviado via fetch
        ?? '';
    // Fallback: também procurar dentro do body JSON (Content-Type: application/json)
    if (!$token_recebido) {
        $raw = file_get_contents('php://input');
        if ($raw) {
            $j = json_decode($raw, true);
            if (is_array($j) && !empty($j['csrf_token'])) {
                $token_recebido = $j['csrf_token'];
            }
        }
    }
    $token_sessao   = $_SESSION['csrf_token'] ?? '';

    if (!$token_sessao || !hash_equals($token_sessao, $token_recebido)) {
        http_response_code(403);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Pedido inválido. Recarrega a página e tenta novamente.']);
        exit;
    }
}
