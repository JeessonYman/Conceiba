<?php
include 'includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el contenido JSON y decodificarlo
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        echo json_encode(['success' => false, 'message' => 'Error en los datos recibidos']);
        exit;
    }

    $conn = $pdo->open();

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // Insertar el ingreso principal
        $stmt = $conn->prepare("INSERT INTO inputs (provider_id, user_id, entry_date, total) VALUES (:provider_id, :user_id, NOW(), :total)");
        
        // Calcular el total
        $total = 0;
        foreach ($data['products'] as $product) {
            $total += $product['subtotal'];
        }

        $stmt->execute([
            'provider_id' => $data['provider_id'],
            'user_id' => $_SESSION['admin'],
            'total' => $total
        ]);

        // Obtener el ID del ingreso insertado
        $inputId = $conn->lastInsertId();

        // Insertar los detalles del ingreso
        $stmt = $conn->prepare("INSERT INTO details_inputs (id_inputs, id_products, quantity, price) VALUES (:input_id, :product_id, :quantity, :price)");

        foreach ($data['products'] as $product) {
            $stmt->execute([
                'input_id' => $inputId,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ]);

            // Actualizar el stock del producto
            $updateStmt = $conn->prepare("UPDATE products SET stock = stock + :quantity WHERE id = :product_id");
            $updateStmt->execute([
                'quantity' => $product['quantity'],
                'product_id' => $product['product_id']
            ]);
        }

        // Confirmar la transacción
        $conn->commit();
        $_SESSION['success'] = 'Ingreso guardado exitosamente';
        echo json_encode(['success' => true, 'message' => 'Ingreso guardado exitosamente. El stock de los productos ha sido actualizado.']);
    } 
    catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        $_SESSION['error'] = 'Error al guardar el ingreso: ' . $e->getMessage();
        echo json_encode(['success' => false, 'message' => 'Error al guardar el ingreso: ' . $e->getMessage()]);
    }

    $pdo->close();
} 
else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>