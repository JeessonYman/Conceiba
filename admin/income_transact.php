<?php
include 'includes/session.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$output = array('list'=>'', 'transaction'=>'', 'date'=>'', 'total'=>'');

if(!$id){
    echo json_encode($output);
    exit();
}

$conn = $pdo->open();

$stmt = $conn->prepare("SELECT di.id AS detail_id, i.id AS input_id, i.code AS input_code, i.entry_date AS entry_date, di.price AS unit_price, di.quantity AS quantity, pr.name AS product_name FROM details_inputs di LEFT JOIN inputs i ON di.id_inputs = i.id LEFT JOIN products pr ON pr.id = di.id_products WHERE i.id = :id");
$stmt->execute(['id'=>$id]);

$total = 0;
foreach($stmt as $row){
    $output['transaction'] = (!empty($row['input_code']) ? htmlspecialchars($row['input_code']) : 'INV-' . $row['input_id']);
    $output['date'] = date('M d, Y', strtotime($row['entry_date']));
    $subtotal = $row['unit_price'] * $row['quantity'];
    $total += $subtotal;
    $output['list'] .= "\n\t<tr class='prepend_items'>\n\t\t<td>".htmlspecialchars($row['product_name'])."</td>\n\t\t<td>S/ ".number_format($row['unit_price'],2)."</td>\n\t\t<td>".intval($row['quantity'])."</td>\n\t\t<td>S/ ".number_format($subtotal,2)."</td>\n\t</tr>\n";
}

$output['total'] = '<b>S/ '.number_format($total, 2).'<b>';
$pdo->close();

echo json_encode($output);
?>