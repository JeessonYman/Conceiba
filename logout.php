<!DOCTYPE html>
<html lang="es">
<?php
	session_start();
	session_destroy();

	header('location: index.php');
?>
</html>
