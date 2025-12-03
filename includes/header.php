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

        /* ========================================== */
/* ESTILOS DE M.A.R.I.A - RESPONSIVE MEJORADO */
/* ========================================== */

/* Botón flotante de M.A.R.I.A */
.maria-button {
    position: fixed !important;
    bottom: 145px !important;
    left: 20px !important;
    z-index: 1000 !important;
    width: 60px !important;
    height: 60px !important;
    border-radius: 50% !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: 3px solid #fff !important;
    cursor: pointer !important;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.6) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s ease !important;
    overflow: hidden !important;
}

.maria-button:hover {
    transform: scale(1.1) !important;
    box-shadow: 0 6px 30px rgba(102, 126, 234, 0.8) !important;
}

.maria-button img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.maria-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff3b30;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    border: 2px solid white;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* ========================================== */
/* MODAL DEL CHAT - RESPONSIVE */
/* ========================================== */

.maria-modal {
    display: none;
    position: fixed;
    bottom: 100px;
    left: 30px;
    width: 300px; /* ancho reducido para consistencia */
    height: 440px; /* altura base moderada (se ajusta en media queries) */
    max-height: calc(100vh - 120px); /* no sobrepasar la ventana */
    background: var(--bg-main);
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    z-index: 999;
    flex-direction: column;
    overflow: hidden;
    transition: all 0.3s ease;
    box-sizing: border-box;
}
.maria-mute {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    color: #495057;
    padding: 8px;
    margin-right: 8px;
    cursor: pointer;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.maria-mute:hover {
    background: #e9ecef;
    color: #212529;
    transform: scale(1.1);
}

.maria-mute i {
    font-size: 16px;
}
.maria-modal.show {
    display: flex;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ========================================== */
/* RESPONSIVE: MÓVILES (hasta 480px) */
/* ========================================== */
@media (max-width: 480px) {
    .maria-button {
        bottom: 20px !important;
        left: 10px !important;
        width: 50px !important;
        height: 50px !important;
    }
    
    .maria-modal {
        left: 10px !important;
        right: 10px !important;
        bottom: 80px !important;
        width: calc(100% - 20px) !important;
        height: calc(100vh - 100px) !important;
        max-height: 500px !important;
    }
    
    .theme-switch {
        bottom: 80px !important;
        left: 10px !important;
        width: 45px !important;
        height: 45px !important;
    }
    
    .maria-header {
        padding: 12px 15px !important;
    }
    
    .maria-avatar {
        width: 38px !important;
        height: 38px !important;
    }
    
    .maria-name {
        font-size: 14px !important;
    }
    
    .maria-online {
        font-size: 11px !important;
    }
    
    .maria-messages {
        padding: 15px !important;
    }
    
    .maria-input-area {
        padding: 10px 15px !important;
    }
    
    .maria-mic-button,
    .maria-send {
        width: 35px !important;
        height: 35px !important;
        font-size: 16px !important;
    }
}

/* ========================================== */
/* RESPONSIVE: MÓVILES HORIZONTALES (481px - 767px) */
/* ========================================== */
@media (min-width: 481px) and (max-width: 767px) {
    .maria-button {
        bottom: 20px !important;
        left: 15px !important;
        width: 55px !important;
        height: 55px !important;
    }
    
    .maria-modal {
        left: 15px !important;
        right: 15px !important;
        bottom: 85px !important;
        width: calc(100% - 30px) !important;
        height: 450px !important;
        max-height: calc(100vh - 110px) !important;
    }
    
    .theme-switch {
        bottom: 85px !important;
        left: 15px !important;
    }
}

/* ========================================== */
/* RESPONSIVE: TABLETS VERTICAL (768px - 991px) */
/* ========================================== */
@media (min-width: 768px) and (max-width: 991px) {
    .maria-modal {
        left: 20px !important;
        bottom: 215px !important;
        width: 320px !important;
        height: 430px !important; /* altura ajustada a 430px */
        max-height: calc(100vh - 160px) !important;
    }
    .maria-messages { max-height: calc(430px - 120px) !important; }
}

/* ========================================== */
/* RESPONSIVE: TABLETS HORIZONTAL Y LAPTOPS (992px - 1199px) */
/* ========================================== */
@media (min-width: 992px) and (max-width: 1199px) {
    .maria-modal {
        left: 20px !important;
        bottom: 215px !important;
        width: 340px !important;
        height: 430px !important; /* altura ajustada a 430px */
        max-height: calc(100vh - 160px) !important;
    }
    .maria-messages { max-height: calc(430px - 120px) !important; }
}

/* ========================================== */
/* RESPONSIVE: DESKTOP (1200px - 1919px) */
/* ========================================== */
@media (min-width: 1200px) and (max-width: 1919px) {
    .maria-modal {
        left: 20px !important;
        bottom: 215px !important;
        width: 360px !important;
        height: 430px !important; /* altura ajustada a 430px */
        max-height: calc(100vh - 160px) !important;
    }
    .maria-messages { max-height: calc(430px - 120px) !important; }
}

/* ========================================== */
/* RESPONSIVE: PANTALLAS GRANDES Y TVs (1920px+) */
/* ========================================== */
@media (min-width: 1920px) {
    .maria-button {
        width: 70px !important;
        height: 70px !important;
        bottom: 160px !important;
        left: 30px !important;
    }
    
    .maria-modal {
        left: 30px !important;
        bottom: 240px !important;
        width: 380px !important;
        height: 430px !important; /* mantener 430px en pantallas grandes */
        max-height: calc(100vh - 160px) !important;
    }
    
    .theme-switch {
        width: 55px !important;
        height: 55px !important;
        bottom: 90px !important;
        left: 30px !important;
    }
}

/* ========================================== */
/* ORIENTACIÓN: LANDSCAPE (HORIZONTAL) */
/* ========================================== */
@media (max-height: 500px) and (orientation: landscape) {
    .maria-modal {
        left: 10px !important;
        right: auto !important;
        bottom: 10px !important;
        top: 10px !important;
        width: 350px !important;
        height: calc(100vh - 20px) !important;
        max-height: none !important;
    }
    
    .maria-button {
        bottom: 10px !important;
        left: 370px !important;
    }
    
    .theme-switch {
        bottom: 10px !important;
        left: 430px !important;
    }
}

/* ========================================== */
/* ORIENTACIÓN: MÓVILES LANDSCAPE */
/* ========================================== */
@media (max-width: 767px) and (orientation: landscape) {
    .maria-modal {
        left: 10px !important;
        bottom: 10px !important;
        top: 10px !important;
        width: 320px !important;
        height: calc(160vh - 20px) !important;
    }
    
    .maria-button {
        bottom: 50% !important;
        left: 340px !important;
        transform: translateY(50%) !important;
    }
    
    .theme-switch {
        bottom: 50% !important;
        left: 340px !important;
        transform: translateY(130%) !important;
    }
    
    .maria-messages {
        padding: 10px !important;
    }
    
    .welcome-message {
        padding: 10px !important;
    }
    
    .welcome-message img {
        width: 60px !important;
        height: 60px !important;
        margin-bottom: 8px !important;
    }
    
    .welcome-message h3 {
        font-size: 16px !important;
    }
    
    .welcome-message p {
        font-size: 12px !important;
    }
}

/* ========================================== */
/* HEADER DEL CHAT */
/* ========================================== */
.maria-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 20px 20px 0 0;
    flex-shrink: 0;
}

.maria-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.maria-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 2px solid white;
    object-fit: cover;
    position: relative;
}

