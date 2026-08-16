<header class="main-header">
  <nav class="navbar navbar-expand-xl navbar-light sticky-top">
    <div class="container-fluid px-4">
      <a href="index.php" class="navbar-brand d-flex align-items-center">
        <img src="./images/<?php echo htmlspecialchars($settings['logo']); ?>" alt="" height="30" width="30" class="me-2">
        <b><?php echo htmlspecialchars($settings['store_name']); ?></b>
      </a>
      <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Recopile los enlaces de navegación, formularios y otro contenido para alternar -->
      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="index.php">INICIO</a></li>
          <li class="nav-item"><a class="nav-link" href="contacto.php">CONTACTANOS</a></li>
          <li class="nav-item"><a class="nav-link" href="hilado.php">HILADO</a></li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">CATEGORIAS</a>
            <ul class="dropdown-menu">
              <?php
                $conn = $pdo->open();
                try{
                  $stmt = $conn->prepare("SELECT * FROM category");
                  $stmt->execute();
                  foreach($stmt as $row){
                    echo "
                      <li><a class='dropdown-item' href='category.php?category=".$row['cat_slug']."'>".$row['name']."</a></li>
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
        <form method="POST" class="d-flex me-lg-3 mb-2 mb-lg-0" action="search.php">
          <div class="input-group">
              <input type="text" class="form-control" id="navbar-search-input" name="keyword" placeholder="Buscar producto" required>
              <button type="submit" id="searchBtn" style="display:none;" class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
          </div>
        </form>

        <!--Menú derecho de la barra de navegación-->
        <ul class="navbar-nav align-items-lg-center">
          <li class="nav-item d-flex align-items-center">
            <button type="button" class="btn btn-sm nav-theme-toggle" onclick="toggleThemeConceiba()" aria-label="Cambiar tema">
              <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="position-relative d-inline-block">
                <i class="fa fa-shopping-cart fs-5"></i>
                <span class="badge rounded-pill bg-success cart_count position-absolute top-0 start-100 translate-middle"></span>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end glass" style="min-width:280px;">
              <li class="dropdown-header">Tienes <span class="cart_count"></span> articulo(s) en el carrito</li>
              <li><ul class="menu list-unstyled px-3" id="cart_menu"></ul></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-center" href="cart_view.php">ir al carrito</a></li>
            </ul>
          </li>
          <?php
            if(isset($_SESSION['user'])){
              $image = (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg';
              echo '
                <li class="nav-item dropdown">
                  <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="'.$image.'" class="user-image rounded-circle me-2" style="width:28px;height:28px;object-fit:contain;background:rgba(0,0,0,0.15);" alt="User Image">
                    <span class="text-truncate" style="max-width:160px;">'.$user['firstname'].' '.$user['lastname'].'</span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end glass p-3 text-center" style="min-width:220px;">
                    <li>
                      <img src="'.$image.'" class="img-circle rounded-circle mb-2" style="width:60px;height:60px;object-fit:contain;background:rgba(0,0,0,0.15);" alt="User Image">
                      <p class="mb-2">
                        '.$user['firstname'].' '.$user['lastname'].'<br>
                        <small class="text-muted">Member since '.date('M. Y', strtotime($user['created_on'])).'</small>
                      </p>
                    </li>
                    <li class="d-flex justify-content-between gap-2">
                      <a href="profile.php" class="btn btn-sm btn-outline-secondary w-50">Perfil</a>
                      <a href="logout.php" class="btn btn-sm btn-outline-danger w-50">Salir</a>
                    </li>
                  </ul>
                </li>
              ';
            }
            else{
              echo '
                <li class="nav-item"><a class="nav-link" href="login.php">INICIAR SESIÓN</a></li>
                <li class="nav-item hide-on-landscape"><a class="nav-link" href="signup.php">REGISTRATE</a></li>
              ';
            }
          ?>
        </ul>
      </div>
    </div>
  </nav>
</header>
<style>
.cart_count:empty {
    display: none;
}

@media (max-width: 915px) and (max-height: 412px) {
    .hide-on-landscape {
        display: none !important;
    }
}

@media (max-width: 912px) and (max-height: 1368px) {
    .hide-on-landscape {
        display: none !important;
    }
}
</style>
