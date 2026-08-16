<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Contenido de Inicio</h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
        <li class="active">Contenido de Inicio</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-bs-dismiss='alert'>&times;</button>".$_SESSION['error']."</div>";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "<div class='alert alert-success alert-dismissible'><button type='button' class='close' data-bs-dismiss='alert'>&times;</button>".$_SESSION['success']."</div>";
          unset($_SESSION['success']);
        }

        include_once __DIR__ . '/../includes/site_settings.php';
        $home = getKeyValueSettings($pdo, 'home_content', [
          'about_title' => '¿Quiénes somos?',
          'about_text' => '',
          'about_image' => '',
          'impact_title' => 'NUESTRO IMPACTO',
          'allies_title' => 'NUESTROS ALIADOS',
        ]);
      ?>

      <div class="box">
        <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-info-circle"></i> Sección "¿Quiénes somos?"</h3></div>
        <div class="box-body">
          <form method="POST" action="home_content_save.php" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Título</label>
              <input type="text" class="form-control" name="about_title" value="<?php echo htmlspecialchars($home['about_title']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Texto</label>
              <textarea class="form-control" name="about_text" rows="3"><?php echo htmlspecialchars($home['about_text']); ?></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Imagen actual</label><br>
              <?php if(!empty($home['about_image'])): ?>
                <img src="../images/<?php echo htmlspecialchars($home['about_image']); ?>" height="80" class="mb-2 rounded"><br>
              <?php endif; ?>
              <input type="file" class="form-control" name="about_image" accept="image/*">
            </div>
            <div class="mb-3">
              <label class="form-label">Título de "Nuestro impacto"</label>
              <input type="text" class="form-control" name="impact_title" value="<?php echo htmlspecialchars($home['impact_title']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Título de "Nuestros aliados"</label>
              <input type="text" class="form-control" name="allies_title" value="<?php echo htmlspecialchars($home['allies_title']); ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-flat" name="save_text"><i class="fa fa-save"></i> Guardar textos</button>
          </form>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-chart-bar"></i> Estadísticas de impacto</h3></div>
            <div class="box-body">
              <?php
                $conn = $pdo->open();
                $stmt = $conn->prepare("SELECT * FROM impact_stats ORDER BY sort_order ASC, id ASC");
                $stmt->execute();
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $stat){
                  $img = (!empty($stat['image']) && file_exists('../images/'.$stat['image'])) ? '../images/'.$stat['image'] : '../images/noimage.jpg';
                  echo "
                    <div class='d-flex align-items-center justify-content-between border-bottom py-2'>
                      <div class='d-flex align-items-center'>
                        <img src='".$img."' height='40' width='40' class='me-2 rounded' style='object-fit:cover;'>
                        <span>".htmlspecialchars($stat['label'])."</span>
                      </div>
                      <form method='POST' action='home_content_save.php' class='maria-confirm-form' data-confirm-msg='¿Eliminar esta estadística?'>
                        <input type='hidden' name='id' value='".$stat['id']."'>
                        <button type='submit' class='btn btn-danger btn-sm btn-flat' name='delete_stat'><i class='fa fa-trash'></i></button>
                      </form>
                    </div>
                  ";
                }
              ?>
              <hr>
              <form method="POST" action="home_content_save.php" enctype="multipart/form-data">
                <div class="mb-2">
                  <label class="form-label">Nueva estadística — texto</label>
                  <input type="text" class="form-control" name="label" placeholder="Ej. 50 familias productoras" required>
                </div>
                <div class="mb-2">
                  <label class="form-label">Ícono / imagen</label>
                  <input type="file" class="form-control" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-success btn-sm btn-flat" name="add_stat"><i class="fa fa-plus"></i> Agregar</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-handshake"></i> Aliados</h3></div>
            <div class="box-body">
              <?php
                $stmt = $conn->prepare("SELECT * FROM allies ORDER BY sort_order ASC, id ASC");
                $stmt->execute();
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $ally){
                  $img = (!empty($ally['image']) && file_exists('../images/'.$ally['image'])) ? '../images/'.$ally['image'] : '../images/noimage.jpg';
                  echo "
                    <div class='d-flex align-items-center justify-content-between border-bottom py-2'>
                      <img src='".$img."' height='40' width='40' class='me-2 rounded' style='object-fit:cover;'>
                      <form method='POST' action='home_content_save.php' class='maria-confirm-form' data-confirm-msg='¿Eliminar este aliado?'>
                        <input type='hidden' name='id' value='".$ally['id']."'>
                        <button type='submit' class='btn btn-danger btn-sm btn-flat' name='delete_ally'><i class='fa fa-trash'></i></button>
                      </form>
                    </div>
                  ";
                }
                $pdo->close();
              ?>
              <hr>
              <form method="POST" action="home_content_save.php" enctype="multipart/form-data">
                <div class="mb-2">
                  <label class="form-label">Logo</label>
                  <input type="file" class="form-control" name="image" accept="image/*" required>
                </div>
                <div class="mb-2">
                  <label class="form-label">Link de Facebook (opcional)</label>
                  <input type="text" class="form-control" name="facebook_url">
                </div>
                <div class="mb-2">
                  <label class="form-label">Link de Twitter/X (opcional)</label>
                  <input type="text" class="form-control" name="twitter_url">
                </div>
                <div class="mb-2">
                  <label class="form-label">Link de Instagram (opcional)</label>
                  <input type="text" class="form-control" name="instagram_url">
                </div>
                <button type="submit" class="btn btn-success btn-sm btn-flat" name="add_ally"><i class="fa fa-plus"></i> Agregar aliado</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
