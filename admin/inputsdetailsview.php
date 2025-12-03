<?php
	include 'includes/session.php';

	$id = $_POST['id'];

	$conn = $pdo->open();

	$output = array('list'=>'');

	$stmt = $conn->prepare("SELECT *,
							inputs.id AS inputsid
                            FROM details_inputs
                            LEFT JOIN products ON products.id=details_inputs.id_products
                            LEFT JOIN inputs ON inputs.id=details_inputs.id_inputs
                            WHERE details_inputs.id_inputs=:id");
	$stmt->execute(['id'=>$id]);

	$total = 0;
	foreach($stmt as $row){
		$output['inputsid'] = $row['id'];
		$output['date'] = date('M d, Y', strtotime($row['entry_date']));
		$subtotal = $row['price']*$row['quantity'];
		$total += $subtotal;
		$output['list'] .= "
			<tr class='prepend_items'>
				<td>".$row['name']."</td>
				<td>S/ ".number_format($row['price'], 2)."</td>
				<td>".$row['quantity']."</td>
				<td>S/ ".number_format($subtotal, 2)."</td>
			</tr>
		";
	}
	
	$output['total'] = '<b>S/ '.number_format($total, 2).'<b>';
	$pdo->close();
	echo json_encode($output);

?>