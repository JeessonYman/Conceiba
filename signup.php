<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }

  if(isset($_SESSION['captcha'])){
    $now = time();
    if($now >= $_SESSION['captcha']){
      unset($_SESSION['captcha']);
    }
  }
?>
<?php include 'includes/header.php'; ?>
<body>
<div class="auth-wrapper">
  <div class="auth-box text-center" style="max-width:440px;">
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
    <h4 class="fw-bold mb-3">Registrar una nueva cuenta</h4>
    <a href="index.php"><img src="images/<?php echo htmlspecialchars($settings['logo']); ?>" alt="<?php echo htmlspecialchars($settings['store_name']); ?>" height="80" class="mb-3"></a>

    <form action="register.php" method="POST" class="text-start">
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-person me-1"></i> Nombre</label>
        <input type="text" class="form-control" name="firstname" placeholder="Nombre" value="<?php echo (isset($_SESSION['firstname'])) ? $_SESSION['firstname'] : '' ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-person me-1"></i> Apellido</label>
        <input type="text" class="form-control" name="lastname" placeholder="Apellido" value="<?php echo (isset($_SESSION['lastname'])) ? $_SESSION['lastname'] : '' ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-envelope me-1"></i> Correo electrónico</label>
        <input type="email" class="form-control" name="email" placeholder="tu@correo.com" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-lock me-1"></i> Contraseña</label>
        <input type="password" class="form-control" name="password" placeholder="Mínimo 8 caracteres" minlength="8" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-lock-fill me-1"></i> Repetir contraseña</label>
        <input type="password" class="form-control" name="repassword" placeholder="Vuelve a escribirla" minlength="8" required>
      </div>

      <?php
        if(!isset($_SESSION['captcha'])){
          echo '
            <div class="mb-3 d-flex justify-content-center">
              <div class="g-recaptcha" data-sitekey="6LevO1IUAAAAAFX5PpmtEoCxwae-I8cCQrbhTfM6"></div>
            </div>
          ';
        }
      ?>

      <button type="submit" class="btn btn-accent w-100" name="signup">
        <i class="fa fa-pencil"></i> Regístrate
      </button>
    </form>

    <hr class="my-3">
    <a href="login.php" class="d-block mb-2 fw-semibold">Ya tengo cuenta</a>
    <a href="index.php" class="d-block"><i class="fa fa-home"></i> Volver al inicio</a>
  </div>
</div>
<?php include 'includes/scripts.php' ?>
</body>
</html>
