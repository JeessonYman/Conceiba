<?php
include '../includes/session.php';
$path_prefix = '../../';
include '../includes/header.php';
?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include '../includes/navbar.php'; ?>
        <?php include '../includes/menubar.php'; ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1><i class="fa fa-magic"></i> Automatizaciones</h1>
                <ol class="breadcrumb">
                    <li><a href="../home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li>M.A.R.Í.A</li>
                    <li class="active">Automatizaciones</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                                    <i class="fa fa-plus"></i> Nueva Automatización
                                </a>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                $stmt = $conn->prepare("SELECT ma.*, CONCAT(u.firstname, ' ', u.lastname) AS created_by_name 
                                       FROM maria_automations ma 
                                       LEFT JOIN users u ON u.id = ma.created_by 
                                       ORDER BY ma.is_active DESC, ma.created_at DESC");
                                $stmt->execute();
                                ?>
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Trigger</th>
                                        <th>Acción</th>
                                        <th>Estado</th>
                                        <th>Última Ejecución</th>
                                        <th>Creado Por</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($stmt->rowCount() > 0) {
                                            foreach ($stmt as $row) {
                                                $status = $row['is_active'] ? '<span class="label label-success">Activa</span>' : '<span class="label label-default">Inactiva</span>';
                                                $last_run = $row['last_run'] ? date('d/m/Y H:i', strtotime($row['last_run'])) : 'Nunca';

                                                echo "
                          <tr>
                            <td data-label='ID'>" . $row['id'] . "</td>
                            <td data-label='Nombre'><strong>" . $row['name'] . "</strong><br><small class='text-muted'>" . $row['description'] . "</small></td>
                            <td data-label='Trigger'><span class='label label-primary'>" . ucfirst($row['trigger_type']) . "</span></td>
                            <td data-label='Acción'><span class='label label-info'>" . ucfirst($row['action_type']) . "</span></td>
                            <td data-label='Estado'>$status</td>
                            <td data-label='Última Ejecución'>$last_run</td>
                            <td data-label='Creado Por'>" . $row['created_by_name'] . "</td>
                            <td data-label='Acciones'>
                              <button class='btn btn-success btn-xs edit' data-id='" . $row['id'] . "'><i class='fa fa-edit'></i></button>
                              <button class='btn btn-danger btn-xs delete' data-id='" . $row['id'] . "'><i class='fa fa-trash'></i></button>
                            </td>
                          </tr>
                        ";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>No hay automatizaciones configuradas</td></tr>";
                                        }
                                        $pdo->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Plantillas de Automatización</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-box bg-aqua">
                                            <span class="info-box-icon"><i class="fa fa-envelope"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Alerta Stock Bajo</span>
                                                <span class="info-box-number">Email diario</span>
                                                <button class="btn btn-xs btn-default">Usar Plantilla</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="info-box bg-green">
                                            <span class="info-box-icon"><i class="fa fa-file-text"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Reporte Semanal</span>
                                                <span class="info-box-number">Lunes 8:00 AM</span>
                                                <button class="btn btn-xs btn-default">Usar Plantilla</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="info-box bg-yellow">
                                            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Auto-Reorden</span>
                                                <span class="info-box-number">Al alcanzar mínimo</span>
                                                <button class="btn btn-xs btn-default">Usar Plantilla</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
    <?php include '../includes/scripts.php'; ?>
</body>

</html>