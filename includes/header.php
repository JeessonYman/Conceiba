<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metaetiquetas requeridas -->
<meta charset="utf-8" lang="es">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" lang="ES">
    <title>Conceiba</title>
    <!-- Dile al navegador que responda al ancho de la pantalla -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" lang="es">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Font Awesome 6 para iconos adicionales -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="./images/logo.png" type="image/x-icon">
    <!-- DataTables -->
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- Magnify -->
    <link rel="stylesheet" href="magnify/magnify.min.css">
    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- AdminLTE -->
    <script src="dist/js/adminlte.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Compatibilidad con navegadores modernos -->
    <script>
    if (typeof Promise === 'undefined') {
        document.write('<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>');
    }
    if (typeof fetch === 'undefined') {
        document.write('<script src="https://cdn.jsdelivr.net/npm/whatwg-fetch@3.6.2/dist/fetch.umd.min.js"><\/script>');
    }
    </script>

  	<!-- Fuente de Google -->
  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- PayPal se carga en cart_view.php -->
    <!-- Google Recaptcha -->
    <script src='https://www.google.com/recaptcha/api.js?render=6LePptsrAAAAAEIfOcXRMroyUqKexS28qsh1rX-b'></script>
    <script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LePptsrAAAAAEIfOcXRMroyUqKexS28qsh1rX-b', {action: 'submit'}).then(function(token) {
            // Agregar el token a todos los formularios
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

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Estilos para modo oscuro -->
    <style>
        /* Variables globales para el tema */
        :root {
            --bg-main: #fff;
            --text-main: #333;
            --bg-nav: #00a65a;
            --nav-text: #fff;
            --box-shadow: rgba(0, 0, 0, 0.1);
            --sidebar-bg: #f4f4f4;
            --product-box-bg: #f8f9fa;
            --heading-color: #333;
            --footer-bg: #00a65a;
        }

        [data-theme="dark"] {
            --bg-main: #1a1a1a;
            --text-main: #fff;
            --bg-nav: #00a65a;
            --nav-text: #fff;
            --box-shadow: rgba(255, 255, 255, 0.1);
            --sidebar-bg: #2d2d2d;
            --product-box-bg: #333;
            --heading-color: #fff;
            --footer-bg: #00a65a;
        }

        /* Aplicar variables a elementos específicos */
        body.hold-transition {
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
        }

        /* Navbar fijo */
        .main-header {
            position: fixed !important;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .main-header .navbar {
            background-color: var(--bg-nav) !important;
            color: var(--nav-text) !important;
            margin-bottom: 0;
        }

        .navbar-nav > li > a,
        .navbar-nav > li > a > span,
        .navbar-nav .cart-items,
        .dropdown-menu > li > a {
            color: var(--nav-text) !important;
        }

        /* Ajustes específicos para el carrito */
        .cart-items,
        .cart-menu > a > span {
            color: var(--nav-text) !important;
        }

        /* Ajustes para los títulos principales */
        h1, h2, h3,
        .impact-header,
        .location-header,
        .families-header,
        .impact-text,
        .families-text,
        .trees-text,
        .text-black,
        .text-center {
            color: var(--text-main) !important;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        h3.text-center {
            font-size: 1.8rem;
            line-height: 1.4;
        }

        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] .text-black,
        [data-theme="dark"] .text-center,
        [data-theme="dark"] .impact-header,
        [data-theme="dark"] .location-header,
        [data-theme="dark"] .families-header,
        [data-theme="dark"] .impact-text,
        [data-theme="dark"] .families-text,
        [data-theme="dark"] .trees-text {
            color: #fff !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        /* Ajuste para el contenido debajo del navbar fijo */
        .content-wrapper {
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
            margin-top: 50px;
        }

        /* Footer fijo */
        .main-footer {
            position: fixed !important;
            bottom: 0;
            width: 100%;
            background-color: var(--footer-bg) !important;
            color: #fff !important;
            z-index: 1000;
        }

        .main-footer a {
            color: #fff !important;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .main-footer a:hover {
            color: #f0f0f0 !important;
            text-decoration: underline;
        }

        /* Sidebar */
        @media (min-width: 768px) {
            .main-sidebar {
                background-color: var(--sidebar-bg) !important;
                position: fixed !important;
                top: 50px !important; /* Altura del navbar */
                bottom: 50px !important; /* Altura del footer */
                height: auto !important;
                overflow-y: auto;
                scrollbar-width: thin;
                scrollbar-color: var(--bg-nav) var(--sidebar-bg);
            }

            .main-sidebar::-webkit-scrollbar {
                width: 8px;
            }

            .main-sidebar::-webkit-scrollbar-track {
                background: var(--sidebar-bg);
            }

            .main-sidebar::-webkit-scrollbar-thumb {
                background-color: var(--bg-nav);
                border-radius: 4px;
            }

            .sidebar-menu {
                height: 100%;
            }
        }

        /* Estilos para pantallas pequeñas */
        @media (max-width: 767px) {
            .main-sidebar {
                background-color: var(--sidebar-bg) !important;
                position: relative;
                height: auto;
                overflow-y: visible;
            }
        }

        .sidebar-menu > li > a {
            color: var(--text-main) !important;
        }

        .sidebar h3,
        .sidebar h4,
        .sidebar .title {
            color: var(--text-main) !important;
            font-weight: bold;
        }

        /* Cajas de productos */
        .box {
            background-color: var(--product-box-bg) !important;
            color: var(--text-main) !important;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        /* Ubicación y otros títulos */
        .location-title,
        .section-title,
        h2, h3, h4,
        .impact-item p,
        .impact-item h3 {
            color: var(--text-main) !important;
        }

        /* Ajustes para dropdowns y menús */
        .navbar .dropdown-menu,
        .user-menu .dropdown-menu {
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
        }

        .navbar .dropdown-menu > li > a,
        .user-menu .dropdown-menu > li > a {
            color: var(--text-main) !important;
        }

        .navbar .dropdown-menu > li > a:hover,
        .user-menu .dropdown-menu > li > a:hover {
            background-color: var(--bg-nav) !important;
            color: var(--nav-text) !important;
        }

        .login-box-body,
        .register-box-body {
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
        }

        .form-control {
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
            border-color: var(--box-shadow) !important;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .modal-content {
            background-color: var(--bg-main);
            color: var(--text-main);
        }

        .close {
            color: var(--text-main);
        }

        .sidebar {
            background-color: var(--bg-nav) !important;
        }

        .skin-green .main-header .navbar,
        .skin-green .main-header .logo {
            background-color: var(--bg-nav) !important;
        }
        /* Botón flotante de tema */
        .theme-switch {
            position: fixed !important;
            bottom: 85px !important;  /* Ajustado más cerca del footer */
            left: 20px !important;  /* Cambiado a la izquierda */
            z-index: 1000 !important;
            width: 50px !important;
            height: 50px !important;
            border-radius: 50% !important;
            background-color: var(--bg-nav) !important;
            color: #fff !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1.2em !important;
            transition: transform 0.3s ease !important;
        }

        .theme-switch:hover {
            transform: scale(1.1) !important;
        }

        /* Ajuste del espacio para el footer fijo */
        .content-wrapper {
            padding-bottom: 60px !important;
        }

        /* Ajustes adicionales para modo oscuro */
        [data-theme="dark"] .box {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        [data-theme="dark"] .sidebar-menu > li:hover > a,
        [data-theme="dark"] .sidebar-menu > li.active > a {
            background-color: var(--bg-nav) !important;
            color: #fff !important;
        }
    </style>
    
    <script>
        // Función para manejar las alertas
        function handleAlerts() {
            const alerts = document.querySelectorAll('.alert:not(.no-auto-close)');
            alerts.forEach(alert => {
                if (!alert.dataset.initialized) {
                    // Marcar la alerta como inicializada
                    alert.dataset.initialized = 'true';
                    
                    // Eliminar la alerta después de 20 segundos
                    setTimeout(() => {
                        if (alert && alert.parentNode) {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-20px)';
                            setTimeout(() => {
                                alert.parentNode.removeChild(alert);
                            }, 300);
                        }
                    }, 20000);
                }
            });
        }

        // Observador para detectar nuevas alertas
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length) {
                    handleAlerts();
                }
            });
        });

        // Configurar el observador
        document.addEventListener('DOMContentLoaded', function() {
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            handleAlerts();
        });

        // Verificar y establecer el tema al cargar
        document.addEventListener('DOMContentLoaded', function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
            updateThemeIcon(theme);
            
            // Crear y agregar el botón de tema si no existe
            if (!document.querySelector('.theme-switch')) {
                const themeBtn = document.createElement('button');
                themeBtn.className = 'theme-switch';
                themeBtn.setAttribute('aria-label', 'Cambiar tema');
                themeBtn.onclick = toggleTheme;
                
                const icon = document.createElement('i');
                icon.id = 'theme-icon';
                icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                themeBtn.appendChild(icon);
                
                document.body.appendChild(themeBtn);
            }
        });

        // Función para cambiar el tema
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        // Actualizar el icono del botón según el tema
        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            if (icon) {
                icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        }
    </script>
    
    <style type="text/css">
    /* Pequeños dispositivos (tablets, 768px y arriba) */
    @media (min-width: 768px){ 
      #navbar-search-input{ 
        width: 60px; 
      }
      #navbar-search-input:focus{ 
        width: 100px; 
      }
    }

    /* Dispositivos medianos (desktops, 992px y arriba) */
    @media (min-width: 992px){ 
      #navbar-search-input{ 
        width: 150px; 
      }
      #navbar-search-input:focus{ 
        width: 250px; 
      } 
    }

    .word-wrap{
      overflow-wrap: break-word;
    }
    .prod-body{
      height:300px;
    }

    .box:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }
    .register-box{
      margin-top:20px;
    }

    #trending{
      list-style: none;
      padding:10px 5px 10px 15px;
    }
    #trending li {
      padding-left: 1.3em;
    }
    #trending li:before {
      content: "\f046";
      font-family: FontAwesome;
      display: inline-block;
      margin-left: -1.3em; 
      width: 1.3em;
    }

    /*Aumentar*/
    .magnify > .magnify-lens {
      width: 100px;
      height: 100px;
    }

    /* Estilos para prevenir Layout Shift */
    .carousel-container {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        aspect-ratio: 16/9;
        overflow: hidden;
        border-radius: 20px;
    }

    .carousel {
        position: relative;
        height: 100%;
    }

    .carousel-inner {
        height: 100%;
    }

    .carousel-inner > .item {
        height: 100%;
    }

    .carousel-inner > .item > img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 20px;
    }

    .carousel-indicators {
        bottom: 20px;
        margin-bottom: 0;
    }

    .carousel-indicators li {
        width: 12px;
        height: 12px;
        margin: 0 5px;
        border-radius: 50%;
        border: 2px solid #fff;
        background-color: transparent;
    }

    .carousel-indicators .active {
        width: 14px;
        height: 14px;
        background-color: #fff;
    }

    /* Estilos para títulos y texto centrado */
    .producto-titulo {
        font-size: 2rem;
        margin: 2rem 0;
        padding: 0 1rem;
        text-align: center;
        line-height: 1.2;
        height: auto;
    }

    .text-center {
        text-align: center;
    }

    .mb-4 {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 2rem;
        margin: 2rem 0;
        padding: 0 1rem;
        text-align: center;
        line-height: 1.2;
        min-height: 2.4rem;
    }

    .section-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .responsive-image-container {
        width: 100%;
        max-width: 600px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .img-responsive {
        width: 100%;
        height: auto;
        border-radius: 20px;
        display: block;
        margin: 0 auto;
    }

    h2, h3, h4 {
        margin: 1rem 0;
        line-height: 1.4;
        min-height: 1.4em;
    }

    .impact-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        padding: 2rem 1rem;
    }

    .impact-item {
        flex: 1;
        min-width: 280px;
        max-width: 400px;
        text-align: center;
        margin-bottom: 2rem;
    }

    .impact-image {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border-radius: 20px;
        margin: 1rem auto;
    }

    </style>

</head>
</html>

