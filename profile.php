<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php
	if(!isset($_SESSION['user'])){
		header('location: index.php');
	}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green layout-top-nav" style='border-radius:10px;'>
<div class="wrapper" >

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper" >
	    <div class="container" style='border-radius:10px;'>

	      <!-- Main content -->
	      <section class="content" style='border-radius:10px;'>
	        <div class="row" style='border-radius:10px;'>
	        	<div class="col-sm-9" style='border-radius:10px;'>
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='callout callout-danger' style='border-radius:10px;'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}

	        			if(isset($_SESSION['success'])){
	        				echo "
	        					<div class='callout callout-success' style='border-radius:10px;'>
	        						".$_SESSION['success']."
	        					</div>
	        				";
	        				unset($_SESSION['success']);
	        			}
	        		?>
	        		<div class="box box-solid" style='border-radius:10px;'>
	        			<div class="box-body" style='border-radius:10px; overflow-x: auto;'>
	        				<div class="col-xs-12 col-sm-3" style='border-radius:10px; overflow-x: auto;'>
	        					<img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" style='border-radius:10px;' width=" 80%">
	        				</div>
	        				<div class="col-xs-12 col-sm-9" style='border-radius:10px; overflow-x: auto;'>
	        					<div class="row" style='border-radius:10px;'>
	        						<div class="col-xs-6 col-sm-3" style='border-radius:10px; overflow-x: auto;'>
	        							<h4>Nombre:</h4>
	        							<h4>Correo electrónico:</h4>
	        							<h4> Información de contacto: </h4>
	        							<h4>Dirección:</h4>
	        							<h4>Miembro desde:</h4>
	        						</div>
	        						<div class="col-xs-6 col-sm-9" style='border-radius:10px; overflow-x: auto;'>
	        							<h4><?php echo $user['firstname'].' '.$user['lastname']; ?>
	        								<span class="pull-right">
	        									<a href="#edit" class="btn btn-success btn-flat btn-sm" style='border-radius:20px;' data-toggle="modal"><i class="fa fa-edit"></i> Edit</a>
	        								</span>
	        							</h4>
										<br>
	        							<h4><?php echo $user['email']; ?></h4>
	        							<h4><?php echo (!empty($user['contact_info'])) ? $user['contact_info'] : 'N/a'; ?></h4>
	        							<h4><?php echo (!empty($user['address'])) ? $user['address'] : 'N/a'; ?></h4>
	        							<h4><?php echo date('M d, Y', strtotime($user['created_on'])); ?></h4>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="box box-solid" style='border-radius:10px;'>
	        			<div class="box-header with-border" style='border-radius:10px;'>
	        				<h4 class="box-title" style='border-radius:10px;'><i class="fa fa-calendar" style='border-radius:10px;'></i> <b>Historial de transacciones</b></h4>
	        			</div>
	        			<div class="box-body table-responsive" style='border-radius:10px;overflow-x: auto;'>
	        				<table class="table table-bordered" id="example1" style='border-radius:10px;overflow-x: auto;'>
	        					<thead style='border-radius:10px; overflow-x: auto;'>
	        						<th class="hidden" style='border-radius:10px; overflow-x: auto;'></th>
	        						<th>Fecha</th>
	        						<th>Transacción#</th>
	        						<th>Cantidad </th>
	        						<th>Detalles completos</th>
	        					</thead>
	        					<tbody>
	        					<?php
	        						$conn = $pdo->open();

	        						try{
	        							$stmt = $conn->prepare("SELECT * 
																FROM sales 
																WHERE user_id=:user_id 
																ORDER BY sales_date DESC");
	        							$stmt->execute(['user_id'=>$user['id']]);
	        							foreach($stmt as $row){
	        								$stmt2 = $conn->prepare("SELECT * 
																	FROM details 
																	LEFT JOIN products ON products.id=details.product_id 
																	WHERE sales_id=:id");
	        								$stmt2->execute(['id'=>$row['id']]);
	        								$total = 0;
	        								foreach($stmt2 as $row2){
	        									$subtotal = $row2['price']*$row2['quantity'];
	        									$total += $subtotal;
	        								}
	        								echo "
	        									<tr>
	        										<td class='hidden' style='border-radius:10px;'></td>
	        										<td>".date('M d, Y', strtotime($row['sales_date']))."</td>
	        										<td>".$row['pay_id']."</td>
	        										<td>S/ ".number_format($total, 2)."</td>
	        										<td><button class='btn btn-sm btn-flat btn-info transact' style='border-radius:20px;' data-id='".$row['id']."'data-toggle='modal' data-target='#transaction'><i class='fa fa-search'></i> Ver</button></td>
	        									</tr>
	        								";
	        							}

	        						}
        							catch(PDOException $e){
										echo "Hay algún problema en la conexión.: " . $e->getMessage();
									}

	        						$pdo->close();
	        					?>
	        					</tbody>
	        				</table>
	        			</div>
	        		</div>	        	
				</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
  	<?php include 'includes/profile_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$(document).on('click', '.transact', function(e){
		e.preventDefault();
		$('#transaction').modal('show');
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'transaction.php',
			data: {id:id},
			dataType: 'json',
			success:function(response){
				$('#date').html(response.date);
				$('#transid').html(response.transaction);
				$('#detail').prepend(response.list);
				$('#total').html(response.total);
			}
		});
	});

	$("#transaction").on("hidden.bs.modal", function () {
	    $('.prepend_items').remove();
	});
});
</script>
</body>
</html>