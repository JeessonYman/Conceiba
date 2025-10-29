<header class="main-header">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <a href="index.php" class="navbar-brand"><b><img src="./images/logo.png" alt="" height="30px" width="30px"></b>onceiba</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars" style="border-radius:20px;"></i>
        </button>
      </div>

      <!-- Recopile los enlaces de navegación, formularios y otro contenido para alternar -->
      <div class="collapse navbar-collapse pull-left" id="navbar-collapse" style="border-radius:20px;">
        <ul class="nav navbar-nav" style="border-radius:20px;">
          <li><a href="index.php" style="border-radius:20px;">INICIO</a></li>
          <li><a href="contacto.php" style="border-radius:20px;">CONTACTANOS</a></li>
          <li><a href="hilado.php" style="border-radius:20px;">HILADO</a></li>
          <li class="dropdown" style='border-radius:20px;'>
            <a href="#" class="dropdown-toggle" style="border-radius:20px;" data-toggle="dropdown">CATEGORIAS<span class="caret" style="border-radius:20px;"></span></a>
            <ul class="dropdown-menu"  style="border-radius:20px;" role="menu">
              <?php
             
                $conn = $pdo->open();
                try{
                  $stmt = $conn->prepare("SELECT * FROM category");
                  $stmt->execute();
                  foreach($stmt as $row){
                    echo "
                      <li><a style='border-radius:10px;' href='category.php?category=".$row['cat_slug']."'>".$row['name']."</a></li>
                    ";                  
                  }
                }
                catch(PDOException $e){
                  echo "Hay algún problema en la conexión.: " . $e->getMessage();
                }

                $pdo->close();

              ?>
            </ul>
          </li>
        </ul>
        <form method="POST"  style="border-radius:20px;" class="navbar-form navbar-left" action="search.php">
          <div class="input-group" style="border-radius:20px;">
              <input type="text" style="border-radius:20px;" class="form-control" id="navbar-search-input" name="keyword" placeholder="Buscar producto" required>
              <span class="input-group-btn"   id="searchBtn" style="display:none;">
                  <button type="submit" style="border-radius:20px;" class="btn btn-default btn-flat"><i class="fa fa-search"></i> </button>
              </span>
          </div>
        </form>
      </div>
      <!-- /.navbar-colapso-->
      <!--Menú derecho de la barra de navegación-->
      <div class="navbar-custom-menu" style="border-radius:20px;">
        <ul class="nav navbar-nav" style="border-radius:20px;">
          <li class="dropdown messages-menu" style="border-radius:20px;">
            <!-- Botón de alternar menú -->
            <a href="#" class="dropdown-toggle" style="border-radius:20px;" data-toggle="dropdown">
              <i style="border-radius:20px;" class="fa fa-shopping-cart"></i>
              <span class="label label-success cart_count" style="border-radius:20px;"></span>
            </a>
            <ul class="dropdown-menu" style="border-radius:20px;">
              <li class="header" style="border-radius:20px;">Tienes <span class="cart_count"></span> articulo(s) en el carrito</li>
              <li>
                <ul class="menu"  style="border-radius:20px;" id="cart_menu">
                </ul>
              </li>
              <li class="footer" style="border-radius:20px;" ><a href="cart_view.php" style="border-radius:20px;">ir al carrito</a></li>
            </ul>
          </li>
          <?php
            if(isset($_SESSION['user'])){
              $image = (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg';
              echo '
                <li class="dropdown user user-menu" style="border-radius:20px;">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="border-radius:20px;">
                    <img src="'.$image.'" class="user-image"  style="border-radius:20px;" alt="User Image">
                    <span class="hidden-xs" style="border-radius:20px;">'.$user['firstname'].' '.$user['lastname'].'</span>
                  </a>
                  <ul class="dropdown-menu" >
                    <!-- User image -->
                    <li class="user-header" >
                      <img src="'.$image.'"  style="border-radius:20px;"  style="border-radius:20px;" class="img-circle" alt="User Image">

                      <p>
                        '.$user['firstname'].' '.$user['lastname'].'
                        <small>Member since '.date('M. Y', strtotime($user['created_on'])).'</small>
                      </p>
                    </li>
                    <li class="user-footer" >
                      <div class="pull-left" style="border-radius:20px;">
                        <a href="profile.php" class="btn btn-default btn-flat" style="border-radius:20px;">Perfil</a>
                      </div>
                      <div class="pull-right" style="border-radius:20px;">
                        <a href="logout.php" class="btn btn-default btn-flat" style="border-radius:20px;">Salir</a>
                      </div>
                    </li>
                  </ul>
                </li>
              ';
            }
            else{
              echo "
                <li><a href='login.php' style='border-radius:20px;' >INICIAR SESIÓN</a></li>
                <li class='hide-on-landscape'><a href='signup.php' style='border-radius:20px;' >REGISTRATE</a></li>
              ";
            }
          ?>
        </ul>
      </div>
    </div>
  </nav>
</header>
<style>
.skin-green .main-header .navbar {
    background-color: #00a65a;
}

.skin-green .main-header .navbar .nav > li > a {
    color: #fff;
}

.skin-green .main-header .navbar .nav > li > a:hover,
.skin-green .main-header .navbar .nav > li > a:active,
.skin-green .main-header .navbar .nav > li > a:focus,
.skin-green .main-header .navbar .nav .open > a,
.skin-green .main-header .navbar .nav .open > a:hover,
.skin-green .main-header .navbar .nav .open > a:focus {
    background: rgba(0, 0, 0, 0.1);
    color: #f6f6f6;
}

.skin-green .main-header .navbar .navbar-custom-menu > .nav {
    margin-right: 10px;
}

.cart_count {
    position: absolute;
    top: 8px;
    right: 8px;
    min-width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    background: #fff;
    color: #00a65a;
    border-radius: 50%;
}

.navbar-header {
    min-height: 50px;
    display: flex;
    align-items: center;
}

.navbar-brand {
    height: 50px;
    display: flex;
    align-items: center;
}

.navbar-brand img {
    width: 30px;
    height: 30px;
    object-fit: contain;
}

@media (max-width: 915px) and (max-height: 412px) {
    .hide-on-landscape {
        display: none !important;
    }
}

@media((max-width: 912px) and (max-height: 1368px)){
    .hide-on-landscape {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .navbar-custom-menu {
        position: absolute;
        right: 0;
        top: 0;
    }
    
    .navbar-custom-menu .dropdown-menu {
        width: 100vw;
        position: fixed;
        top: 50px;
        left: 0;
    }
}
</style>