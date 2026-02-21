import './styles/app.css';

// Alpine.js for interactive components
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Auto-dismiss flash messages
    document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    // Confirm delete dialogs
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('submit', function(e) {
            if (!confirm(btn.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});
