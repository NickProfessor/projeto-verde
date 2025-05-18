<?php
require '../php/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Projeto Verde</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Acesse sua conta</h1>
        
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="notification error">
                <?= htmlspecialchars($_SESSION['login_error'], ENT_QUOTES, 'UTF-8'); ?>
                <button class="close-btn">&times;</button>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>

        <form method="POST" action="auth.php">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-primary">Entrar</button>
            
            <div class="register-link">
                Não tem conta? <a href="../index.php">Cadastre-se</a>
            </div>
        </form>
    </div>
    <script src="../js/login.js"></script>
</body>
</html>