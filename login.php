<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition" style="background: url(images/colores1.gif);" >
<center>
<div class="login-box">
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
          <div class='callout callout-success  style='border-radius:20px;' text-center'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?>
  	<div class="login-box-body" style="border-radius:20px;">
    	<p class="login-box-msg"><b>Inicia Sesión</b></p>
      <a href="#"><img src="images/Conceiba.png" alt="" height="120px" width="320px"></a>
      <br>
      <br>
    	<form action="verify.php" method="POST" style="border-radius:20px;">
      		<div class="form-group has-feedback bg">
        		<input type="email" class="form-control" style="border-radius:20px;" name="email" placeholder="Correo electrónico" required>
        		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      		</div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" style="border-radius:20px;" name="password" placeholder="Contraseña" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
      		<div class="row">
    			<div class="col-xs-4">
          			<button type="submit" class="btn btn-primary btn-block btn-flat"  style="border-radius:20px;" name="login"><i class="fa fa-sign-in"></i> Ingresar</button>
        		</div>
      		</div>
    	</form>
      <br>
      <a href="password_forgot.php"><b style="color:hsl(0,100%,50%);">Olvidé mi contraseña</b></a><br>
      <a href="signup.php" class="text-center"><b >Registrar una cuenta </b></a><br>
      <a href="index.php"><b><i class="fa fa-home"></i> Casa </b></a>
  	</div>
</div>
</center>
<?php include 'includes/scripts.php' ?>
</body>
</html>