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
<body class="hold-transition" 
      style="
        background: url('images/colores3.gif') no-repeat center center fixed;
        background-size: cover;
      ">
<body class="hold-transition login-page" style="background: url(images/colores.gif);">
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
    ?>
  	<div class="login-box-body" style='border-radius:10px;'>
    	<p class="login-box-msg" style='border-radius:10px;'>Introduzca nueva contraseña</p>
      <a href="#"><img src="images/Conceiba.png" alt="" height="120px" width="320px"></a>
    	<form action="password_new.php?code=<?php echo $_GET['code']; ?>&user=<?php echo $_GET['user']; ?>"  style='border-radius:10px;' method="POST">
      		<div class="form-group has-feedback" style='border-radius:10px;'>
        		<input type="password" class="form-control" style='border-radius:10px;' name="password" placeholder="Nueva contraseña" required>
        		<span class="glyphicon glyphicon-lock form-control-feedback" style='border-radius:10px;'></span>
      		</div>
          <div class="form-group has-feedback" style='border-radius:10px;'>
            <input type="password" class="form-control" style='border-radius:10px;' name="repassword" placeholder="Vuelva a escribir la contraseña" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback" style='border-radius:10px;' ></span>
          </div>
      		<div class="row" style='border-radius:10px;'>
    			<div class="col-xs-4" style='border-radius:10px;'>
          			<button type="submit" class="btn btn-primary btn-block btn-flat" style='border-radius:20px;' name="reset"><i class="fa fa-check-square-o"></i> Reiniciar</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>
