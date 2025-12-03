<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<style>
/* ==============================================
   ESTILOS RESPONSIVOS PARA USUARIOS
   ============================================== */

:root {
    --bg-light: #fff;
    --text-light: #333;
    --border-light: #ddd;
    --bg-dark: #222;
    --text-dark: #fff;
    --border-dark: #555;
}

/* Modo Claro */
.skin-green .content-header > h1 {
    color: #333 !important;
    font-weight: 600;
    text-shadow: none !important;
}

.skin-green .content-header .breadcrumb {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 3px;
    padding: 8px 15px;
}

.skin-green .content-header .breadcrumb > li > a {
    color: #337ab7 !important;
    font-weight: 500;
}

.skin-green .content-header .breadcrumb > .active {
    color: #555 !important;
    font-weight: 600;
}

/* Modo Oscuro */
body.dark-mode .content-header > h1 {
    color: #ecf0f1 !important;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

body.dark-mode .content-header .breadcrumb {
    background: rgba(52, 73, 94, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

body.dark-mode .content-header .breadcrumb > li > a {
    color: #3498db !important;
}

body.dark-mode .content-header .breadcrumb > .active {
    color: #bdc3c7 !important;
}

body.dark-mode .box {
    background: #2c3e50;
    border-color: #34495e;
}

body.dark-mode .box-header {
    background: #34495e;
    border-color: #34495e;
}

body.dark-mode .table {
    color: #ecf0f1;
}

body.dark-mode .table > thead > tr > th {
    background: #34495e;
    color: #ecf0f1;
    border-color: #455a64;
}

body.dark-mode .table > tbody > tr > td {
    border-color: #455a64;
}

body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) {
    background-color: rgba(255, 255, 255, 0.05);
}

/* Responsive */
@media (max-width: 767px) {
    .content-header > h1 {
        font-size: 22px !important;
        margin: 10px 0;
    }
    
    .content-header .breadcrumb {
        padding: 5px 10px;
        font-size: 12px;
    }
    
    .box-header .btn {
        width: 100% !important;
        margin: 0 0 5px 0 !important;
    }
    
    .table {
        font-size: 11px;
    }
    
    .table > thead > tr > th,
    .table > tbody > tr > td {
        padding: 6px 3px !important;
        font-size: 11px;
    }
    
    .table > thead > tr > th:first-child,
    .table > tbody > tr > td:first-child {
        display: none;
    }
    
    .table .btn {
        padding: 4px 6px !important;
        font-size: 10px !important;
        width: 100%;
        margin-bottom: 3px;
    }
    
    .label {
        font-size: 10px;
        padding: 3px 6px;
    }
}

@media (min-width: 768px) and (max-width: 1024px) {
    .content-header > h1 {
        font-size: 26px;
    }
    
    .table {
        font-size: 13px;
    }
}

@media (min-width: 1920px) {
    .content-wrapper {
        max-width: 1800px;
        margin: 0 auto;
    }
}

.table > tbody > tr > td img {
    border-radius: 50%;
    border: 2px solid #ddd;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.table > tbody > tr > td img:hover {
    transform: scale(1.1);
}

body.dark-mode .table > tbody > tr > td img {
    border-color: #555;
}

.table > tbody > tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

body.dark-mode .table > tbody > tr:hover {
    background-color: rgba(52, 152, 219, 0.1);
}
</style>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Usuarios</h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
        <li class="active">Usuarios</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>".$_SESSION['error']."
            </div>";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              <h4><i class='icon fa fa-check'></i> ¡Éxito!</h4>".$_SESSION['success']."
            </div>";
          unset($_SESSION['success']);
        }
      ?>

      <!-- Contenedor para alertas generadas por AJAX -->
      <div id="ajax-alerts" style="margin: 10px 0;"></div>

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                <i class="fa fa-plus"></i> Nuevo
              </a>
              <button type="button" class="btn btn-warning btn-sm btn-flat" data-toggle="modal" data-target="#clientsModal">
                <i class="fa fa-users"></i> Mostrar Clientes
              </button>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Foto</th>
                      <th>Correo electrónico</th>
                      <th>Nombre</th>
                      <th>Estado</th>
                      <th>Fecha Agregada</th>
                      <th>Herramientas</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $conn = $pdo->open();

                      try{
                        $stmt = $conn->prepare("SELECT * FROM users WHERE type=:type ORDER BY created_on DESC");
                        $stmt->execute(['type'=>1]);
                        foreach($stmt as $row){
                          $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                          $status = ($row['status']) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Inactivo</span>';

                          echo "
                            <tr>
                              <td class='text-center'>
                                <img src='".$image."' height='40px' width='40px'>
                                <span class='pull-right'>
                                  <a href='#edit_photo' class='photo' data-toggle='modal' data-id='".$row['id']."'>
                                    <i class='fa fa-edit'></i>
                                  </a>
                                </span>
                              </td>
                              <td>".$row['email']."</td>
                              <td>".$row['firstname'].' '.$row['lastname']."</td>
                              <td class='text-center'>".$status."</td>
                              <td>".date('d/m/Y', strtotime($row['created_on']))."</td>
                              <td>
                                <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'>
                                  <i class='fa fa-edit'></i> Editar
                                </button>
                                <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'>
                                  <i class='fa fa-trash'></i> Eliminar
                                </button>
                                <!-- Botón para despromover administrador a usuario -->
                                <button class='btn btn-warning btn-sm demote-admin-btn' data-id='".$row['id']."' data-name='".$row['firstname'].' '.$row['lastname']."' title='Quitar permisos de administrador' style='margin-left:5px;'>
                                  <i class='fa fa-user-times'></i> Quitar Admin
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
      </div>
    </section>
     
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/users_modal.php'; ?>
  <?php include 'includes/users_clients_modal.php'; ?>

</div>

<?php include 'includes/scripts.php'; ?>

<script>
$(document).ready(function(){
  console.log('✅ [USERS.PHP] Página cargada');
  
  // Botón EDITAR
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    console.log('📝 [EDIT] Botón clickeado');
    $('#edit').modal('show');
    getRow($(this).data('id'));
  });

  // Botón ELIMINAR
  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    console.log('🗑️ [DELETE] Botón clickeado');
    $('#delete').modal('show');
    getRow($(this).data('id'));
  });

  // Botón FOTO
  $(document).on('click', '.photo', function(e){
    e.preventDefault();
    console.log('📷 [PHOTO] Botón clickeado');
    $('#edit_photo').modal('show');
    getRow($(this).data('id'));
  });

  // Función para obtener datos del usuario
  function getRow(id){
    console.log('📡 [AJAX] Obteniendo datos del usuario ID:', id);
    $.ajax({
      type: 'POST',
      url: 'users_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        console.log('✅ [AJAX] Datos recibidos:', response);
        $('.userid').val(response.id);
        $('#edit_email').val(response.email);
        $('#edit_password').val(response.password);
        $('#edit_firstname').val(response.firstname);
        $('#edit_lastname').val(response.lastname);
        $('#edit_address').val(response.address);
        $('#edit_contact').val(response.contact_info);
        $('.fullname').html(response.firstname+' '+response.lastname);
      },
      error: function(xhr, status, error) {
        console.error('❌ [AJAX] Error:', error);
        alert('Error al cargar los datos del usuario');
      }
    });
  }
});
</script>

