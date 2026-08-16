<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php
  if(!isset($_GET['code']) OR !isset($_GET['user'])){
    header('location: index.php');
    exit();
  }
?>
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
    ?>
    <h4 class="fw-bold mb-3">Introduce nueva contraseña</h4>
    <a href="index.php"><img src="images/<?php echo htmlspecialchars($settings['logo']); ?>" alt="<?php echo htmlspecialchars($settings['store_name']); ?>" height="80" class="mb-3"></a>

    <form action="password_new.php?code=<?php echo $_GET['code']; ?>&user=<?php echo $_GET['user']; ?>" method="POST" class="text-start">
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-lock me-1"></i> Nueva contraseña</label>
        <input type="password" class="form-control" name="password" placeholder="Nueva contraseña" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-lock-fill me-1"></i> Repetir contraseña</label>
        <input type="password" class="form-control" name="repassword" placeholder="Vuelve a escribirla" required>
      </div>
      <button type="submit" class="btn btn-accent w-100" name="reset">
        <i class="fa fa-check-square-o"></i> Reiniciar
      </button>
    </form>
  </div>
</div>
<?php include 'includes/scripts.php' ?>
</body>
</html>
