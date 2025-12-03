<?php
// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

$logFile = __DIR__ . '/promote_debug.log';
file_put_contents($logFile, "\n=== " . date('Y-m-d H:i:s') . " DEMOTE ===\n", FILE_APPEND);
file_put_contents($logFile, "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

try {
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == '') {
        http_response_code(403);
        $response['message'] = 'No autorizado. Inicie sesión como administrador.';
        file_put_contents($logFile, "Unauthorized demote attempt. POST: " . print_r($_POST, true) . "\n", FILE_APPEND);
        echo json_encode($response);
        exit();
    }

    $connPath = __DIR__ . '/../../includes/conn.php';
    if(!file_exists($connPath)) {
        throw new Exception('Archivo de conexión no encontrado: ' . $connPath);
    }
    include $connPath;

    if(!isset($pdo) || !method_exists($pdo, 'open')) {
        throw new Exception('Objeto $pdo no disponible tras incluir conn.php');
    }

    $conn = $pdo->open();

    if(isset($_POST['id'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if($user) {
            if($user['type'] == 1) {
                $stmt = $conn->prepare("UPDATE users SET type=0 WHERE id=:id");
                $stmt->execute(['id' => $id]);

                $response['success'] = true;
                $response['message'] = 'Usuario despromovido a usuario correctamente';
                $_SESSION['success'] = 'Usuario '.$user['firstname'].' '.$user['lastname'].' despromovido a usuario correctamente';
            } else {
                $response['message'] = 'El usuario ya no es administrador';
            }
        } else {
            $response['message'] = 'Usuario no encontrado';
        }
    } else {
        $response['message'] = 'ID de usuario no proporcionado';
    }
} catch(PDOException $e) {
    $msg = 'Error de base de datos: ' . $e->getMessage();
    file_put_contents($logFile, "PDOException demote: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    $response['message'] = $msg;
} catch(Exception $e) {
    $msg = 'Error: ' . $e->getMessage();
    file_put_contents($logFile, "Exception demote: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    $response['message'] = $msg;
}

$pdo->close();

echo json_encode($response);
exit();
