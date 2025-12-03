<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?php echo (!empty($admin['photo'])) ? '../images/' . $admin['photo'] : '../images/profile.jpg'; ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?php echo $admin['firstname'] . ' ' . $admin['lastname']; ?></p>
        <a><i class="fa fa-circle text-success"></i> Activo</a>
      </div>
    </div>

    <!-- Contenedor con scroll para el menú -->
    <div class="sidebar-menu-container">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <!-- MENU PRINCIPAL -->
        <li class="header">PRINCIPAL</li>
        <li><a href="home.php"><i class="fa fa-dashboard"></i> <span>Escritorio</span></a></li>

        <!-- INFORMES -->
        <li class="header">INFORMES</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-money"></i>
            <span>Consultar ventas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="sales.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
            <li><a href="sales_daily.php"><i class="fa fa-circle-o"></i> Ventas Diarias</a></li>
            <li><a href="sales_monthly.php"><i class="fa fa-circle-o"></i> Ventas Mensuales</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i>
            <span>Consultar Ingresos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="income.php"><i class="fa fa-circle-o"></i> Ingresos por fechas</a></li>
            <li><a href="income_categories.php"><i class="fa fa-circle-o"></i> Ingresos por categoría</a></li>
          </ul>
        </li>

        <!-- GESTIONAR -->
        <li class="header">GESTIONAR</li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i>
            <span>Almacen</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="products.php"><i class="fa fa-circle-o"></i> Productos</a></li>
            <li><a href="category.php"><i class="fa fa-circle-o"></i> Categoría</a></li>
            <li><a href="stock.php"><i class="fa fa-circle-o"></i> Control de Stock</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-shopping-cart"></i>
            <span>Ingresos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="inputs.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
            <li><a href="provider.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
            <li><a href="purchase_orders.php"><i class="fa fa-circle-o"></i> Órdenes de Compra</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-shopping-bag"></i>
            <span>Ventas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="sales.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
            <li><a href="clients.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
            <li><a href="invoices.php"><i class="fa fa-circle-o"></i> Facturas</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i>
            <span>Inventario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="inputs.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
            <li><a href="outputs.php"><i class="fa fa-circle-o"></i> Salidas</a></li>
            <li><a href="inventory_report.php"><i class="fa fa-circle-o"></i> Reporte Inventario</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i>
            <span>Acceso</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="users.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
            <li><a href="roles.php"><i class="fa fa-circle-o"></i> Roles</a></li>
            <li><a href="permissions.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
          </ul>
        </li>

        <!-- AJUSTES M.A.R.Í.A -->
        <li class="header">INTELIGENCIA ARTIFICIAL</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>M.A.R.Í.A</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="maria_config.php"><i class="fa fa-cog"></i> Configuración</a></li>
            <li><a href="maria_chat.php"><i class="fa fa-comment"></i> Chat con María</a></li>
            <li><a href="maria_history.php"><i class="fa fa-history"></i> Historial</a></li>
            <li><a href="maria_advanced.php"><i class="fa fa-sliders"></i> Ajustes avanzados</a></li>
            <li><a href="maria_reports.php"><i class="fa fa-bar-chart"></i> Reportes IA</a></li>
            <li><a href="maria_automations.php"><i class="fa fa-magic"></i> Automatizaciones</a></li>
          </ul>
        </li>

        <!-- SOLICITAR -->
        <li class="header">SOLICITAR</li>
        <li>
          <a href="https://www.facebook.com/conceiba.es" target="_blank">
            <i class="fa fa-question-circle"></i> <span>Ayuda</span>
          </a>
        </li>
        <li>
          <a href="support.php">
            <i class="fa fa-life-ring"></i> <span>Soporte Técnico</span>
          </a>
        </li>
        <li>
          <a href="training.php">
            <i class="fa fa-graduation-cap"></i> <span>Capacitación</span>
          </a>
        </li>
      </ul>
    </div>
  </section>
  <!-- /.sidebar -->
