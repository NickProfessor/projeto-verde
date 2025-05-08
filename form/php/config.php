<?php
session_start();


define('DB_HOST', 'localhost');
define('DB_NAME', 'db_projeto_verde');
define('DB_USER', 'root');
define('DB_PASS', '&tec77@info!');

function getConnection() {
    try {
        $conn = new PDO(
            "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("Sistema temporariamente indisponível. Por favor, tente novamente mais tarde.");
    }
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function rateLimitExceeded($key, $limit = 5, $interval = 300) {
    if (!isset($_SESSION['rate_limits'][$key])) {
        $_SESSION['rate_limits'][$key] = [
            'attempts' => 0,
            'last_attempt' => 0
        ];
    }
    
    $now = time();
    $rateData = &$_SESSION['rate_limits'][$key];
    
    if ($rateData['last_attempt'] + $interval < $now) {
        $rateData['attempts'] = 0;
        $rateData['last_attempt'] = $now;
    }
    
    $rateData['attempts']++;
    $rateData['last_attempt'] = $now;
    
    return $rateData['attempts'] > $limit;
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}
?>