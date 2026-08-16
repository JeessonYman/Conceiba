<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Soporte Técnico</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Soporte</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <a href="#addnew" data-bs-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                                    <i class="fa fa-plus"></i> Nuevo Ticket
                                </a>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                $stmt = $conn->prepare("SELECT st.*, CONCAT(u.firstname, ' ', u.lastname) AS user_name 
                                       FROM support_tickets st 
                                       LEFT JOIN users u ON u.id = st.user_id 
                                       ORDER BY st.created_at DESC");
                                $stmt->execute();
                                ?>
                                <table id="example1" class="table table-bordered">
                                    <thead>
<tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Asunto</th>
                                        <th>Estado</th>
                                        <th>Prioridad</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
</thead>
                                    <tbody>
                                        <?php
                                        if ($stmt->rowCount() > 0) {
                                            foreach ($stmt as $row) {
                                                $status_class = [
                                                    'open' => 'primary',
                                                    'in_progress' => 'warning',
                                                    'resolved' => 'success',
                                                    'closed' => 'default'
                                                ][$row['status']];

                                                $priority_class = [
                                                    'low' => 'info',
                                                    'medium' => 'primary',
                                                    'high' => 'warning',
                                                    'urgent' => 'danger'
                                                ][$row['priority']];

                                                echo "
                          <tr>
                            <td data-label='ID'>#" . $row['id'] . "</td>
                            <td data-label='Usuario'>" . $row['user_name'] . "</td>
                            <td data-label='Asunto'>" . $row['subject'] . "</td>
                            <td data-label='Estado'><span class='label label-$status_class'>" . str_replace('_', ' ', $row['status']) . "</span></td>
                            <td data-label='Prioridad'><span class='label label-$priority_class'>" . ucfirst($row['priority']) . "</span></td>
                            <td data-label='Fecha'>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>
                            <td data-label='Acciones'>
                              <button class='btn btn-info btn-sm btn-flat view' data-id='" . $row['id'] . "'>
                                <i class='fa fa-eye'></i> Ver
                              </button>
                            </td>
                          </tr>
                        ";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>No hay tickets de soporte</td></tr>";
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