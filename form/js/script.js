document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const strengthBar = document.getElementById('strength-bar');
    const termsCheckbox = document.getElementById('terms');
    const termsLink = document.getElementById('terms-link');
    const termsModal = document.getElementById('terms-modal');
    const closeModal = document.querySelector('.close-modal');
    const acceptTermsBtn = document.getElementById('accept-terms');
    
    termsLink.addEventListener('click', function(e) {
        e.preventDefault();
        termsModal.classList.remove('hidden');
        termsModal.classList.add('show');
    });
    
    closeModal.addEventListener('click', function() {
        termsModal.classList.remove('show');
        termsModal.classList.add('hidden');
    });
    
    acceptTermsBtn.addEventListener('click', function() {
        termsCheckbox.checked = true;
        termsModal.classList.remove('show');
        termsModal.classList.add('hidden');
        updateTermsText();
    });
    
    function updateTermsText() {
        const termsText = document.getElementById('terms-text');
        termsText.innerHTML = termsCheckbox.checked 
            ? 'Li e aceito os <a href="#" id="terms-link">Termos de Serviço</a> ✔'
            : 'Li e aceito os <a href="#" id="terms-link">Termos de Serviço</a>';
    }
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength += 1;
        if (password.length >= 12) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[a-z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        return strength;
    }
    
    function updateStrengthBar(strength) {
        const percent = Math.min((strength / 6) * 100, 100);
        strengthBar.style.width = percent + '%';
        
        if (strength <= 2) {
            strengthBar.className = 'strength-bar strength-weak';
        } else if (strength <= 4) {
            strengthBar.className = 'strength-bar strength-medium';
        } else {
            strengthBar.className = 'strength-bar strength-strong';
        }
    }
    
    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const strength = calculatePasswordStrength(password);
        updateStrengthBar(strength);
    });
    
    form.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            showError('terms-error', 'Você deve aceitar os Termos de Serviço');
        }
    });
    
    function showError(id, message) {
        const errorElement = document.getElementById(id);
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    window.addEventListener('click', function(event) {
        if (event.target === termsModal) {
            termsModal.classList.remove('show');
            termsModal.classList.add('hidden');
        }
    });
});