<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metaetiquetas requeridas -->
<meta charset="utf-8" lang="es">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" lang="ES">
    <?php
        include_once __DIR__ . '/site_settings.php';
        $settings = getSiteSettings($pdo);
    ?>
    <title><?php echo htmlspecialchars($settings['store_name']); ?></title>
    <!-- Dile al navegador que responda al ancho de la pantalla -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" lang="es">
    <!-- Bootstrap 5.3 (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Bootstrap Icons (reemplaza Glyphicons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Shim v4: mantiene funcionando los nombres viejos (fa-dashboard, fa-money, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/v4-shims.min.css">
    <!-- Fix: v4-shims usa @font-face "FontAwesome" (v4), hay que declararla -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/v4-font-face.min.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="./images/<?php echo htmlspecialchars($settings['logo']); ?>" type="image/x-icon">
    <!-- DataTables (build BS5) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css">
    <!-- AdminLTE 3.2 (compatible con Bootstrap 5 vía plugin oficial) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Magnify -->
    <link rel="stylesheet" href="magnify/magnify.min.css">
    <!-- Sistema de diseño Conceiba (paleta esmeralda + dorado, glassmorphism) -->
    <link rel="stylesheet" href="css/theme.css">
    <!-- Compatibilidad mínima BS3->BS5 para páginas aún no migradas -->
    <link rel="stylesheet" href="css/bs3-compat.css">
    <!-- Colores dinámicos desde el panel de administración -->
    <style>
        :root {
            --c-emerald-700: <?php echo htmlspecialchars($settings['color_primary']); ?>;
            --c-emerald-900: <?php echo htmlspecialchars($settings['color_primary_dark']); ?>;
            --c-gold-600: <?php echo htmlspecialchars($settings['color_accent']); ?>;
            --accent: <?php echo htmlspecialchars($settings['color_accent']); ?>;
        }
    </style>
    <!-- jQuery (requerido por AdminLTE 3 y plugins) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 Bundle (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <!-- DataTables (build BS5) -->
    <script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js"></script>
    <!-- AdminLTE 3.2 -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Fuente de Google -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- Google Recaptcha -->
    <script src='https://www.google.com/recaptcha/api.js?render=6LePptsrAAAAAEIfOcXRMroyUqKexS28qsh1rX-b'></script>
    <script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LePptsrAAAAAEIfOcXRMroyUqKexS28qsh1rX-b', {action: 'submit'}).then(function(token) {
            document.querySelectorAll('form').forEach(function(form) {
                let input = form.querySelector('input[name="g-recaptcha-response"]');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'g-recaptcha-response';
                    form.appendChild(input);
                }
                input.value = token;
            });
        });
    });
    </script>

    <!-- CSS personalizado (ajustes puntuales del sitio) -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- Theme switcher: aplica el tema guardado antes de pintar, evita parpadeo -->
    <script>
        (function() {
            var saved = localStorage.getItem('theme');
            if (!saved) {
                // Sin preferencia guardada: usar la del sistema/navegador del cliente,
                // y si tampoco se puede detectar, oscuro por defecto.
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                    saved = 'light';
                } else {
                    saved = 'dark';
                }
            }
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
</head>

<script>
    // El botón de tema ahora vive fijo dentro del navbar (ver includes/navbar.php)
    // en vez de crearse dinámicamente como botón flotante.
    document.addEventListener('DOMContentLoaded', function () {
        const theme = document.documentElement.getAttribute('data-theme') || 'light';
        const icon = document.getElementById('theme-icon');
        if (icon) {
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }
    });

    function toggleThemeConceiba() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        const icon = document.getElementById('theme-icon');
        if (icon) {
            icon.className = newTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }
    }
</script>
