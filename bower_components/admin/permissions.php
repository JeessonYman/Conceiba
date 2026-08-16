<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Gestión de Permisos</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Permisos</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <a href="#addnew" data-bs-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                                    <i class="fa fa-plus"></i> Nuevo Permiso
                                </a>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                $stmt = $conn->prepare("SELECT * FROM permissions ORDER BY module ASC, action ASC");
                                $stmt->execute();

                                $permissions_by_module = [];
                                foreach ($stmt as $row) {
                                    $permissions_by_module[$row['module']][] = $row;
                                }
                                $pdo->close();
                                ?>

                                <?php foreach ($permissions_by_module as $module => $perms): ?>
                                    <h4 class="text-primary"><i class="fa fa-folder"></i> Módulo: <?php echo ucfirst($module); ?></h4>
                                    <table class="table table-bordered table-striped">
                                        <thead>
<tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Acción</th>
                                            <th>Opciones</th>
                                        </tr>
</thead>
                                        <tbody>
                                            <?php foreach ($perms as $perm): ?>
                                                <tr>
                                                    <td data-label='ID'><?php echo $perm['id']; ?></td>
                                                    <td data-label='Nombre'><?php echo $perm['name']; ?></td>
                                                    <td data-label='Descripción'><?php echo $perm['description']; ?></td>
                                                    <td data-label='Acción'><span class='label label-primary'><?php echo $perm['action']; ?></span></td>
                                                    <td data-label='Opciones'>
                                                        <button class='btn btn-success btn-sm edit btn-flat' data-id='<?php echo $perm['id']; ?>'>
                                                            <i class='fa fa-edit'></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <br>
                                <?php endforeach; ?>
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