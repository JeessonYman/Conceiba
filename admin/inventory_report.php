<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Reporte de Inventario</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Reporte Inventario</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <!-- Resumen -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-cubes"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Productos</span>
                                <span class="info-box-number">
                                    <?php
                                    $conn = $pdo->open();
                                    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM products");
                                    $stmt->execute();
                                    echo $stmt->fetch()['total'];
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Valor Total Inventario</span>
                                <span class="info-box-number">
                                    S/ <?php
                                        $stmt = $conn->prepare("SELECT SUM(cost * stock) AS total FROM products");
                                        $stmt->execute();
                                        echo number_format($stmt->fetch()['total'], 2);
                                        ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-warning"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Stock Bajo</span>
                                <span class="info-box-number">
                                    <?php
                                    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM products WHERE stock < stock_minimum");
                                    $stmt->execute();
                                    echo $stmt->fetch()['total'];
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-times-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sin Stock</span>
                                <span class="info-box-number">
                                    <?php
                                    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM products WHERE stock = 0");
                                    $stmt->execute();
                                    echo $stmt->fetch()['total'];
                                    $pdo->close();
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Detalle de Inventario</h3>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-success btn-sm btn-flat" onclick="window.print()">
                                        <span class="glyphicon glyphicon-print"></span> Imprimir
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                $stmt = $conn->prepare("SELECT p.*, c.name AS category_name, pr.name AS provider_name,
                                       (p.cost * p.stock) AS total_value
                                       FROM products p
                                       LEFT JOIN category c ON c.id = p.category_id
                                       LEFT JOIN provider pr ON pr.id = p.provider_id
                                       ORDER BY total_value DESC");
                                $stmt->execute();
                                ?>
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Proveedor</th>
                                        <th>Costo Unitario</th>
                                        <th>Stock</th>
                                        <th>Valor Total</th>
                                        <th>Estado</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($stmt as $row) {
                                            $status = $row['stock'] == 0 ? 'Sin Stock' : ($row['stock'] < $row['stock_minimum'] ? 'Bajo' : 'Normal');
                                            $status_class = $row['stock'] == 0 ? 'danger' : ($row['stock'] < $row['stock_minimum'] ? 'warning' : 'success');
                                            echo "
                        <tr>
                          <td data-label='Producto'>" . $row['name'] . "</td>
                          <td data-label='Categoría'>" . $row['category_name'] . "</td>
                          <td data-label='Proveedor'>" . $row['provider_name'] . "</td>
                          <td data-label='Costo'>S/ " . number_format($row['cost'], 2) . "</td>
                          <td data-label='Stock'>" . $row['stock'] . "</td>
                          <td data-label='Valor Total'>S/ " . number_format($row['total_value'], 2) . "</td>
                          <td data-label='Estado'><span class='label label-$status_class'>$status</span></td>
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