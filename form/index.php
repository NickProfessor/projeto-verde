<?php
require 'php/config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Crie Sua Conta</h1>
        
        <?php if (isset($_SESSION['form_error'])): ?>
            <div class="notification error">
                <?= htmlspecialchars($_SESSION['form_error'], ENT_QUOTES, 'UTF-8'); ?>
                <button class="close-btn">&times;</button>
            </div>
            <?php unset($_SESSION['form_error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['registration_success'])): ?>
            <div class="notification success">
                Cadastro realizado com sucesso!
                <button class="close-btn">&times;</button>
            </div>
            <?php unset($_SESSION['registration_success']); ?>
        <?php endif; ?>
        
        <form id="registrationForm" method="POST" action="php/register.php" autocomplete="on" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
            
            <div class="form-group">
                <label for="name">Nome Completo*</label>
                <input type="text" id="name" name="name" required minlength="3" maxlength="100"
                    pattern="[A-Za-zÀ-ÿ ]+" title="Apenas letras e espaços" 
                    value="<?= htmlspecialchars($_SESSION['form_data']['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    autocomplete="name">
                <div id="name-error" class="error">
                    <?= isset($_SESSION['errors']['name']) ? htmlspecialchars($_SESSION['errors']['name'], ENT_QUOTES, 'UTF-8') : '' ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">E-mail*</label>
                <input type="email" id="email" name="email" required maxlength="100"
                    value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    autocomplete="email">
                <div id="email-error" class="error">
                    <?= isset($_SESSION['errors']['email']) ? htmlspecialchars($_SESSION['errors']['email'], ENT_QUOTES, 'UTF-8') : '' ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Senha*</label>
                <input type="password" id="password" name="password" required minlength="8" maxlength="100"
                    autocomplete="new-password" aria-describedby="password-hint"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$">
                <div class="password-strength">
                    <div class="strength-bar" id="strength-bar"></div>
                </div>
                <div id="password-hint" class="hint">
                    Mínimo 8 caracteres incluindo maiúsculas, minúsculas, números e caracteres especiais (@#$%^&+=)
                </div>
                <div id="password-error" class="error">
                    <?= isset($_SESSION['errors']['password']) ? htmlspecialchars($_SESSION['errors']['password'], ENT_QUOTES, 'UTF-8') : '' ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirme sua Senha*</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    autocomplete="new-password">
                <div id="confirm_password-error" class="error">
                    <?= isset($_SESSION['errors']['confirm_password']) ? htmlspecialchars($_SESSION['errors']['confirm_password'], ENT_QUOTES, 'UTF-8') : '' ?>
                </div>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="terms" name="terms" required
                    <?= isset($_POST['terms']) ? 'checked' : ''; ?>>
                <label for="terms">
                    <span id="terms-text">Li e aceito os <a href="#" id="terms-link">Termos de Serviço</a></span>
                </label>
                <div id="terms-error" class="error">
                    <?= isset($_SESSION['errors']['terms']) ? htmlspecialchars($_SESSION['errors']['terms'], ENT_QUOTES, 'UTF-8') : '' ?>
                </div>
            </div>
            
            <div id="terms-modal" class="modal hidden">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h2>Termos de Serviço</h2>
                    <div class="terms-content">
                        <p>Ao se cadastrar em nosso serviço, você concorda com os seguintes termos:</p>
                        <ol>
                            <li>Você é responsável por manter a confidencialidade de sua conta e senha</li>
                            <li>Você concorda em nos fornecer informações precisas e atualizadas</li>
                            <li>Você não usará este serviço para atividades ilegais ou não autorizadas</li>
                            <li>Reservamo-nos o direito de modificar ou descontinuar o serviço a qualquer momento</li>
                            <li>Seu uso do serviço está sujeito às leis e regulamentos aplicáveis</li>
                        </ol>
                    </div>
                    <button id="accept-terms" class="btn-accept">Aceitar Termos</button>
                </div>
            </div>
            
            <div class="honeypot">
                <label for="website" aria-hidden="true" class="visually-hidden">Não preencha este campo</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" class="visually-hidden">
            </div>
            
            <button type="submit" id="submit-btn" class="btn-primary">Registrar</button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
<?php
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?>