import './bootstrap';
import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.data('theme', () => ({
        isDarkMode: localStorage.getItem('theme') === 'dark' || 
                    (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        
        init() {
            this.applyTheme();
        },

        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            this.applyTheme();
        },

        applyTheme() {
            if (this.isDarkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }));
});

window.Alpine = Alpine;
Alpine.start();