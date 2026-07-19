<?php

// Ativar relatórios de erro (desativar mysqli_report em produção)
if (getenv('ENVIRONMENT') !== 'production') {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
} else {
    mysqli_report(MYSQLI_REPORT_OFF);
}

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: 3306;
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'vaijogar';
$environment = getenv('ENVIRONMENT') ?: 'development';


try {
    $conn = new mysqli(
        $db_host,
        $db_user,
        $db_pass,
        $db_name,
        (int) $db_port
    );
    
    // Verificar se houve erro na ligação
    if ($conn->connect_error) {
        throw new Exception("Erro de ligação: " . $conn->connect_error);
    }
    
    // Configurar charset para UTF-8
    $conn->set_charset("utf8mb4");
    
    // Verificar se a ligação foi bem-sucedida
    if (!$conn->ping()) {
        throw new Exception("Falha ao comunicar com a base de dados.");
    }
    
} catch (Exception $e) {
    // Em produção, não revelar detalhes de erro ao cliente
    $error_message = $environment === 'production' 
        ? "Erro ao conectar com a base de dados"
        : $e->getMessage();
    
    // Registar erro em arquivo de log
    if (function_exists('error_log')) {
        error_log("Database Connection Error: " . $e->getMessage());
    }
    
    // Enviar resposta de erro
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode([
        "sucesso" => false,
        "erro" => $error_message
    ]));
}

// ============================================================
// Funções Úteis para Trabalhar com a Ligação
// ============================================================

/**
 * Executar uma query preparada com bind_param
 * 
 * @param string $sql - Query SQL com placeholders (?)
 * @param array $params - Array com os parâmetros e tipos [param1, param2, ...]
 * @param string $types - String de tipos (s=string, i=integer, d=double, b=blob)
 * @return mysqli_result|bool - Resultado da query ou false em erro
 */
function executarQuery($sql, $params = [], $types = '') {
    global $conn;
    
    try {
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro ao preparar query: " . $conn->error);
        }
        
        // Se houver parâmetros, fazer bind
        if (!empty($params) && !empty($types)) {
            $stmt->bind_param($types, ...$params);
        }
        
        // Executar
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar query: " . $stmt->error);
        }
        
        // Retornar resultado (pode ser null para INSERT/UPDATE)
        return $stmt->get_result();
        
    } catch (Exception $e) {
        error_log("Query Error: " . $e->getMessage() . " | SQL: " . $sql);
        return false;
    }
}

/**
 * Obter resultado de uma query como array associativo
 * 
 * @param string $sql - Query SQL
 * @param array $params - Parâmetros da query
 * @param string $types - Tipos dos parâmetros
 * @return array|null - Array com resultado ou null se nenhum resultado
 */
function obterResultado($sql, $params = [], $types = '') {
    $result = executarQuery($sql, $params, $types);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Obter múltiplos resultados de uma query
 * 
 * @param string $sql - Query SQL
 * @param array $params - Parâmetros da query
 * @param string $types - Tipos dos parâmetros
 * @return array - Array com todos os resultados
 */
function obterTodosResultados($sql, $params = [], $types = '') {
    $result = executarQuery($sql, $params, $types);
    $resultados = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }
    }
    
    return $resultados;
}

/**
 * Executar uma query de inserção/atualização/deleção
 * 
 * @param string $sql - Query SQL
 * @param array $params - Parâmetros da query
 * @param string $types - Tipos dos parâmetros
 * @return bool - true se sucesso, false se erro
 */
function executarModificacao($sql, $params = [], $types = '') {
    global $conn;
    
    try {
        $result = executarQuery($sql, $params, $types);
        return $result !== false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Obter o ID da última inserção
 * 
 * @return int - ID da última inserção
 */
function obterUltimoId() {
    global $conn;
    return $conn->insert_id;
}

/**
 * Obter o número de linhas afetadas
 * 
 * @return int - Número de linhas afetadas
 */
function obterLinhasAfetadas() {
    global $conn;
    return $conn->affected_rows;
}

/**
 * Escapar string para usar em queries (não recomendado, use prepared statements)
 * 
 * @param string $string - String a escapar
 * @return string - String escapada
 */
function escaparString($string) {
    global $conn;
    return $conn->real_escape_string($string);
}

/**
 * Iniciar transação
 * 
 * @return bool - true se sucesso
 */
function iniciarTransacao() {
    global $conn;
    return $conn->begin_transaction();
}

/**
 * Confirmar transação
 * 
 * @return bool - true se sucesso
 */
function confirmarTransacao() {
    global $conn;
    return $conn->commit();
}

/**
 * Reverter transação
 * 
 * @return bool - true se sucesso
 */
function reverterTransacao() {
    global $conn;
    return $conn->rollback();
}

// Verificar se conexão está ativa ao final do carregamento
if (!isset($conn) || !$conn) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode([
        "sucesso" => false,
        "erro" => "Erro ao inicializar base de dados"
    ]));
}

?>