<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Especificaciones de Hilado</h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
        <li class="active">Especificaciones de Hilado</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-bs-dismiss='alert'>&times;</button>".$_SESSION['error']."</div>";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "<div class='alert alert-success alert-dismissible'><button type='button' class='close' data-bs-dismiss='alert'>&times;</button>".$_SESSION['success']."</div>";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-bs-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Nueva ficha de hilado</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <tr>
                    <th>Foto</th>
                    <th>Título</th>
                    <th>Composición</th>
                    <th>Herramientas</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();
                    $stmt = $conn->prepare("SELECT * FROM yarn_specs ORDER BY sort_order ASC, id ASC");
                    $stmt->execute();
                    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                      $image = (!empty($row['photo']) && file_exists('../images/'.$row['photo'])) ? '../images/'.$row['photo'] : '../images/noimage.jpg';
                      echo "
                        <tr>
                          <td><img src='".$image."' height='40' width='40' style='object-fit:cover;border-radius:8px;'></td>
                          <td>".htmlspecialchars($row['title'])."</td>
                          <td>".htmlspecialchars($row['composition'])."</td>
                          <td>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Editar</button>
                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Eliminar</button>
                          </td>
                        </tr>
                      ";
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
  <?php include 'includes/yarn_specs_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    getRow($(this).data('id'));
  });
  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    getRow($(this).data('id'));
  });
});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'yarn_specs_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.yarnid').val(response.id);
      $('.yarnname').html(response.title);
      $('#edit_title').val(response.title);
      $('#edit_description').val(response.description);
      $('#edit_composition').val(response.composition);
      $('#edit_yarn_title').val(response.yarn_title);
      $('#edit_presentation').val(response.presentation);
      $('#edit_production').val(response.production);
      $('#edit_needles').val(response.needles);
      $('#edit_weight').val(response.weight);
      $('#edit_uses').val(response.uses);
      $('#edit_care_instructions').val(response.care_instructions);
    }
  });
}
</script>
</body>
</html>
