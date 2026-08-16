<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body>
<div class="auth-wrapper">
  <div class="auth-box text-center">
    <?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='alert alert-danger text-center'>
            ".$_SESSION['error']."
          </div>
        ";
        unset($_SESSION['error']);
      }
      if(isset($_SESSION['success'])){
        echo "
          <div class='alert alert-success text-center'>
            ".$_SESSION['success']."
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?>
    <h5 class="fw-bold mb-3">Ingresa el correo asociado a tu cuenta</h5>
    <a href="index.php"><img src="images/<?php echo htmlspecialchars($settings['logo']); ?>" alt="<?php echo htmlspecialchars($settings['store_name']); ?>" height="80" class="mb-3"></a>

    <form action="reset.php" method="POST" class="text-start">
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-envelope me-1"></i> Correo electrónico</label>
        <input type="email" class="form-control" name="email" placeholder="tu@correo.com" required>
      </div>
      <button type="submit" class="btn btn-accent w-100" name="reset">
        <i class="fa fa-mail-forward"></i> Enviar
      </button>
    </form>

    <hr class="my-3">
    <a href="login.php" class="d-block mb-2 fw-semibold">Recuerdo mi contraseña</a>
    <a href="index.php" class="d-block"><i class="fa fa-home"></i> Volver al inicio</a>
  </div>
</div>
<?php include 'includes/scripts.php' ?>
</body>
</html>