<script>
// Lógica para despromover administradores desde la lista principal
$(document).on('click', '.demote-admin-btn', function(e){
  e.preventDefault();
  var userId = $(this).data('id');
  var userName = $(this).data('name') || '';
  $('#demote_user_id').val(userId);
  $('#demote_user_name').text(userName);
  $('#demoteModal').modal('show');
});

$(document).on('click', '#confirmDemoteBtn', function(e){
  e.preventDefault();
  var btn = $(this);
  var userId = $('#demote_user_id').val();
  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');

  $.ajax({
    type: 'POST',
    url: 'includes/users_demote.php',
    data: { id: userId },
    dataType: 'json',
    timeout: 10000,
    success: function(response){
      btn.prop('disabled', false).html('<i class="fa fa-user-times"></i> Quitar Admin');
      if(response.success){
        $('#demoteModal').modal('hide');
        // Mostrar alerta en página (si existe showPageAlert)
        if(typeof showPageAlert === 'function') {
          showPageAlert('success', response.message);
        } else {
          alert(response.message);
        }
        setTimeout(function(){ location.reload(); }, 900);
      } else {
        if(typeof showPageAlert === 'function') {
          showPageAlert('danger', response.message);
        } else {
          alert(response.message);
        }
      }
    },
    error: function(xhr, status, error){
      btn.prop('disabled', false).html('<i class="fa fa-user-times"></i> Quitar Admin');
      var msg = 'Error al despromover el usuario.';
      if(xhr.status === 403) msg = 'No autorizado. Inicie sesión como administrador.';
      else if(xhr.status === 500) msg = 'Error del servidor (500). Revisa el log.';
      if(typeof showPageAlert === 'function') showPageAlert('danger', msg);
      else alert(msg);
    }
  });
});
</script>

</body>
</html>