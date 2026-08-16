<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Contenido de Contacto</h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
        <li class="active">Contenido de Contacto</li>
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
        $contact = getKeyValueSettings($pdo, 'contact_content', [
          'intro_title' => 'Envíanos un mensaje',
          'map_embed_url' => '',
        ]);
      ?>

      <div class="box">
        <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-envelope"></i> Contenido de la página de Contacto</h3></div>
        <div class="box-body">
          <form method="POST" action="contact_content_save.php">
            <div class="mb-3">
              <label class="form-label">Título del formulario</label>
              <input type="text" class="form-control" name="intro_title" value="<?php echo htmlspecialchars($contact['intro_title']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">URL del mapa embebido de Google Maps</label>
              <textarea class="form-control" name="map_embed_url" rows="3"><?php echo htmlspecialchars($contact['map_embed_url']); ?></textarea>
              <small class="text-muted">Ve a Google Maps → Compartir → Insertar un mapa → copia solo la URL que está dentro de <code>src="..."</code>.</small>
            </div>
            <button type="submit" class="btn btn-primary btn-flat" name="save"><i class="fa fa-save"></i> Guardar</button>
          </form>
        </div>
      </div>
    </section>
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
