<?php
require '../php/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Projeto Verde</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <div class="user-info">
            Olá, <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?>
            <a href="logout.php" class="logout-btn">Sair</a>
        </div>
        
        <h1>Painel do Usuário</h1>
        <div class="dashboard-content">
        </div>
    </div>
</body>
</html>