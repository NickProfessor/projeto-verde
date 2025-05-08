document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');
    const rememberCheckbox = document.getElementById('remember');
    const notification = document.getElementById('notification');
    const closeNotification = document.querySelector('.close-btn');
    

    const formGroups = document.querySelectorAll('.form-group');
    
    formGroups.forEach(group => {
        const input = group.querySelector('input');
        const label = group.querySelector('label');
        
        input.addEventListener('focus', () => {
            group.classList.add('focused');
        });
        
        input.addEventListener('blur', () => {
            if (!input.value) {
                group.classList.remove('focused');
            }
        });
        

        if (input.value) {
            group.classList.add('focused');
        }
    });
    

    if (passwordToggle) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🔒';
        });
    }
    

    if (closeNotification) {
        closeNotification.addEventListener('click', function() {
            notification.style.display = 'none';
        });
    }
    

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            

            let isValid = true;
            
            if (!emailInput.value) {
                showError('email-error', 'Por favor, insira seu e-mail');
                isValid = false;
            } else if (!isValidEmail(emailInput.value)) {
                showError('email-error', 'Por favor, insira um e-mail válido');
                isValid = false;
            }
            
            if (!passwordInput.value) {
                showError('password-error', 'Por favor, insira sua senha');
                isValid = false;
            }
            
            if (isValid) {

                showNotification('success', 'Login realizado com sucesso! Redirecionando...');
                

                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 1500);
            }
        });
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function showError(id, message) {
        const errorElement = document.getElementById(id);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    }
    
    function showNotification(type, message) {
        if (notification) {
            notification.className = `notification ${type}`;
            notification.querySelector('p').textContent = message;
            notification.style.display = 'flex';
            

            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }
    }
    

    const body = document.querySelector('body');
    let angle = 0;
    
    function updateBackground() {
        angle = (angle + 0.2) % 360;
        const x = 50 + 10 * Math.cos(angle * Math.PI / 180);
        const y = 50 + 10 * Math.sin(angle * Math.PI / 180);
        body.style.backgroundImage = `linear-gradient(${angle}deg, rgba(39, 174, 96, 0.1) 0%, rgba(255, 255, 255, 1) 100%)`;
        requestAnimationFrame(updateBackground);
    }
    

    updateBackground();
});