<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Ingresos por Categorías</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Ingresos por Categorías</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="pull-right">
                                    <form method="POST" class="form-inline">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" class="form-control" id="reservation" name="date_range">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm btn-flat" name="filter"><span class="glyphicon glyphicon-filter"></span> Filtrar</button>
                                        <button type="button" class="btn btn-success btn-sm btn-flat" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Imprimir</button>
                                    </form>
                                </div>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                try {
                                    $stmt = $conn->prepare("SELECT c.name AS category_name, c.id AS category_id,
                                         COUNT(di.id) AS total_items,
                                         SUM(di.quantity) AS total_quantity,
                                         SUM(di.price * di.quantity) AS total_amount
                                         FROM category c
                                         LEFT JOIN products pr ON pr.category_id = c.id
                                         LEFT JOIN details_inputs di ON di.id_products = pr.id
                                         LEFT JOIN inputs i ON i.id = di.id_inputs
                                         GROUP BY c.id, c.name
                                         ORDER BY total_amount DESC");
                                    $stmt->execute();
                                ?>
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <th>Categoría</th>
                                            <th>Total Items</th>
                                            <th>Total Cantidad</th>
                                            <th>Monto Total</th>
                                            <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $grand_total = 0;
                                            foreach ($stmt as $row) {
                                                $grand_total += $row['total_amount'];
                                                echo "
                        <tr>
                          <td data-label='Categoría'>" . $row['category_name'] . "</td>
                          <td data-label='Total Items'>" . $row['total_items'] . "</td>
                          <td data-label='Total Cantidad'>" . $row['total_quantity'] . "</td>
                          <td data-label='Monto Total'>S/ " . number_format($row['total_amount'], 2) . "</td>
                          <td data-label='Acciones'>
                            <button type='button' class='btn btn-info btn-sm btn-flat details' data-id='" . $row['category_id'] . "'>
                              <i class='fa fa-eye'></i> Ver Detalle
                            </button>
                          </td>
                        </tr>
                      ";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Total General:</th>
                                                <th>S/ <?php echo number_format($grand_total, 2); ?></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                <?php
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }
                                $pdo->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script>
        $(function() {
            $('#reservation').daterangepicker();
        });
    </script>
</body>

</html>