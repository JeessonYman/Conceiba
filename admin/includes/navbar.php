<header class="main-header">
  <!-- Logo -->
  <a href="#" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">
      <img src="../images/logo.png" alt="Logo" class="img-responsive" style="max-height: 40px; max-width: 40px;">
    </span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg">
      <img src="../images/logo.png" alt="Logo" class="img-responsive" style="max-height: 40px; display: inline-block; vertical-align: middle;">
      <b style="vertical-align: middle; margin-left: 8px;">onceiba</b>
    </span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" aria-label="Toggle navigation">
      <span class="sr-only">Navegación de palanca</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Botón del menú de usuario -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo (!empty($admin['photo'])) ? '../images/' . $admin['photo'] : '../images/profile.jpg'; ?>"
              class="user-image"
              alt="<?php echo $admin['firstname'] . ' ' . $admin['lastname']; ?>">
            <span class="user-info hidden-xs">
              <?php echo $admin['firstname'] . ' ' . $admin['lastname']; ?>
            </span>
          </a>

          <!-- Dropdown Menu -->
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img src="<?php echo (!empty($admin['photo'])) ? '../images/' . $admin['photo'] : '../images/profile.jpg'; ?>"
                class="img-circle"
                alt="<?php echo $admin['firstname'] . ' ' . $admin['lastname']; ?>">
              <p>
                <?php echo $admin['firstname'] . ' ' . $admin['lastname']; ?>
                <small>Miembro desde <?php echo date('M. Y', strtotime($admin['created_on'])); ?></small>
              </p>
            </li>

            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="#profile"
                  data-toggle="modal"
                  class="btn btn-default btn-flat"
                  id="admin_profile"
                  style="border-radius:10px;">
                  Perfil
                </a>
              </div>
              <div class="pull-right">
                <a href="../logout.php"
                  class="btn btn-default btn-flat"
                  style="border-radius:10px;">
                  Salir
                </a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>

<?php include 'includes/profile_modal.php'; ?>

<style>
  /* =========================================================
   FIX RESPONSIVE NAVBAR ADMINLTE + BOOTSTRAP 3.3.7
   ========================================================= */
  .main-header {
    position: fixed;
    width: 100%;
    z-index: 1030;
    top: 0;
    left: 0;
  }

  /* FIX: Estructura del navbar */
  .main-header .navbar {
    margin: 0;
    border: none;
    border-radius: 0;
    min-height: 50px;
    height: 50px !important;
    line-height: 50px !important;
    padding: 0;
    display: block;
    /* Cambiado de flex a block */
    position: relative;
  }

  /* FIX: Logo con ancho correcto */
  .main-header .logo {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    /* Alinear a la izquierda */
    height: 50px;
    line-height: 50px !important;
    overflow: hidden;
    padding: 0 15px;
    transition: width 0.3s ease;
    float: left;
    /* Importante: flotar a la izquierda */
    width: 230px;
    /* Ancho fijo cuando sidebar está expandido */
  }

  .main-header .logo img {
    max-height: 38px;
    width: auto;
    vertical-align: middle;
  }

  .main-header .logo-lg {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .main-header .logo-lg b {
    font-size: 17px;
    color: #fff;
    text-transform: capitalize;
  }

  /* Logo mini cuando sidebar está colapsado */
  .main-header .logo-mini {
    display: none;
  }

  /* Sidebar contraído */
  .sidebar-collapse .main-header .logo {
    width: 50px !important;
    padding: 0;
    justify-content: center;
  }

  .sidebar-collapse .main-header .logo-lg {
    display: none !important;
  }

  .sidebar-collapse .main-header .logo-mini {
    display: flex !important;
    align-items: center;
    justify-content: center;
  }

  /* FIX: Botón del sidebar (hamburguesa) */
  .main-header .sidebar-toggle {
    float: left;
    /* Importante: flotar a la izquierda */
    background-color: transparent;
    background-image: none;
    padding: 15px;
    font-family: fontAwesome;
    color: #fff;
    height: 50px;
    display: flex;
    align-items: center;
    transition: background 0.3s;
  }

  .main-header .sidebar-toggle:hover {
    background: rgba(0, 0, 0, 0.1);
  }

  .main-header .sidebar-toggle:before {
    content: "\f0c9";
    font-size: 18px;
  }

  /* Menú del usuario - flotando a la derecha */
  .navbar-custom-menu {
    float: right;
    /* Importante: flotar a la derecha */
    display: flex;
    align-items: center;
    margin-right: 230px;
    height: 50px;
  }

  .navbar-custom-menu .navbar-nav {
    display: flex;
    align-items: center;
    margin: 0;
    height: 50px;
  }

  .navbar-custom-menu .user-menu>a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    height: 50px !important;
    color: #fff;
    transition: background 0.3s;
  }

  .navbar-custom-menu .user-menu>a:hover {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
  }

  /* Imagen del usuario */
  .navbar-custom-menu .user-image {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    object-fit: cover;
  }

  /* Texto del usuario */
  .navbar-custom-menu .user-info {
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
    color: #fff;
  }

  /* Dropdown */
  .navbar-custom-menu .dropdown-menu {
    right: 0;
    left: auto;
    min-width: 260px;
    margin-top: 0;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    transform-origin: top right;
    animation: dropdownFadeIn 0.2s ease;
    z-index: 9999;
  }

  @keyframes dropdownFadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* =============================
   RESPONSIVE BREAKPOINTS
   ============================= */

  /* Tablets medianas */
  @media (max-width: 1024px) {
    .navbar-custom-menu .user-info {
      max-width: 140px;
    }
  }

  /* Tablets pequeñas */
  @media (max-width: 767px) {
    .main-header .logo {
      width: 50px !important;
      padding: 0;
    }

    .main-header .logo-lg {
      display: none !important;
    }

    .main-header .logo-mini {
      display: flex !important;
    }

    .navbar-custom-menu {
      margin-right: 5px;
    }

    /* Ocultamos el texto para ganar espacio */
    .navbar-custom-menu .user-info {
      display: none;
    }

    .navbar-custom-menu .user-image {
      width: 32px;
      height: 32px;
    }

    .navbar-custom-menu .dropdown-menu {
      min-width: 240px;
      right: 10px;
    }
  }

  /* Móviles */
  @media (max-width: 575px) {
    .navbar-custom-menu .dropdown-menu {
      position: fixed !important;
      top: 55px !important;
      right: 5px !important;
      left: 5px !important;
      width: auto !important;
      max-width: calc(100vw - 10px);
    }

    .navbar-custom-menu .user-header .img-circle {
      width: 70px;
      height: 70px;
    }

    .navbar-custom-menu .user-footer {
      flex-direction: column;
    }

    .navbar-custom-menu .user-footer .btn {
      width: 100%;
      margin: 4px 0;
    }
  }

  /* Landscape móvil */
  @media (max-height: 500px) and (orientation: landscape) {
    .navbar-custom-menu .dropdown-menu {
      max-height: 80vh;
      overflow-y: auto;
    }
  }

  /* Monitores grandes (FullHD / 4K) */
  @media (min-width: 1920px) {
    .navbar-custom-menu .user-info {
      max-width: 280px;
      font-size: 16px;
    }

    .navbar-custom-menu .user-image {
      width: 40px;
      height: 40px;
    }
  }

  /* =============================
   CONTENIDO
   ============================= */
  .content-wrapper {
    margin-top: 55px !important;
  }

  /* Clearfix para los floats */
  .main-header .navbar:after {
    content: "";
    display: table;
    clear: both;
  }
</style>