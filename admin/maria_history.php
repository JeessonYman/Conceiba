<?php
include 'includes/session.php';
$path_prefix = '../';
include 'includes/header.php';
?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1><i class="fa fa-history"></i> Historial de Conversaciones</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li>M.A.R.Í.A</li>
                    <li class="active">Historial</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Todas las Conversaciones</h3>
                                <div class="pull-right">
                                    <button class="btn btn-danger btn-sm btn-flat" onclick="confirm('¿Eliminar todo el historial?')">
                                        <i class="fa fa-trash"></i> Limpiar Historial
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                $stmt = $conn->prepare("SELECT mc.*, CONCAT(u.firstname, ' ', u.lastname) AS user_name 
                                       FROM maria_conversations mc 
                                       LEFT JOIN users u ON u.id = mc.user_id 
                                       ORDER BY mc.created_at DESC 
                                       LIMIT 50");
                                $stmt->execute();
                                ?>
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Mensaje</th>
                                        <th>Respuesta</th>
                                        <th>Fecha</th>
                                        <th>Sesión</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($stmt->rowCount() > 0) {
                                            foreach ($stmt as $row) {
                                                $message_preview = strlen($row['message']) > 50 ? substr($row['message'], 0, 50) . '...' : $row['message'];
                                                $response_preview = strlen($row['response']) > 50 ? substr($row['response'], 0, 50) . '...' : $row['response'];

                                                echo "
                          <tr>
                            <td data-label='ID'>" . $row['id'] . "</td>
                            <td data-label='Usuario'>" . $row['user_name'] . "</td>
                            <td data-label='Mensaje'>" . $message_preview . "</td>
                            <td data-label='Respuesta'>" . $response_preview . "</td>
                            <td data-label='Fecha'>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>
                            <td data-label='Sesión'>" . substr($row['session_id'], 0, 8) . "</td>
                          </tr>
                        ";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>No hay conversaciones en el historial</td></tr>";
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