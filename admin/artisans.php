<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Artesanos
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
        <li class="active">Artesanos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-bs-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-bs-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> ¡Éxito!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <p class="text-muted">Estos artesanos aparecen en la sección "Conoce a nuestras artesanas Conceiba" de la página de Hilado del sitio público. Puedes agregar todos los que quieras.</p>
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-bs-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Nuevo</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Comunidad</th>
                    <th>Visible</th>
                    <th>Herramientas</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT * FROM artisans ORDER BY sort_order ASC, id ASC");
                      $stmt->execute();
                      foreach($stmt as $row){
                        $image = (!empty($row['photo']) && file_exists('../images/'.$row['photo'])) ? '../images/'.$row['photo'] : '../images/noimage.jpg';
                        $activeLabel = ($row['active']) ? "<span class='badge bg-success'>Visible</span>" : "<span class='badge bg-secondary'>Oculto</span>";
                        echo "
                          <tr>
                            <td><img src='".$image."' height='40' width='40' style='object-fit:cover;border-radius:50%;'></td>
                            <td>".$row['name']."</td>
                            <td>".$row['community']."</td>
                            <td>".$activeLabel."</td>
                            <td>
                              <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Editar</button>
                              <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Eliminar</button>
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
    <?php include 'includes/artisans_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'artisans_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.artisanid').val(response.id);
      $('.artisanname').html(response.name);
      $('#edit_name').val(response.name);
      $('#edit_age').val(response.age);
      $('#edit_community').val(response.community);
      $('#edit_bio').val(response.bio);
      $('#edit_active').prop('checked', response.active == 1);
    }
  });
}
</script>
</body>
</html>
