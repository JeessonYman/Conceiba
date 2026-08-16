<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php
	if(!isset($_SESSION['user'])){
		header('location: index.php');
	}
?>
<?php include 'includes/header.php'; ?>
<body>
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>

	<div class="content-wrapper">
		<div class="container-fluid px-4 py-3">

			<!-- Main content -->
			<section class="content">
				<div class="row g-4">
					<div class="col-lg-9">
						<?php
							if(isset($_SESSION['error'])){
								echo "
									<div class='alert alert-danger'>
										".$_SESSION['error']."
									</div>
								";
								unset($_SESSION['error']);
							}

							if(isset($_SESSION['success'])){
								echo "
									<div class='alert alert-success'>
										".$_SESSION['success']."
									</div>
								";
								unset($_SESSION['success']);
							}
						?>

						<div class="glass p-4 mb-4">
							<div class="row g-3 align-items-start">
								<div class="col-12 col-sm-3 text-center">
									<img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" class="rounded-3 w-100" style="object-fit:contain; height:180px; background:rgba(0,0,0,0.15);">
								</div>
								<div class="col-12 col-sm-9">
									<div class="row g-2">
										<div class="col-5 col-sm-3 fw-semibold">Nombre:</div>
										<div class="col-7 col-sm-9">
											<?php echo $user['firstname'].' '.$user['lastname']; ?>
											<a href="#edit" class="btn btn-accent btn-sm float-end" data-bs-toggle="modal"><i class="fa fa-edit"></i> Editar</a>
										</div>

										<div class="col-5 col-sm-3 fw-semibold">Correo electrónico:</div>
										<div class="col-7 col-sm-9"><?php echo $user['email']; ?></div>

										<div class="col-5 col-sm-3 fw-semibold">Información de contacto:</div>
										<div class="col-7 col-sm-9"><?php echo (!empty($user['contact_info'])) ? $user['contact_info'] : 'N/a'; ?></div>

										<div class="col-5 col-sm-3 fw-semibold">Dirección:</div>
										<div class="col-7 col-sm-9"><?php echo (!empty($user['address'])) ? $user['address'] : 'N/a'; ?></div>

										<div class="col-5 col-sm-3 fw-semibold">Miembro desde:</div>
										<div class="col-7 col-sm-9"><?php echo date('M d, Y', strtotime($user['created_on'])); ?></div>
									</div>
								</div>
							</div>
						</div>

						<div class="glass p-4">
							<h5 class="mb-3"><i class="fa fa-calendar"></i> <b>Historial de transacciones</b></h5>
							<div class="table-responsive">
								<table class="table table-borderless align-middle" id="example1">
									<thead>
										<tr>
											<th class="d-none"></th>
											<th>Fecha</th>
											<th>Transacción#</th>
											<th>Cantidad</th>
											<th>Detalles completos</th>
										</tr>
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
														<td class='d-none'></td>
														<td>".date('M d, Y', strtotime($row['sales_date']))."</td>
														<td>".$row['pay_id']."</td>
														<td>S/ ".number_format($total, 2)."</td>
														<td><button class='btn btn-sm btn-outline-info transact' data-id='".$row['id']."' data-bs-toggle='modal' data-bs-target='#transaction'><i class='fa fa-search'></i> Ver</button></td>
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
					<div class="col-lg-3">
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
