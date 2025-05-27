import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

// Inicialização segura com verificação
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.plugin(focus); // Adicione seus plugins aqui
    Alpine.start();
}