.maria-status {
    display: flex;
    flex-direction: column;
}

.maria-name {
    font-weight: bold;
    font-size: 16px;
    margin: 0;
    color: white !important;
}

.maria-online {
    font-size: 12px;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #4cd964;
    border-radius: 50%;
    animation: blink 2s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.maria-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    font-size: 20px;
}

.maria-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* ========================================== */
/* ÁREA DE MENSAJES */
/* ========================================== */
.maria-messages {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 20px;
    background: var(--bg-main);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

[data-theme="dark"] .maria-messages {
    background: #1a1a1a;
}

.maria-message {
    display: flex;
    gap: 10px;
    animation: messageAppear 0.3s ease;
}

@keyframes messageAppear {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    flex-shrink: 0;
}

.message-content {
    max-width: 75%;
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    margin-bottom: 4px;
    word-wrap: break-word;
    word-break: break-word;
    line-height: 1.4;
}

.maria-message.assistant .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-left-radius: 4px;
}

.maria-message.user {
    flex-direction: row-reverse;
}

.maria-message.user .message-content {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.maria-message.user .message-bubble {
    background: #007aff;
    color: white;
    border-bottom-right-radius: 4px;
}

.message-time {
    font-size: 11px;
    opacity: 0.6;
    padding: 0 8px;
    color: var(--text-main);
}

/* ========================================== */
/* ÁREA DE INPUT */
/* ========================================== */
.maria-input-area {
    padding: 15px 20px;
    background: var(--bg-main);
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    display: flex;
    gap: 10px;
    align-items: center;
    border-radius: 0 0 20px 20px;
    flex-shrink: 0;
}

[data-theme="dark"] .maria-input-area {
    background: #1a1a1a;
    border-top-color: rgba(255, 255, 255, 0.1);
}

.maria-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #e5e5ea;
    border-radius: 20px;
    background: var(--bg-main);
    color: var(--text-main);
    outline: none;
    font-size: 14px;
    transition: all 0.3s;
}

