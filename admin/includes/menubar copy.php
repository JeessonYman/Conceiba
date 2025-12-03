<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?php echo (!empty($admin['photo'])) ? '../images/'.$admin['photo'] : '../images/profile.jpg'; ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?php echo $admin['firstname'].' '.$admin['lastname']; ?></p>
        <a><i class="fa fa-circle text-success"></i> Activo</a>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less --
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
        </ul>
      </li>
      
      <!-- AJUSTES M.A.R.Í.A 
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
          <li><a href="#"><i class="fa fa-cog"></i> Configuración</a></li>
          <li><a href="#"><i class="fa fa-comment"></i> Chat con María</a></li>
          <li><a href="#"><i class="fa fa-history"></i> Historial</a></li>
          <li><a href="#"><i class="fa fa-sliders"></i> Ajustes avanzados</a></li>
        </ul>
      </li> -->

      <!-- SOLICITAR -->
      <li class="header">SOLICITAR</li>
      <li>
        <a href="https://www.facebook.com/conceiba.es" target="_blank">
          <i class="fa fa-question-circle"></i> <span>Ayuda</span>
        </a>  
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>