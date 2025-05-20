document.addEventListener('DOMContentLoaded', function() {
    const actionItems = document.querySelectorAll('.action-item');
    actionItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    this.style.textDecoration = 'line-through';
                    this.style.opacity = '0.7';
                } else {
                    this.style.textDecoration = 'none';
                    this.style.opacity = '1';
                }
            }
        });
    });

    const shareBtn = document.getElementById('share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: 'Meu Progresso em Sustentabilidade',
                    text: 'Confira minhas conquistas e recomendações para um estilo de vida mais sustentável!',
                    url: window.location.href
                })
                .catch(err => {
                    console.log('Erro ao compartilhar:', err);
                    alert('Ocorreu um erro ao tentar compartilhar.');
                });
            } else {
                alert('Seu navegador não suporta a API de compartilhamento. Copie o link manualmente: ' + window.location.href);
            }
        });
    }

    const animateElements = () => {
        const elements = document.querySelectorAll('.question-box, .recommendation-category, .action-plan');
        elements.forEach((el, index) => {
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 150);
        });
    };
    
    document.querySelectorAll('.question-box, .recommendation-category, .action-plan').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });
    
    setTimeout(animateElements, 300);
});