document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const strengthBar = document.getElementById('strength-bar');
    const emailInput = document.getElementById('email');
    const notification = document.getElementById('notification');
    
    
    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const strength = calculatePasswordStrength(password);
        updateStrengthBar(strength);
        validatePassword(password);
    });
    
    
    confirmPasswordInput.addEventListener('input', function() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            showError('confirm-password-error', 'As senhas não coincidem');
        } else {
            hideError('confirm-password-error');
        }
    });
    
    
    emailInput.addEventListener('blur', function() {
        validateEmail(emailInput.value);
    });
    
   
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        
        
        if (document.getElementById('name').value.length < 3) {
            showError('name-error', 'Por favor, insira seu nome completo (mínimo 3 caracteres)');
            isValid = false;
        } else {
            hideError('name-error');
        }
        
        
        if (!validateEmail(emailInput.value)) {
            isValid = false;
        }
        
        
        if (!validatePassword(passwordInput.value)) {
            isValid = false;
        }
        
        
        if (passwordInput.value !== confirmPasswordInput.value) {
            showError('confirm-password-error', 'As senhas não coincidem');
            isValid = false;
        } else {
            hideError('confirm-password-error');
        }
        
        
        if (!document.getElementById('terms').checked) {
            showError('terms-error', 'Você deve aceitar os termos para continuar');
            isValid = false;
        } else {
            hideError('terms-error');
        }
        
        if (isValid) {
            checkEmailExists(emailInput.value).then(exists => {
                if (exists) {
                    showNotification('Este e-mail já está cadastrado', 'error');
                } else {
                    showNotification('Cadastro realizado com sucesso!', 'success');
                    
                }
            });
        }
    });
    
    function showNotification(message, type) {
        notification.textContent = message;
        notification.className = `notification ${type}`;
        notification.classList.remove('hidden');
        
        
        const closeBtn = document.createElement('button');
        closeBtn.className = 'close-btn';
        closeBtn.innerHTML = '&times;';
        closeBtn.onclick = () => notification.classList.add('hidden');
        
        notification.appendChild(closeBtn);
        

        setTimeout(() => {
            notification.classList.add('hidden');
        }, 5000);
    }
    
    function showError(id, message) {
        const errorElement = document.getElementById(id);
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    function hideError(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength += 1;
        if (password.length >= 12) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[a-z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        
        return Math.min(strength, 5);
    }
    
    function updateStrengthBar(strength) {
        const colors = ['#e74c3c', '#e67e22', '#f1c40f', '#2ecc71', '#27ae60'];
        const width = (strength / 5) * 100;
        
        strengthBar.style.width = width + '%';
        strengthBar.style.backgroundColor = colors[strength - 1] || colors[0];
    }
    
    function validatePassword(password) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;
        
        if (!regex.test(password)) {
            showError('password-error', 'A senha deve ter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, números e caracteres especiais');
            return false;
        } else {
            hideError('password-error');
            return true;
        }
    }
    
    function validateEmail(email) {
        if (!email.includes('@')) {
            showError('email-error', 'O e-mail deve conter @');
            showNotification('Formato de e-mail inválido. Deve conter @', 'error');
            return false;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(email)) {
            showError('email-error', 'Por favor, insira um e-mail válido');
            showNotification('Formato de e-mail inválido', 'error');
            return false;
        }
        
        hideError('email-error');
        return true;
    }
    
    function checkEmailExists(email) {
        // retirar, exemplo para funcionalidade coma 
        const existingEmails = ['teste@exemplo.com', 'usuario@dominio.com'];
        
        return new Promise(resolve => {
            setTimeout(() => {
                resolve(existingEmails.includes(email.toLowerCase()));
            }, 500);
        });
    }
});