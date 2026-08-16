<?php
include 'includes/session.php';
include 'includes/format.php';
?>
<?php
$today = date('Y-m-d');
$year = date('Y');
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}

$conn = $pdo->open();
?>
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
          Escritorio
        </h1>
        <ol class="breadcrumb">
          <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
          <li class="active">Tablero</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <?php
        if (isset($_SESSION['error'])) {
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-bs-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              " . $_SESSION['error'] . "
            </div>
          ";
          unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-bs-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              " . $_SESSION['success'] . "
            </div>
          ";
          unset($_SESSION['success']);
        }
        ?>

        <!-- Controles globales para filtros de gráficos (período/fecha/año) -->
        <div class="row" style="margin-bottom:10px;">
          <div class="col-md-12">
            <div class="d-flex flex-wrap align-items-center justify-content-md-end gap-2">
              <label class="mb-0">Periodo:</label>
              <select id="maria_period" class="form-control input-sm" style="width:auto;">
                <option value="day">Día</option>
                <option value="week">Semana</option>
                <option value="month" selected>Mes</option>
                <option value="year">Año</option>
              </select>
              <label class="mb-0">Fecha:</label>
              <input type="date" id="maria_date" class="form-control input-sm" style="width:auto;" value="<?php echo date('Y-m-d'); ?>">
              <label class="mb-0">Año:</label>
              <select id="maria_year" class="form-control input-sm" style="width:auto;">
                <?php for ($i = 2015; $i <= 2065; $i++) {
                  $sel = ($i == $year) ? 'selected' : '';
                  echo "<option value='$i' $sel>$i</option>";
                } ?>
              </select>
              <button id="maria_apply" class="btn btn-primary btn-sm">Aplicar</button>
            </div>
          </div>
        </div>

        <!-- Cajas de estadísticas -->
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-aqua">
              <div class="inner">
                <?php
                $stmt = $conn->prepare("SELECT * FROM details LEFT JOIN products ON products.id=details.product_id");
                $stmt->execute();
                $total = 0;
                foreach ($stmt as $srow) {
                  $subtotal = $srow['price'] * $srow['quantity'];
                  $total += $subtotal;
                }
                echo "<h3>S/ " . number_format_short($total, 2) . "</h3>";
                ?>
                <p>Ventas totales</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-cart"></i>
              </div>
              <a href="sales.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-green">
              <div class="inner">
                <?php
                $stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM products");
                $stmt->execute();
                $prow = $stmt->fetch();
                echo "<h3>" . $prow['numrows'] . "</h3>";
                ?>
                <p>Número de productos</p>
              </div>
              <div class="icon">
                <i class="fa fa-barcode"></i>
              </div>
              <a href="products.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-yellow">
              <div class="inner">
                <?php
                $stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users");
                $stmt->execute();
                $urow = $stmt->fetch();
                echo "<h3>" . $urow['numrows'] . "</h3>";
                ?>
                <p>Número de usuarios</p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
              <a href="users.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-red">
              <div class="inner">
                <?php
                $stmt = $conn->prepare("SELECT * FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id WHERE sales_date=:sales_date");
                $stmt->execute(['sales_date' => $today]);
                $total = 0;
                foreach ($stmt as $trow) {
                  $subtotal = $trow['price'] * $trow['quantity'];
                  $total += $subtotal;
                }
                echo "<h3>S/ " . number_format_short($total, 2) . "</h3>";
                ?>
                <p>Ventas hoy</p>
              </div>
              <div class="icon">
                <i class="fa fa-money"></i>
              </div>
              <a href="sales.php" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Gráfico de Ventas Mensuales -->
        <div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Informe mensual de ventas</h3>
                <div class="box-tools float-end d-flex align-items-center gap-2 flex-wrap">
                  <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('el informe mensual de ventas')">
                    <i class="fa fa-comment-dots"></i> Consultar a Maria
                  </button>
                  <form class="form-inline mb-0">
                    <div class="form-group mb-0">
                      <label>Seleccione el año: </label>
                      <select class="form-control input-sm" id="select_year">
                        <?php
                        for ($i = 2015; $i <= 2065; $i++) {
                          $selected = ($i == $year) ? 'selected' : '';
                          echo "<option value='" . $i . "' " . $selected . ">" . $i . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </form>
                </div>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="barChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Productos más vendidos y Ventas por día -->
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="box box-success">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Top 10 Productos Más Vendidos</h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('Top 10 Productos Más Vendidos')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="topProductsChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-sm-12">
            <div class="box box-info">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Ventas Últimos 7 Días</h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('Ventas Últimos 7 Días')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="dailySalesChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Ingresos por mes y Comparativa año actual vs anterior -->
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="box box-warning">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Ingresos de Productos por Mes (<?php echo $year; ?>)</h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('ingresos de productos por mes')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="inputsChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-sm-12">
            <div class="box box-danger">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Comparativa Ventas: <?php echo $year; ?> vs <?php echo ($year - 1); ?></h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('la comparativa de ventas entre años')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="compareYearsChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Ventas por categoría y Productos con bajo stock -->
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="box box-primary">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Ventas por Categoría</h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('las ventas por categoría')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="categoryChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-sm-12">
            <div class="box box-danger">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Productos con Stock Bajo</h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('los productos con stock bajo')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $stmt = $conn->prepare("SELECT * FROM products WHERE stock <= stock_minimum ORDER BY stock ASC LIMIT 10");
                      $stmt->execute();
                      foreach ($stmt as $row) {
                        echo "
                          <tr>
                            <td>" . $row['name'] . "</td>
                            <td><span class='badge bg-red'>" . $row['stock'] . "</span></td>
                            <td>" . $row['stock_minimum'] . "</td>
                          </tr>
                        ";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Comparativa de ventas por día, mes y año -->
        <div class="row">
          <div class="col-md-6">
            <div class="box box-info">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Ventas últimos 30 días</h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('las ventas de los últimos 30 días')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="last30DaysChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="box box-warning">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Ventas por Año</h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('las ventas por año')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="yearlySalesChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Ranking de productos más vistos -->
        <div class="row">
          <div class="col-md-12">
            <div class="box box-success">
              <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="box-title mb-0">Top 10 Productos Más Vistos</h3>
                <button type="button" class="btn btn-sm btn-accent" onclick="preguntarMariaSobreGrafico('los productos más vistos')">
                  <i class="fa fa-comment-dots"></i> Consultar a Maria
                </button>
              </div>
              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="viewsChart" style="height:300px"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>


      </section>
    </div>

    <?php include 'includes/footer.php'; ?>

  </div>

  <!-- Datos para gráficos -->
  <?php
  // Datos para gráfico mensual
  $months = array();
  $sales = array();
  for ($m = 1; $m <= 12; $m++) {
    try {
      $stmt = $conn->prepare("SELECT * FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id WHERE MONTH(sales_date)=:month AND YEAR(sales_date)=:year");
      $stmt->execute(['month' => $m, 'year' => $year]);
      $total = 0;
      foreach ($stmt as $srow) {
        $subtotal = $srow['price'] * $srow['quantity'];
        $total += $subtotal;
      }
      array_push($sales, round($total, 2));
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    $num = str_pad($m, 2, 0, STR_PAD_LEFT);
    $month = date('M', mktime(0, 0, 0, $m, 1));
    array_push($months, $month);
  }

  // Top 10 productos más vendidos
  $stmt = $conn->prepare("SELECT products.name, SUM(details.quantity) as total_sold FROM details LEFT JOIN products ON products.id=details.product_id GROUP BY details.product_id ORDER BY total_sold DESC LIMIT 10");
  $stmt->execute();
  $topProducts = array();
  $topProductsSales = array();
  foreach ($stmt as $row) {
    array_push($topProducts, $row['name']);
    array_push($topProductsSales, $row['total_sold']);
  }

  // Ventas últimos 7 días
  $dailyLabels = array();
  $dailySales = array();
  for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stmt = $conn->prepare("SELECT * FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id WHERE sales_date=:date");
    $stmt->execute(['date' => $date]);
    $total = 0;
    foreach ($stmt as $row) {
      $total += $row['price'] * $row['quantity'];
    }
    array_push($dailyLabels, date('d/m', strtotime($date)));
    array_push($dailySales, round($total, 2));
  }

  // Ingresos por mes
  $inputsData = array();
  for ($m = 1; $m <= 12; $m++) {
    $stmt = $conn->prepare("SELECT SUM(total) as total FROM inputs WHERE MONTH(entry_date)=:month AND YEAR(entry_date)=:year");
    $stmt->execute(['month' => $m, 'year' => $year]);
    $row = $stmt->fetch();
    array_push($inputsData, $row['total'] ? round($row['total'], 2) : 0);
  }

  // Comparativa años
  $currentYearSales = array();
  $lastYearSales = array();
  for ($m = 1; $m <= 12; $m++) {
    $stmt = $conn->prepare("SELECT * FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id WHERE MONTH(sales_date)=:month AND YEAR(sales_date)=:year");
    $stmt->execute(['month' => $m, 'year' => $year]);
    $total = 0;
    foreach ($stmt as $row) {
      $total += $row['price'] * $row['quantity'];
    }
    array_push($currentYearSales, round($total, 2));

    $stmt->execute(['month' => $m, 'year' => ($year - 1)]);
    $total = 0;
    foreach ($stmt as $row) {
      $total += $row['price'] * $row['quantity'];
    }
    array_push($lastYearSales, round($total, 2));
  }

  // Ventas por categoría
  $stmt = $conn->prepare("SELECT category.name, SUM(details.quantity * products.price) as total FROM details LEFT JOIN products ON products.id=details.product_id LEFT JOIN category ON category.id=products.category_id GROUP BY products.category_id");
  $stmt->execute();
  $categories = array();
  $categorySales = array();
  foreach ($stmt as $row) {
    array_push($categories, $row['name']);
    array_push($categorySales, round($row['total'], 2));
  }

  $dailySalesFull = [];
  $dailyDates = [];
  for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stmt = $conn->prepare("SELECT SUM(products.price * details.quantity) AS total
FROM details 
LEFT JOIN sales ON sales.id = details.sales_id 
LEFT JOIN products ON products.id = details.product_id 
WHERE sales_date = :date
");
    $stmt->execute(['date' => $date]);
    $row = $stmt->fetch();
    array_push($dailySalesFull, $row['total'] ? round($row['total'], 2) : 0);
    array_push($dailyDates, date('d/m', strtotime($date)));
  }

  // ====== Ventas por año (últimos 5 años) ======
  $yearlySales = [];
  $yearsList = [];
  for ($i = 4; $i >= 0; $i--) {
    $y = $year - $i;
    $stmt = $conn->prepare("SELECT SUM(products.price * details.quantity) AS total 
FROM details 
LEFT JOIN sales ON sales.id = details.sales_id 
LEFT JOIN products ON products.id = details.product_id 
WHERE YEAR(sales_date) = :year");
    $stmt->execute(['year' => $y]);
    $row = $stmt->fetch();
    array_push($yearlySales, $row['total'] ? round($row['total'], 2) : 0);
    array_push($yearsList, $y);
  }

  // ====== Productos más vistos (según campo 'views' o similar) ======
  $stmt = $conn->prepare("SELECT name, counter FROM products ORDER BY counter DESC LIMIT 10");
  $stmt->execute();
  $productViewsNames = [];
  $productViewsCount = [];
  foreach ($stmt as $row) {
    array_push($productViewsNames, $row['name']);
    array_push($productViewsCount, $row['counter']);
  }

  // Convertir a JSON
  $months = json_encode($months);
  $sales = json_encode($sales);
  $topProducts = json_encode($topProducts);
  $topProductsSales = json_encode($topProductsSales);
  $dailyLabels = json_encode($dailyLabels);
  $dailySales = json_encode($dailySales);
  $inputsData = json_encode($inputsData);
  $currentYearSales = json_encode($currentYearSales);
  $lastYearSales = json_encode($lastYearSales);
  $categories = json_encode($categories);
  $categorySales = json_encode($categorySales);
  $dailySalesFull = json_encode($dailySalesFull);
  $dailyDates = json_encode($dailyDates);
  $yearlySales = json_encode($yearlySales);
  $yearsList = json_encode($yearsList);
  $productViewsNames = json_encode($productViewsNames);
  $productViewsCount = json_encode($productViewsCount);
  ?>

  <?php $pdo->close(); ?>
  <?php include 'includes/scripts.php'; ?>

  <!-- Chart.js actualizado -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <script>
    $(function() {
      // Esperar que todo esté cargado
      setTimeout(function() {

        if (typeof Chart === 'undefined') {
          console.error('❌ Chart.js no está cargado');
          return;
        }

        // Configuración global (Chart.js 4)
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;
        Chart.defaults.plugins.legend.display = true;
        Chart.defaults.plugins.legend.position = 'bottom';

        // Colores base
        const colors = {
          primary: 'rgba(60,141,188,0.9)',
          success: 'rgba(0,166,90,0.9)',
          info: 'rgba(0,192,239,0.9)',
          warning: 'rgba(243,156,18,0.9)',
          danger: 'rgba(221,75,57,0.9)'
        };

        // Función genérica de configuración de escalas
        const basicScales = {
          y: {
            beginAtZero: true
          },
          x: {
            beginAtZero: true
          }
        };

        // ======================
        // 1. GRÁFICO DE VENTAS MENSUALES
        // ======================
        try {
          barChart = new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
              labels: <?php echo $months; ?>,
              datasets: [{
                label: 'Ventas (S/)',
                data: <?php echo $sales; ?>,
                backgroundColor: colors.primary
              }]
            },
            options: {
              scales: basicScales
            }
          });
        } catch (e) {
          console.error('Error en ventas mensuales', e);
        }

        // ======================
        // 2. TOP PRODUCTOS MÁS VENDIDOS
        // ======================
        try {
          topProductsChart = new Chart(document.getElementById('topProductsChart'), {
            type: 'bar',
            data: {
              labels: <?php echo $topProducts; ?>,
              datasets: [{
                label: 'Cantidad Vendida',
                data: <?php echo $topProductsSales; ?>,
                backgroundColor: colors.success
              }]
            },
            options: {
              indexAxis: 'y',
              scales: basicScales,
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          });
        } catch (e) {
          console.error('Error top productos', e);
        }

        // ======================
        // 3. VENTAS ÚLTIMOS 7 DÍAS
        // ======================
        try {
          dailySalesChart = new Chart(document.getElementById('dailySalesChart'), {
            type: 'line',
            data: {
              labels: <?php echo $dailyLabels; ?>,
              datasets: [{
                label: 'Ventas Diarias (S/)',
                data: <?php echo $dailySales; ?>,
                borderColor: colors.info,
                backgroundColor: 'rgba(0,192,239,0.2)',
                borderWidth: 2,
                fill: true
              }]
            },
            options: {
              scales: basicScales
            }
          });
        } catch (e) {
          console.error('Error ventas 7 días', e);
        }

        // ======================
        // 4. INGRESOS POR MES
        // ======================
        try {
          inputsChart = new Chart(document.getElementById('inputsChart'), {
            type: 'bar',
            data: {
              labels: <?php echo $months; ?>,
              datasets: [{
                label: 'Ingresos (S/)',
                data: <?php echo $inputsData; ?>,
                backgroundColor: colors.warning
              }]
            },
            options: {
              scales: basicScales
            }
          });
        } catch (e) {
          console.error('Error ingresos', e);
        }

        // ======================
        // 5. COMPARATIVA AÑOS
        // ======================
        try {
          compareYearsChart = new Chart(document.getElementById('compareYearsChart'), {
            type: 'line',
            data: {
              labels: <?php echo $months; ?>,
              datasets: [{
                  label: '<?php echo $year; ?>',
                  borderColor: colors.primary,
                  backgroundColor: 'rgba(60,141,188,0.2)',
                  borderWidth: 2,
                  data: <?php echo $currentYearSales; ?>,
                  fill: false
                },
                {
                  label: '<?php echo ($year - 1); ?>',
                  borderColor: colors.danger,
                  backgroundColor: 'rgba(221,75,57,0.2)',
                  borderWidth: 2,
                  data: <?php echo $lastYearSales; ?>,
                  fill: false
                }
              ]
            },
            options: {
              scales: basicScales
            }
          });
        } catch (e) {
          console.error('Error comparativa', e);
        }

        // ======================
        // 6. VENTAS POR CATEGORÍA
        // ======================
        try {
          categoryChart = new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
              labels: <?php echo $categories; ?>,
              datasets: [{
                data: <?php echo $categorySales; ?>,
                backgroundColor: [
                  colors.primary,
                  colors.success,
                  colors.info,
                  colors.warning,
                  colors.danger,
                  'rgba(111,84,153,0.9)',
                  'rgba(0,140,186,0.9)'
                ]
              }]
            }
          });
        } catch (e) {
          console.error('Error categoría', e);
        }

        // ======================
        // 7. VENTAS ÚLTIMOS 30 DÍAS
        // ======================
        try {
          last30DaysChart = new Chart(document.getElementById('last30DaysChart'), {
            type: 'line',
            data: {
              labels: <?php echo $dailyDates; ?>,
              datasets: [{
                label: 'Ventas (S/)',
                data: <?php echo $dailySalesFull; ?>,
                borderColor: colors.primary,
                backgroundColor: 'rgba(60,141,188,0.2)',
                borderWidth: 2,
                fill: true
              }]
            },
            options: {
              scales: basicScales
            }
          });
        } catch (e) {
          console.error('Error gráfico 30 días', e);
        }

        // ======================
        // 8. VENTAS POR AÑO
        // ======================
        try {
          yearlySalesChart = new Chart(document.getElementById('yearlySalesChart'), {
            type: 'bar',
            data: {
              labels: <?php echo $yearsList; ?>,
              datasets: [{
                label: 'Ventas Anuales (S/)',
                data: <?php echo $yearlySales; ?>,
                backgroundColor: colors.success
              }]
            },
            options: {
              scales: basicScales
            }
          });
        } catch (e) {
          console.error('Error gráfico anual', e);
        }

        // ======================
        // 9. PRODUCTOS MÁS VISTOS
        // ======================
        try {
          viewsChart = new Chart(document.getElementById('viewsChart'), {
            type: 'bar',
            data: {
              labels: <?php echo $productViewsNames; ?>,
              datasets: [{
                label: 'Vistas',
                data: <?php echo $productViewsCount; ?>,
                backgroundColor: colors.info
              }]
            },
            options: {
              indexAxis: 'y',
              scales: basicScales,
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          });
        } catch (e) {
          console.error('Error gráfico vistas', e);
        }

        console.log('✅ Todos los gráficos cargados correctamente');

        // ======================
        // Funciones para recargar gráficos desde admin/maria/data.php
        // ======================
        function fetchData(type, extraParams) {
          extraParams = extraParams || {};
          var period = $('#maria_period').val();
          var date = $('#maria_date').val();
          var year = $('#maria_year').val();
          var params = 'period=' + encodeURIComponent(period) + '&date=' + encodeURIComponent(date) + '&year=' + encodeURIComponent(year);
          for (var k in extraParams) {
            params += '&' + encodeURIComponent(k) + '=' + encodeURIComponent(extraParams[k]);
          }
          return fetch('maria/data.php?type=' + encodeURIComponent(type) + '&' + params).then(function(r) {
            return r.json();
          });
        }

        function updateCharts() {
          // Actualizar cada gráfico consultando la API
          fetchData('monthly_year').then(function(j) {
            if (j.ok && typeof barChart !== 'undefined') {
              barChart.data.labels = j.labels;
              barChart.data.datasets[0].data = j.data;
              barChart.update();
            }
          });

          fetchData('top_products').then(function(j) {
            if (j.ok && typeof topProductsChart !== 'undefined') {
              topProductsChart.data.labels = j.labels;
              topProductsChart.data.datasets[0].data = j.data;
              topProductsChart.update();
            }
          });

          fetchData('daily_series').then(function(j) {
            if (j.ok) {
              if (typeof dailySalesChart !== 'undefined') {
                dailySalesChart.data.labels = j.labels;
                dailySalesChart.data.datasets[0].data = j.data;
                dailySalesChart.update();
              }
              if (typeof last30DaysChart !== 'undefined') {
                last30DaysChart.data.labels = j.labels;
                last30DaysChart.data.datasets[0].data = j.data;
                last30DaysChart.update();
              }
            }
          });

          fetchData('category_sales').then(function(j) {
            if (j.ok && typeof categoryChart !== 'undefined') {
              categoryChart.data.labels = j.labels;
              categoryChart.data.datasets[0].data = j.data;
              categoryChart.update();
            }
          });

          fetchData('compare_years').then(function(j) {
            if (j.ok && typeof compareYearsChart !== 'undefined') {
              compareYearsChart.data.labels = j.labels;
              compareYearsChart.data.datasets[0].data = j.current;
              compareYearsChart.data.datasets[1].data = j.previous;
              compareYearsChart.update();
            }
          });

          fetchData('yearly_series').then(function(j) {
            if (j.ok && typeof yearlySalesChart !== 'undefined') {
              yearlySalesChart.data.labels = j.labels;
              yearlySalesChart.data.datasets[0].data = j.data;
              yearlySalesChart.update();
            }
          });

          fetchData('views_top').then(function(j) {
            if (j.ok && typeof viewsChart !== 'undefined') {
              viewsChart.data.labels = j.labels;
              viewsChart.data.datasets[0].data = j.data;
              viewsChart.update();
            }
          });
        }

        // Cuando el usuario pulse aplicar
        $('#maria_apply').click(function() {
          updateCharts();
        });
      }, 400);

      // Selector de año
      $('#select_year').change(function() {
        window.location.href = 'home.php?year=' + $(this).val();
      });

      // ======================
      // CAMBIO DE TEMA
      // ======================
      const themeToggle = document.getElementById('theme-toggle');
      const themeIcon = document.getElementById('theme-icon');

      if (themeToggle && themeIcon) {
        const currentTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', currentTheme);

        const updateIcon = (theme) => {
          themeIcon.className = theme === 'dark' ? 'fa fa-sun' : 'fa fa-moon';
        };
        updateIcon(currentTheme);

        themeToggle.addEventListener('click', () => {
          const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
          document.documentElement.setAttribute('data-theme', newTheme);
          localStorage.setItem('theme', newTheme);
          updateIcon(newTheme);
        });
      }
    });
  </script>
  <style>
    /* Estilos responsivos adicionales */
    @media (max-width: 767px) {
      .small-box h3 {
        font-size: 28px !important;
      }

      .small-box p {
        font-size: 12px !important;
      }

      .box-title {
        font-size: 16px !important;
      }

      .chart-responsive {
        overflow-x: auto;
      }

      .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
    }

    @media (max-width: 480px) {
      .small-box h3 {
        font-size: 22px !important;
      }

      .content-header h1 {
        font-size: 22px !important;
      }
    }

    /* Mejorar visualización en tablets */
    @media (min-width: 768px) and (max-width: 991px) {
      .col-sm-12 {
        margin-bottom: 20px;
      }
    }
  </style>

  <script>
  // Botón "Consultar a Maria" junto a los gráficos del dashboard:
  // abre el chat y envía directamente una pregunta sobre ese gráfico.
  function preguntarMariaSobreGrafico(nombreGrafico) {
      const input = document.getElementById('maria-global-input');
      if (!input) return;

      if (typeof toggleMariaWidget === 'function') {
          const panel = document.getElementById('maria-chat-window');
          if (panel && panel.classList.contains('hidden')) {
              toggleMariaWidget();
          }
      }

      setTimeout(function () {
          input.value = '¿Qué representa ' + nombreGrafico + ' y qué me recomiendas hacer según estos datos?';
          if (typeof sendMariaGlobalMessage === 'function') {
              sendMariaGlobalMessage();
          }
      }, 300);
  }
  </script>

</body>

</html>