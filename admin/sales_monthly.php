<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Ventas Mensuales</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Ventas Mensuales</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="float-end">
                                    <form method="POST" class="form-inline">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <select name="filter_month" class="form-control">
                                                <?php for ($i = 1; $i <= 12; $i++) {
                                                    $selected = (isset($_POST['filter_month']) && $_POST['filter_month'] == $i) || (!isset($_POST['filter_month']) && $i == date('n')) ? 'selected' : '';
                                                    echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
                                                } ?>
                                            </select>
                                            <select name="filter_year" class="form-control">
                                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) {
                                                    $selected = (isset($_POST['filter_year']) && $_POST['filter_year'] == $y) || (!isset($_POST['filter_year']) && $y == date('Y')) ? 'selected' : '';
                                                    echo "<option value='$y' $selected>$y</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm btn-flat" name="filter"><span class="bi bi-filter"></span> Filtrar</button>
                                        <button type="button" class="btn btn-success btn-sm btn-flat" onclick="window.print()"><span class="bi bi-print"></span> Imprimir</button>
                                    </form>
                                </div>
                                <h3 class="box-title">
                                    Ventas de: <?php
                                                $month = isset($_POST['filter_month']) ? $_POST['filter_month'] : date('n');
                                                $year = isset($_POST['filter_year']) ? $_POST['filter_year'] : date('Y');
                                                echo date('F Y', mktime(0, 0, 0, $month, 1, $year));
                                                ?>
                                </h3>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                $total_monthly = 0;
                                try {
                                    $stmt = $conn->prepare("SELECT s.*, CONCAT(u.firstname, ' ', u.lastname) AS buyer_name 
                                         FROM sales s 
                                         LEFT JOIN users u ON u.id=s.user_id 
                                         WHERE MONTH(s.sales_date) = :month AND YEAR(s.sales_date) = :year 
                                         ORDER BY s.sales_date DESC");
                                    $stmt->execute(['month' => $month, 'year' => $year]);
                                ?>
                                    <table id="example1" class="table table-bordered">
                                        <thead>
<tr>
                                            <th>Fecha</th>
                                            <th>Comprador</th>
                                            <th>Transacción#</th>
                                            <th>Monto</th>
                                            <th>Detalles</th>
                                        </tr>
</thead>
                                        <tbody>
                                            <?php
                                            foreach ($stmt as $row) {
                                                $stmt2 = $conn->prepare("SELECT * FROM details d LEFT JOIN products p ON p.id=d.product_id WHERE d.sales_id=:id");
                                                $stmt2->execute(['id' => $row['id']]);
                                                $total = 0;
                                                foreach ($stmt2 as $details) {
                                                    $total += $details['price'] * $details['quantity'];
                                                }
                                                $total_monthly += $total;
                                                echo "
                        <tr>
                          <td data-label='Fecha'>" . date('d/m/Y', strtotime($row['sales_date'])) . "</td>
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
                                                <th colspan="3" class="text-right">Total del Mes:</th>
                                                <th>S/ <?php echo number_format($total_monthly, 2); ?></th>
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
        <?php include '../includes/profile_modal.php'; ?>
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