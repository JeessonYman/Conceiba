<?php include 'includes/session.php'; ?>
<?php
  $where = '';
  if(isset($_GET['category'])){
    $catid = $_GET['category'];
    $where = 'WHERE category_id ='.$catid;
  }
  if(isset($_GET['provider'])){
    $proid = $_GET['provider'];
    if(empty($where)){
      $where = 'WHERE provider_id ='.$proid;
    } else {
      $where .= ' AND provider_id ='.$proid;
    }
  }
?>
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
        Lista de productos
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
        <li>Productos</li>
        <li class="active">Lista de productos</li>
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
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-bs-toggle="modal" class="btn btn-primary btn-sm btn-flat" id="addproduct"><i class="fa fa-plus"></i> Nuevo</a>
              <div class="float-end">
                <form class="form-inline">
                  <div class="form-group">
                    <label>Categoria: </label>
                    <select class="form-control input-sm" id="select_category">
                      <option value="0">Todos</option>
                      <?php
                        $conn = $pdo->open();

                        $stmt = $conn->prepare("SELECT * FROM category");
                        $stmt->execute();

                        foreach($stmt as $crow){
                          $selected = ($crow['id'] == $catid) ? 'selected' : ''; 
                          echo "
                            <option value='".$crow['id']."' ".$selected.">".$crow['name']."</option>
                          ";
                        }

                        $pdo->close();
                      ?>
                    </select>

                    <label>Proveedor: </label>
                    <select class="form-control input-sm" id="select_provider">
                      <option value="0">Todos</option>
                      <?php
                        $conn = $pdo->open();

                        $stmt = $conn->prepare("SELECT * FROM provider");
                        $stmt->execute();

                        foreach($stmt as $crow){
                          $selected = ($crow['id'] == $proid) ? 'selected' : ''; 
                          echo "
                            <option value='".$crow['id']."' ".$selected.">".$crow['name']."</option>
                          ";
                        }

                        $pdo->close();
                      ?>
                    </select>
                  </div>
                </form>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
<tr>
                  <th>Nombre</th>
                  <th>Foto</th>
                  <th>Descripción</th>
                  <th>Precio</th>
                  <th>Precio normal</th>
                  <th>Stock</th>
                  <th>Stock Minimo</th>
                  <th>Vistas hoy</th>
                  <th>Herramientas</th>
                </tr>
</thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $now = date('Y-m-d');
                      $stmt = $conn->prepare("SELECT * FROM products $where");
                      $stmt->execute();
                      foreach($stmt as $row){
                        $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/noimage.jpg';
                        $counter = ($row['date_view'] == $now) ? $row['counter'] : 0;
                        echo "
                          <tr>
                            <td>".$row['name']."</td>
                            <td>
                              <img src='".$image."' height='30px' width='30px'>
                              <span class='float-end'>
                                <a href='#edit_photo' class='photo' data-bs-toggle='modal' data-id='".$row['id']."' title='Cambiar foto principal'><i class='fa fa-edit'></i></a>
                                <a href='#gallery_photos' class='gallery ms-2' data-bs-toggle='modal' data-id='".$row['id']."' title='Galería de fotos'><i class='fa fa-images'></i></a>
                              </span>
                            </td>
                            <td><a href='#description' data-bs-toggle='modal' class='btn btn-info btn-sm btn-flat desc' data-id='".$row['id']."'><i class='fa fa-search'></i> Ver</a></td>
                            <td>S/ ".number_format($row['price'], 2)."</td>
                            <td>S/ ".number_format($row['price_normal'], 2)."</td>
                            <td>".$row['stock']."</td>
                            <td>".$row['stock_minimum']."</td>
                            <td>".$counter."</td>
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
</div>
<!-- ./wrapper -->
<?php include 'includes/footer.php'; ?> 
<?php include 'includes/products_modal.php'; ?>
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

  $(document).on('click', '.photo', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.gallery', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('.gallery-prodid').val(id);
    loadGallery(id);
  });

  $(document).on('click', '.gallery-delete-btn', async function(e){
    e.preventDefault();
    var imgId = $(this).data('imgid');
    var prodId = $('.gallery-prodid').val();
    const ok = await mariaConfirm('¿Eliminar esta imagen de la galería?', 'Eliminar imagen');
    if(!ok) return;
    $.ajax({
      type: 'POST',
      url: 'products_gallery_delete_ajax.php',
      data: {image_id: imgId},
      dataType: 'json',
      success: function(){
        loadGallery(prodId);
      }
    });
  });

  $(document).on('click', '.desc', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

  $('#select_category').change(function(){
    var val = $(this).val();
    if(val == 0){
      window.location = 'products.php';
    }
    else{
      window.location = 'products.php?category='+val;
    }
  });
  
  $('#select_provider').change(function(){
    var val = $(this).val();
    if(val == 0){
      window.location = 'products.php';
    }
    else{
      window.location = 'products.php?provider='+val;
    }
  });

  $('#addproduct').click(function(e){
    e.preventDefault();
    getCategory();
    getprovider();

  });

  $("#addnew").on("hidden.bs.modal", function () {
      $('.append_items').remove();
  });

  $("#edit").on("hidden.bs.modal", function () {
      $('.append_items').remove();
  });

});

function loadGallery(id){
  $('#gallery-thumbs').html('<p class="text-center py-3">Cargando...</p>');
  $.ajax({
    type: 'POST',
    url: 'products_gallery_fetch.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      if(response.length === 0){
        $('#gallery-thumbs').html('<p class="text-center text-muted py-3">Sin imágenes adicionales aún.</p>');
        return;
      }
      var html = '<div class="row g-2">';
      response.forEach(function(img){
        html += '\
          <div class="col-4 col-sm-3 gallery-thumb-item" data-imgid="'+img.id+'">\
            <div class="position-relative">\
              <img src="../images/'+img.image+'" class="img-fluid rounded" style="height:80px;width:100%;object-fit:cover;">\
              <button type="button" class="btn btn-danger btn-sm gallery-delete-btn" data-imgid="'+img.id+'" style="position:absolute;top:2px;right:2px;padding:2px 6px;line-height:1;"><i class="fa fa-times"></i></button>\
            </div>\
          </div>';
      });
      html += '</div>';
      $('#gallery-thumbs').html(html);
    }
  });
}

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'products_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('#desc').html(response.description);
      $('.name').html(response.prodname);
      $('.prodid').val(response.prodid);
      $('#edit_name').val(response.prodname);
      $('#catselected').val(response.category_id).html(response.catname);
      $('#proselected').val(response.provider_id).html(response.proname);
      $('#edit_price').val(response.price);
      $('#edit_cost').val(response.cost);
      $('#edit_price_normal').val(response.price_normal);
      $('#edit_stock').val(response.stock);
      $('#edit_stock_minimum').val(response.stock_minimum);
      CKEDITOR.instances["editor2"].setData(response.description);
      getCategory();
      getprovider();
    }
  });
}
function getCategory(){
  $.ajax({
    type: 'POST',
    url: 'category_fetch.php',
    dataType: 'json',
    success:function(response){
      $('#category').append(response);
      $('#edit_category').append(response);
    }
  });
}

function getprovider(){
  $.ajax({
    type: 'POST',
    url: 'provider_fetch.php',
    dataType: 'json',
    success:function(response){
      $('#provider').append(response);
      $('#edit_provider').append(response);
    }
  });
}
</script>
</body>
</html>
