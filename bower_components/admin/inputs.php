<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
 <!-- Contenedor de contenido. Contiene contenido de la página -->
  <div class="content-wrapper">
    <!-- Encabezado de contenido (encabezado de página) -->
    <section class="content-header">
      <h1>
        Historial Ingreso
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
        <li class="active">Ingresos</li>
      </ol>
    </section>

    <!-- Contenido principal -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="inputs_new.php" class="btn btn-primary" id="newinputs"><i class="fa fa-plus"></i> Nuevo</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
<tr>
                  <th class="hidden"></th>
                  <th>Código</th>
                  <th>Proveedor</th>
                  <th>Usuario</th>
                  <th>Fecha</th>
                  <th>Total</th>
                  <th>Herramientas</th>
                </tr>
</thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT *,
                                                      inputs.id AS inputsid,
                                                      provider.name AS proveedor,
                                                      CONCAT_WS(' ', users.firstname,users.lastname) AS usuario
                                              FROM inputs 
                                              LEFT JOIN users ON users.id=inputs.user_id
                                              LEFT JOIN provider ON provider.id=inputs.provider_id
                                              ORDER BY entry_date DESC");
                      $stmt->execute();
                      foreach($stmt as $row){ 
                        $stmt = $conn->prepare("SELECT *
                                                FROM details_inputs
                                                LEFT JOIN products ON products.id=details_inputs.id_products
                                                WHERE details_inputs.id_inputs=:id");
                        $stmt->execute(['id'=>$row['inputsid']]);
                        $total = 0;
                        foreach($stmt as $details){
                          $subtotal = $details['price']*$details['quantity'];
                          $total += $subtotal;
                        }
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td>".$row['inputsid']."</td>
                            <td>".$row['proveedor']."</td>
                            <td>".$row['usuario']."</td>
                            <td>".date('M d, Y', strtotime($row['entry_date']))."</td>
                            <td>S/ ".number_format($row['total'], 2)."</td>
                            <td>
                              <button type='button' class='btn btn-info btn-sm btn-flat inputsdetailsview' data-id='".$row['inputsid']."'>
                                <i class='fa fa-search'>
                                </i> Ver
                              </button>
                            </td>
                          </tr>
                        ";
                      }
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                    $pdo->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
     
  </div>
  	<?php include 'includes/footer.php'; ?>
    <?php include 'includes/inputs_modal.php'; ?>


</div>
<!-- ./envoltura -->

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.inputsdetailsview', function(e){
    e.preventDefault();
    $('#inputsdetailss').modal('show');
    const id = $(this).data('id');
    $.ajax({
      type: 'POST',
      url: 'inputsdetailsview.php',
      data: {id:id},
      dataType: 'json',
      success:function(response){
        $('#date').html(response.date);
        $('#inputsid').html(response.inputsid);
        $('#detail').prepend(response.list);
        $('#total').html(response.total);
      }
    });
  });

  $("#inputsdetailss").on("hidden.bs.modal", function () {
      $('.prepend_items').remove();
  });
});
</script>
</body>

