<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['form_error'] = 'Método de requisição inválido';
    header('Location: ../index.php');
    exit;
}

if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['form_error'] = 'Token de segurança inválido';
    header('Location: ../index.php');
    exit;
}

if (rateLimitExceeded('registration_attempt')) {
    $_SESSION['form_error'] = 'Muitas tentativas. Tente novamente mais tarde.';
    header('Location: ../index.php');
    exit;
}

$fullName = sanitizeInput($_POST['name'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$termsAccepted = isset($_POST['terms']);

$errors = [];

if (empty($fullName) || strlen($fullName) < 3) {
    $errors['name'] = 'Nome deve conter pelo menos 3 caracteres';
} elseif (!preg_match('/^[A-Za-zÀ-ÿ ]+$/', $fullName)) {
    $errors['name'] = 'Nome contém caracteres inválidos';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'E-mail inválido';
} elseif (strlen($email) > 100) {
    $errors['email'] = 'E-mail muito longo';
}

if (strlen($password) < 8) {
    $errors['password'] = 'Senha deve ter pelo menos 8 caracteres';
} elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || 
           !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
    $errors['password'] = 'Inclua maiúsculas, minúsculas, números e caracteres especiais (@#$%^&+=)';
}

if ($password !== $confirmPassword) {
    $errors['confirm_password'] = 'As senhas não coincidem';
}

if (!$termsAccepted) {
    $errors['terms'] = 'Você deve aceitar os Termos de Serviço';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ../index.php');
    exit;
}

try {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT id FROM usuario WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $errors['email'] = 'Este e-mail já está cadastrado';
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../index.php');
        exit;
    }
    
    
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, tipo, ativo, data_cadastro) 
                          VALUES (?, ?, ?, 1, 1, NOW())");
    $stmt->execute([$fullName, $email, $passwordHash]);
    
    $_SESSION['registration_success'] = true;
    header('Location: ../index.php');
    exit;

} catch (PDOException $e) {
    error_log("Erro no cadastro: " . $e->getMessage());
    $_SESSION['form_error'] = 'Erro no sistema. Por favor, tente novamente.';
    header('Location: ../index.php');
    exit;
}
?>