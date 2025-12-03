<?php

/**
 * Theme Loader - Conceiba
 * Carga automática de archivos CSS y JS del sistema de temas
 */

// Rutas de los archivos
$theme_css = 'assets/css/theme-switcher.css';
$theme_js = 'assets/js/theme-switcher.js';
$responsive_css = 'assets/css/responsive.css';

// Verificar si estamos en un subdirectorio (ej: maria/)
$path_prefix = file_exists($theme_css) ? '' : '../';
?>

<!-- Theme Switcher CSS -->
<link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/theme-switcher.css">
<link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/responsive.css">

<!-- Theme Switcher JS (se carga al final con scripts.php) -->
<script>
    // Aplicar tema inmediatamente para evitar parpadeo
    (function() {
        var theme = localStorage.getItem('conceiba_theme');
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    })();
</script>