[data-theme="dark"] .maria-input {
    background: #2d2d2d;
    border-color: #3d3d3d;
}

.maria-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.maria-send {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    font-size: 18px;
    flex-shrink: 0;
}

.maria-send:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.maria-send:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ========================================== */
/* MENSAJE DE BIENVENIDA */
/* ========================================== */
.welcome-message {
    text-align: center;
    padding: 15px;
    color: var(--text-main);
}

.welcome-message img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin-bottom: 12px;
    border: 3px solid #667eea;
}

.welcome-message h3 {
    margin: 0 0 8px 0;
    color: #667eea !important;
    font-size: 18px;
}

.welcome-message p {
    margin: 4px 0;
    font-size: 13px;
    opacity: 0.8;
}

.quick-questions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 12px;
}

.quick-question {
    padding: 10px 15px;
    background: rgba(102, 126, 234, 0.1);
    border: 1px solid rgba(102, 126, 234, 0.3);
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 12px;
    text-align: left;
    color: var(--text-main);
}

.quick-question:hover {
    background: rgba(102, 126, 234, 0.2);
    transform: translateX(5px);
}

/* ========================================== */
/* SISTEMA DE VOZ */
/* ========================================== */
.maria-mic-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #28a745;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    font-size: 18px;
    margin-right: 10px;
    flex-shrink: 0;
}

.maria-mic-button:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
}

.maria-mic-button.listening {
    background: #dc3545;
    animation: pulse-mic 1.5s infinite;
}

@keyframes pulse-mic {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    50% {
        box-shadow: 0 0 0 15px rgba(220, 53, 69, 0);
    }
}

.listening-indicator {
    padding: 15px;
    text-align: center;
}

.listening-animation {
    display: flex;
    justify-content: center;
    gap: 5px;
    margin-bottom: 10px;
}

.listening-animation span {
    width: 4px;
    height: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
    animation: sound-wave 1s ease-in-out infinite;
}

.listening-animation span:nth-child(1) { animation-delay: 0s; }
.listening-animation span:nth-child(2) { animation-delay: 0.1s; }
.listening-animation span:nth-child(3) { animation-delay: 0.2s; }
.listening-animation span:nth-child(4) { animation-delay: 0.3s; }
.listening-animation span:nth-child(5) { animation-delay: 0.4s; }

@keyframes sound-wave {
    0%, 100% { height: 20px; }
    50% { height: 40px; }
}

.listening-indicator p {
    color: var(--text-main);
    font-weight: bold;
    margin: 0;
    font-size: 13px;
}

.maria-avatar.speaking {
    animation: avatar-speak 0.5s ease-in-out infinite;
    border-color: #28a745;
    box-shadow: 0 0 20px rgba(40, 167, 69, 0.6);
}

@keyframes avatar-speak {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* ========================================== */
/* SCROLLBAR PERSONALIZADO */
/* ========================================== */
.maria-messages::-webkit-scrollbar {
    width: 6px;
}

.maria-messages::-webkit-scrollbar-track {
    background: transparent;
}

.maria-messages::-webkit-scrollbar-thumb {
    background: rgba(102, 126, 234, 0.3);
    border-radius: 3px;
}

.maria-messages::-webkit-scrollbar-thumb:hover {
    background: rgba(102, 126, 234, 0.5);
}

/* ========================================== */
/* AJUSTES PARA ASEGURAR VISIBILIDAD */
/* ========================================== */
.maria-button,
.maria-modal {
    pointer-events: auto !important;
}

/* Prevenir overflow en dispositivos pequeños */
body.maria-open {
    overflow: hidden;
}

@media (max-width: 767px) {
    body.maria-open .maria-modal {
        position: fixed;
    }
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

