<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Configuración del sitio</h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
        <li class="active">Configuración</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-bs-dismiss='alert' aria-hidden='true'>&times;</button>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-bs-dismiss='alert' aria-hidden='true'>&times;</button>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>

      <div class="row">
        <div class="col-md-7">
          <div class="box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-store"></i> Identidad de la tienda</h3></div>
            <div class="box-body">
              <form method="POST" action="site_settings_save.php" enctype="multipart/form-data">
                <div class="mb-3">
                  <label class="form-label">Nombre de la tienda</label>
                  <input type="text" class="form-control" name="store_name" value="<?php echo htmlspecialchars($settings['store_name']); ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Eslogan / tagline</label>
                  <input type="text" class="form-control" name="store_tagline" value="<?php echo htmlspecialchars($settings['store_tagline']); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Logo actual</label><br>
                  <img src="../images/<?php echo htmlspecialchars($settings['logo']); ?>" height="60" class="mb-2 rounded"><br>
                  <input type="file" class="form-control" name="logo" accept="image/*">
                  <small class="text-muted">Deja en blanco para mantener el logo actual.</small>
                </div>

                <hr>
                <h4><i class="fa fa-palette"></i> Colores del sitio</h4>
                <div class="row">
                  <div class="col-4">
                    <label class="form-label">Color principal</label>
                    <input type="color" class="form-control form-control-color" name="color_primary" value="<?php echo htmlspecialchars($settings['color_primary']); ?>">
                  </div>
                  <div class="col-4">
                    <label class="form-label">Color principal (oscuro)</label>
                    <input type="color" class="form-control form-control-color" name="color_primary_dark" value="<?php echo htmlspecialchars($settings['color_primary_dark']); ?>">
                  </div>
                  <div class="col-4">
                    <label class="form-label">Color de acento</label>
                    <input type="color" class="form-control form-control-color" name="color_accent" value="<?php echo htmlspecialchars($settings['color_accent']); ?>">
                  </div>
                </div>

                <hr>
                <h4><i class="fa fa-file-invoice"></i> Datos legales (para boletas / facturas / correos)</h4>
                <div class="mb-3">
                  <label class="form-label">RUC</label>
                  <input type="text" class="form-control" name="company_ruc" value="<?php echo htmlspecialchars($settings['company_ruc']); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Dirección fiscal</label>
                  <input type="text" class="form-control" name="company_address" value="<?php echo htmlspecialchars($settings['company_address']); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Teléfono de contacto</label>
                  <input type="text" class="form-control" name="company_phone" value="<?php echo htmlspecialchars($settings['company_phone']); ?>">
                </div>

                <hr>
                <button type="submit" class="btn btn-primary btn-flat" name="save"><i class="fa fa-save"></i> Guardar cambios</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-5">
          <div class="box">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-robot"></i> Identidad del asistente virtual</h3></div>
            <div class="box-body">
              <form method="POST" action="site_settings_save.php" enctype="multipart/form-data">
                <div class="mb-3">
                  <label class="form-label">Nombre del asistente</label>
                  <input type="text" class="form-control" name="assistant_name" value="<?php echo htmlspecialchars($settings['assistant_name']); ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Tagline del asistente</label>
                  <input type="text" class="form-control" name="assistant_tagline" value="<?php echo htmlspecialchars($settings['assistant_tagline']); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Avatar actual</label><br>
                  <img src="../images/<?php echo htmlspecialchars($settings['assistant_avatar']); ?>" height="60" class="mb-2 rounded-circle"><br>
                  <input type="file" class="form-control" name="assistant_avatar" accept="image/*">
                  <small class="text-muted">Deja en blanco para mantener el avatar actual.</small>
                </div>
                <div class="mb-3">
                  <label class="form-label">URL del backend de voz (Colab / XTTS)</label>
                  <input type="text" class="form-control" name="assistant_tts_backend_url" value="<?php echo htmlspecialchars($settings['assistant_tts_backend_url']); ?>" placeholder="https://xxxx.ngrok-free.app">
                  <small class="text-muted">Pega aquí la URL pública (ngrok) que imprime tu notebook al correrlo. Déjalo vacío para usar la voz del navegador en su lugar.</small>
                </div>
                <button type="submit" class="btn btn-primary btn-flat" name="save_assistant"><i class="fa fa-save"></i> Guardar cambios</button>
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
