<?php
session_start();
session_unset();
session_destroy();

// Redireciona para o formulário
header('Location: formulario.php');
exit;
