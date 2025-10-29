<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition" 
      style="
        background: url('images/colores2.gif') no-repeat center center fixed;
        background-size: cover;
      ">
<body class="hold-transition login-page" style="background: url(images/colores1.gif);">
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
          <div class='callout callout-success style='border-radius:20px;'text-center'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?>
  	<div class="login-box-body" style='border-radius:10px;'>
    	<p class="login-box-msg" style='border-radius:10px;'>Ingrese el correo electrónico asociado con la cuenta</p>
      <a href="#"><img src="images/Conceiba.png" alt="" height="120px" width="320px"></a>
      <br>
      <br>
    	<form action="reset.php" method="POST" style='border-radius:10px;'>
      		<div class="form-group has-feedback" style='border-radius:10px;'>
        		<input type="email" class="form-control" style='border-radius:10px;' name="email" placeholder="Correo electrónico" required>
        		<span class="glyphicon glyphicon-envelope form-control-feedback" style='border-radius:10px;'></span>
      		</div>
      		<div class="row" style='border-radius:10px;'>
    			<div class="col-xs-4" style='border-radius:10px;'>
          			<button type="submit" class="btn btn-primary btn-block btn-flat" style='border-radius:10px;' name="reset"><i class="fa fa-mail-forward"></i> Send</button>
        		</div>
      		</div>
    	</form>
      <br>
      <a href="login.php">Recuerdo mi contraseña</a><br>
      <a href="index.php"><i class="fa fa-home"></i> Casa</a>
  	</div>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>