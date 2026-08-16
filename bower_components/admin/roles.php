<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Gestión de Roles</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Roles</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <a href="#addnew" data-bs-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                                    <i class="fa fa-plus"></i> Nuevo Rol
                                </a>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                $stmt = $conn->prepare("SELECT r.*, COUNT(ur.id) AS users_count 
                                       FROM roles r 
                                       LEFT JOIN user_roles ur ON ur.role_id = r.id 
                                       GROUP BY r.id 
                                       ORDER BY r.id ASC");
                                $stmt->execute();
                                ?>
                                <table id="example1" class="table table-bordered">
                                    <thead>
<tr>
                                        <th>ID</th>
                                        <th>Nombre Rol</th>
                                        <th>Descripción</th>
                                        <th>Usuarios Asignados</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
</thead>
                                    <tbody>
                                        <?php
                                        foreach ($stmt as $row) {
                                            echo "
                        <tr>
                          <td data-label='ID'>" . $row['id'] . "</td>
                          <td data-label='Nombre'><strong>" . $row['name'] . "</strong></td>
                          <td data-label='Descripción'>" . $row['description'] . "</td>
                          <td data-label='Usuarios'><span class='badge bg-blue'>" . $row['users_count'] . "</span></td>
                          <td data-label='Fecha'>" . date('d/m/Y', strtotime($row['created_at'])) . "</td>
                          <td data-label='Acciones'>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['id'] . "'>
                              <i class='fa fa-edit'></i> Editar
                            </button>
                            <button class='btn btn-info btn-sm permissions btn-flat' data-id='" . $row['id'] . "'>
                              <i class='fa fa-key'></i> Permisos
                            </button>
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
    </div>
    <?php include 'includes/scripts.php'; ?>
</body>

</html>