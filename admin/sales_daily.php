<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">

        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <h1>
                    Ventas Diarias
                </h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Ventas Diarias</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="pull-right">
                                    <form method="POST" class="form-inline">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="date" class="form-control" id="filter_date" name="filter_date" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm btn-flat" name="filter">
                                            <span class="glyphicon glyphicon-filter"></span> Filtrar
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm btn-flat" onclick="window.print()">
                                            <span class="glyphicon glyphicon-print"></span> Imprimir
                                        </button>
                                    </form>
                                </div>
                                <h3 class="box-title">
                                    Ventas del día: <?php echo date('d/m/Y', strtotime(isset($_POST['filter_date']) ? $_POST['filter_date'] : date('Y-m-d'))); ?>
                                </h3>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                $filter_date = isset($_POST['filter_date']) ? $_POST['filter_date'] : date('Y-m-d');
                                $total_daily = 0;

                                try {
                                    $stmt = $conn->prepare("SELECT s.*, CONCAT(u.firstname, ' ', u.lastname) AS buyer_name 
                                         FROM sales s 
                                         LEFT JOIN users u ON u.id=s.user_id 
                                         WHERE s.sales_date = :filter_date 
                                         ORDER BY s.id DESC");
                                    $stmt->execute(['filter_date' => $filter_date]);
                                ?>
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <th>Hora</th>
                                            <th>Comprador</th>
                                            <th>Transacción#</th>
                                            <th>Monto</th>
                                            <th>Detalles</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($stmt as $row) {
                                                $stmt2 = $conn->prepare("SELECT * FROM details d 
                                              LEFT JOIN products p ON p.id=d.product_id 
                                              WHERE d.sales_id=:id");
                                                $stmt2->execute(['id' => $row['id']]);
                                                $total = 0;
                                                foreach ($stmt2 as $details) {
                                                    $subtotal = $details['price'] * $details['quantity'];
                                                    $total += $subtotal;
                                                }
                                                $total_daily += $total;

                                                echo "
                        <tr>
                          <td data-label='Hora'>" . date('H:i', strtotime($row['sales_date'])) . "</td>
                          <td data-label='Comprador'>" . $row['buyer_name'] . "</td>
                          <td data-label='Transacción'>" . $row['pay_id'] . "</td>
                          <td data-label='Monto'>S/ " . number_format($total, 2) . "</td>
                          <td data-label='Detalles'>
                            <button type='button' class='btn btn-info btn-sm btn-flat transact' data-id='" . $row['id'] . "'>
                              <i class='fa fa-search'></i> Ver
                            </button>
                          </td>
                        </tr>
                      ";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Total del Día:</th>
                                                <th>S/ <?php echo number_format($total_daily, 2); ?></th>
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
            $(document).on('click', '.transact', function(e) {
                e.preventDefault();
                $('#transaction').modal('show');
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'transact.php',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#date').html(response.date);
                        $('#transid').html(response.transaction);
                        $('#detail').prepend(response.list);
                        $('#total').html(response.total);
                    }
                });
            });

            $("#transaction").on("hidden.bs.modal", function() {
                $('.prepend_items').remove();
            });
        });
    </script>
</body>

</html>