</aside>

<style>
  /* ESTILOS ORIGINALES MANTENIDOS */
  .main-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 230px;
    background-color: #222d32;
    z-index: 810;
    overflow: hidden;
  }

  .sidebar {
    height: 100%;
  }

  .user-panel {
    padding: 10px;
    border-bottom: 1px solid #4b646f;
  }

  .pull-left.image {
    float: left;
    width: 45px;
  }

  .pull-left.image img {
    width: 100%;
    height: 45px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.3);
  }

  .pull-left.info {
    float: left;
    padding: 5px 5px 5px 15px;
  }

  .pull-left.info p {
    margin: 0;
    font-weight: 600;
    font-size: 14px;
    color: #fff;
  }

  .pull-left.info a {
    color: #b8c7ce;
    font-size: 12px;
    text-decoration: none;
  }

  .fa-circle.text-success {
    color: #00a65a !important;
  }

  .sidebar-menu {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .sidebar-menu>li.header {
    padding: 10px 25px 10px 15px;
    font-size: 12px;
    color: #4b646f;
    background: #1a2226;
  }

  .sidebar-menu>li>a {
    padding: 12px 5px 12px 15px;
    display: block;
    color: #b8c7ce;
    text-decoration: none;
  }

  .sidebar-menu>li>a>.fa {
    width: 20px;
  }

  .treeview-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: #2c3b41;
    display: none;
  }

  .treeview.menu-open .treeview-menu {
    display: block;
  }

  .treeview-menu>li>a {
    padding: 5px 5px 5px 35px;
    display: block;
    font-size: 14px;
    color: #8aa4af;
    text-decoration: none;
  }

  .treeview-menu>li>a>.fa {
    width: 15px;
  }

  /* CONTENEDOR CON SCROLL - SOLUCIÓN MEJORADA */
  .sidebar-menu-container {
    height: calc(100vh - 100px) !important;
    max-height: calc(100vh - 100px) !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
  }

  /* SCROLLBAR VERDE MÁS VISIBLE */
  .sidebar-menu-container::-webkit-scrollbar {
    width: 10px;
  }

  .sidebar-menu-container::-webkit-scrollbar-track {
    background: #1a2226;
    border-radius: 0;
  }

  .sidebar-menu-container::-webkit-scrollbar-thumb {
    background: #00a65a;
    border-radius: 5px;
    border: 2px solid #1a2226;
  }

  .sidebar-menu-container::-webkit-scrollbar-thumb:hover {
    background: #008d4c;
  }

  /* Para Firefox */
  .sidebar-menu-container {
    scrollbar-width: thin;
    scrollbar-color: #00a65a #1a2226;
  }

  /* RESPONSIVE BÁSICO */
  @media (max-width: 768px) {
    .main-sidebar {
      width: 100%;
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }

    .main-sidebar.mobile-open {
      transform: translateX(0);
    }
  }

  .mobile-toggle {
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 800;
    background: #00a65a;
    color: white;
    border: none;
    padding: 10px 12px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    display: none;
  }

  @media (max-width: 768px) {
    .mobile-toggle {
      display: block;
    }
  }
</style>

<script>
  // JavaScript para funcionalidad
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.main-sidebar');
    const treeviewLinks = document.querySelectorAll('.treeview > a');

    // Expandir/contraer menús
    treeviewLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const parent = this.parentElement;
        parent.classList.toggle('menu-open');
      });
    });

    // Botón móvil
    if (window.innerWidth <= 768) {
      const toggleBtn = document.createElement('button');
      toggleBtn.className = 'mobile-toggle';
      toggleBtn.innerHTML = '☰';
      document.body.appendChild(toggleBtn);

      toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('mobile-open');
      });
    }

    // Forzar que el scroll esté activo
    setTimeout(() => {
      const container = document.querySelector('.sidebar-menu-container');
      container.style.overflowY = 'auto';
    }, 100);
  });
</script>