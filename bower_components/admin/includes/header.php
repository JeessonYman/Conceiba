<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php
    include_once __DIR__ . '/../../includes/site_settings.php';
    $settings = getSiteSettings($pdo);
  ?>
  <title><?php echo htmlspecialchars($settings['store_name']); ?> - Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes" name="viewport">
  <meta name="description" content="Sistema de gestión Conceiba">
  <meta name="theme-color" content="#00a65a">
  <!-- Navbar Responsive Styles -->
  <link rel="stylesheet" href="../css/navbar-responsive.css">

  <!-- PWA Meta Tags -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <!-- Bootstrap 5.3 (CDN) -->
  <link rel="shortcut icon" href="../images/<?php echo htmlspecialchars($settings['logo']); ?>" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <!-- Bootstrap Icons (reemplaza Glyphicons) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <!-- Shim v4: mantiene funcionando los nombres viejos (fa-dashboard, fa-money, etc.) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/v4-shims.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/v4-font-face.min.css">
  <!-- Select2 (build BS5) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
  <!-- AdminLTE 3.2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <!-- DataTables (build BS5) -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker@3.1.0/daterangepicker.css">
  <!-- Bootstrap time Picker (fork compatible BS5) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-timepicker@0.5.5/css/bootstrap-timepicker.min.css">
  <!-- bootstrap datepicker (agnóstico de versión de BS) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css">
  <!-- Compatibilidad BS3 -> BS5 -->
  <link rel="stylesheet" href="../css/bs3-compat.css">
  <!-- Sistema de diseño Conceiba (paleta + glassmorphism, mismo que el sitio público) -->
  <link rel="stylesheet" href="../css/theme.css">
  <!-- Colores dinámicos desde el panel de configuración -->
  <style>
      :root {
          --c-emerald-700: <?php echo htmlspecialchars($settings['color_primary']); ?>;
          --c-emerald-900: <?php echo htmlspecialchars($settings['color_primary_dark']); ?>;
          --c-gold-600: <?php echo htmlspecialchars($settings['color_accent']); ?>;
          --accent: <?php echo htmlspecialchars($settings['color_accent']); ?>;
      }
  </style>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  	<![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- Custom CSS for theme switching -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <!--<link rel="stylesheet" href="assets/css/navbar_responsive_css.css"> 
     <!-- Nueva línea -->
  <!-- <link rel="stylesheet" href="assets/css/custom-fixed.css">-->

  <!-- CSS Responsivo específico para Admin -->
  <!-- <link rel="stylesheet" href="assets/css/admin-responsive.css"> -->

  <script>
    // Verificar si hay un tema guardado; si no, usar la preferencia del sistema/navegador,
    // y si tampoco se puede detectar, oscuro por defecto.
    let savedTheme = localStorage.getItem('theme');
    if (!savedTheme) {
      savedTheme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) ? 'light' : 'dark';
    }
    document.documentElement.setAttribute('data-theme', savedTheme);
  </script>

  <style type="text/css">
    .mt20 {
      margin-top: 20px;
    }

    .bold {
      font-weight: bold;
    }

    /* Mejoras responsivas generales */
    @media (max-width: 767px) {
      .box {
        margin-bottom: 10px;
      }

      .form-inline {
        display: flex;
        flex-direction: column;
        gap: 10px;
      }

      .form-inline .input-group {
        width: 100%;
        margin-bottom: 10px;
      }

      .form-inline button {
        width: 100%;
      }

      .table-responsive {
        border: 0;
      }
    }

    /* Mejoras para tablets */
    @media (min-width: 768px) and (max-width: 1024px) {
      .form-inline {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .form-inline .input-group {
        width: auto;
      }
    }

    /* Mejoras para pantallas grandes */
    @media (min-width: 1200px) {
      .container {
        width: 95%;
        max-width: 1400px;
      }
    }

    /* Soporte para modo oscuro */
    [data-theme="dark"] .box {
      background-color: #2d2d2d;
      color: #f0f0f0;
    }

    [data-theme="dark"] .table-bordered {
      border-color: #444;
    }

    [data-theme="dark"] .table-bordered>thead>tr>th,
    [data-theme="dark"] .table-bordered>tbody>tr>td {
      border-color: #444;
    }

    [data-theme="dark"] .form-control {
      background-color: #333;
      color: #f0f0f0;
      border-color: #444;
    }

    /* Mejoras de accesibilidad */
    .btn {
      min-height: 34px;
    }

    .form-control:focus {
      box-shadow: 0 0 0 2px var(--primary-color);
    }

    /* Soporte para orientación */
    @media screen and (orientation: portrait) {
      .form-inline {
        flex-direction: column;
      }
    }

    @media screen and (orientation: landscape) {
      .form-inline {
        flex-direction: row;
      }
    }

    /* Estilos para los precios */
    .price-tag {
      padding: 10px;
      font-size: 16px;
      text-align: center;
      margin-top: 10px;
      transition: all 0.3s ease;
    }

    /* Modo claro */
    body:not(.dark-mode) .price-tag {
      background-color: #333 !important;
      color: #fff !important;
    }

    body:not(.dark-mode) .price-tag b {
      color: #fff !important;
    }

    /* Modo oscuro */
    body.dark-mode .price-tag {
      background-color: #fff !important;
      color: #333 !important;
    }

    body.dark-mode .price-tag b {
      color: #333 !important;
    }

    /* Chart style */
    #legend ul {

      /* Estilos para los precios */
      .price-display {
        padding: 12px;
        font-size: 16px;
        text-align: center;
        margin-top: 10px;
        transition: all 0.3s ease;
      }

      /* Tema claro por defecto */
      .price-display {
        background-color: #ffffff;
        color: #333333;
        border: 1px solid #dddddd;
      }

      /* Tema oscuro */
      [data-theme="dark"] .price-display {
        background-color: #333333;
        color: #ffffff;
        border: 1px solid #444444;
      }

      /* Asegurando que el texto bold se vea correctamente en ambos temas */
      .price-display b {
        font-weight: 600;
      }

      [data-theme="dark"] .price-display b {
        color: #ffffff;
      }

      list-style: none;
    }

    #legend ul li {
      display: inline;
      padding-left: 30px;
      position: relative;
      padding: 2px 8px 2px 28px;
      font-size: 14px;
      cursor: default;
      transition: background-color 200ms ease-in-out;
    }

    #legend li span {
      display: block;
      position: absolute;
      left: 0;
      top: 0;
      width: 20px;
      height: 100%;
    }

    /* Chart Loader */
    .chart-loader {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 10;
    }

    /* Touch device styles */
    .touch-device .small-box {
      cursor: pointer;
    }

    .touch-device .small-box.touch-active {
      transform: scale(0.98);
      transition: transform 0.1s;
    }

    /* Dark mode support */
    body.dark-mode {
      background-color: #1a1a1a;
      color: #f0f0f0;
    }

    body.dark-mode .box {
      background-color: #2a2a2a;
      border-color: #444;
    }

    body.dark-mode .small-box {
      color: #fff;
    }

    /* Mejoras responsivas adicionales */
    @media (max-width: 767px) {
      .content-header>h1 {
        margin: 10px 0;
      }

      .box-header .box-title {
        font-size: 16px;
        margin-top: 5px;
      }

      .box-tools {
        margin-top: 5px;
      }
    }

    /* Animación de carga */
    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .fa-spin {
      animation: spin 1s infinite linear;
    }

    /* Mejoras para impresión */
    @media print {

      .no-print,
      .main-header,
      .main-sidebar,
      .content-header,
      .box-tools {
        display: none !important;
      }

      body {
        background: white;
      }

      .content-wrapper {
        margin: 0 !important;
      }
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    /* Mejoras de accesibilidad */
    .skip-to-content {
      position: absolute;
      top: -40px;
      left: 0;
      background: #000;
      color: white;
      padding: 8px;
      text-decoration: none;
      z-index: 100;
    }

    .skip-to-content:focus {
      top: 0;
    }

    /* Loading overlay */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }

    .loading-overlay.active {
      display: flex;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid #3498db;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    /* Responsive images */
    img {
      max-width: 100%;
      height: auto;
    }

    /* Fix para Safari iOS */
    @supports (-webkit-touch-callout: none) {
      .content-wrapper {
        min-height: -webkit-fill-available;
      }
    }

    /* Mejoras para tablets en portrait */
    @media (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
      .col-md-6 {
        width: 100%;
      }

      .small-box {
        margin-bottom: 15px;
      }
    }

    /* Mejoras para landscape móvil */
    @media (max-height: 500px) and (orientation: landscape) {

      .main-header .logo,
      .main-header .navbar {
        height: 40px;
      }

      .content-wrapper {
        margin-top: 40px;
      }

      .small-box {
        padding: 10px;
      }

      .small-box h3 {
        font-size: 20px !important;
      }
    }
  </style>
</head>