<?php
require '../php/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, nome, senha FROM usuario WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            header('Location: dashboard.php');
            exit;
        } else {
            $_SESSION['login_error'] = 'E-mail ou senha incorretos';
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = 'Erro no sistema';
        header('Location: index.php');
        exit;
    }
}

header('Location: index.php');
?>