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
<body class="hold-transition " style="background: url(images/colores.gif);" >
<body class="hold-transition register-page bg-green" style="background: url(images/colores1.gif) ;"  style="border-radius:20px;">
<div class="register-box" style="border-radius:20px;">
  	<?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='callout callout-danger text-center'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }

      if(isset($_SESSION['success'])){
        echo "
          <div class='callout callout-success style='border-radius:20px;' text-center'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?>
  	<div class="register-box-body" style="border-radius:20px;">
    	<p class="login-box-msg"><b>Registrar una nueva cuenta</b></p>
      <a href="#"><img src="images/Conceiba.png" alt="" height="120px" width="320px"></a>
      <br>
      <br>

    	<form action="register.php" method="POST">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" style="border-radius:20px;" name="firstname" placeholder="Nombre" value="<?php echo (isset($_SESSION['firstname'])) ? $_SESSION['firstname'] : '' ?>" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" style="border-radius:20px;" name="lastname" placeholder="Apellido" value="<?php echo (isset($_SESSION['lastname'])) ? $_SESSION['lastname'] : '' ?>"  required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
      		<div class="form-group has-feedback">
        		<input type="email" class="form-control" style="border-radius:20px;" name="email" placeholder="Correo electrónico" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" required>
        		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      		</div>
          <div class="form-group has-feedback">
            <input type="password" style="border-radius:20px;" class="form-control" name="password" placeholder="Contraseña" minlength="8" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" style="border-radius:20px;" class="form-control" name="repassword" placeholder="Vuelva a escribir la contraseña"  minlength="8" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <center>
          <?php
            if(!isset($_SESSION['captcha'])){
              echo '
                <di class="form-group" style="border-radius:20px; color:black; width:100%;">
                  <div style="border-radius:20px; color:black;" class="g-recaptcha " data-sitekey="6LevO1IUAAAAAFX5PpmtEoCxwae-I8cCQrbhTfM6"></div>
                </di>
              ';
            }
          ?>
          </center>
          <hr>
      		<div class="row">
    			<div class="col-xs-5">
          			<button type="submit"  style="border-radius:20px;" class="btn btn-primary btn-block btn-flat" name="signup"><b><i class="fa fa-pencil"></i> Regístrate</b></button>
        		</div>
      		</div>
    	</form>
      <br>
      <a href="login.php"><b >Ya tengo cuenta</b></a><br>
      <a href="index.php"><b ><i class="fa fa-home"></i> Casa</b></a>
  	</div>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>