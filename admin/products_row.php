<?php 
    include 'includes/session.php';

    if(isset($_POST['id'])){
        $id = $_POST['id'];
        
        $conn = $pdo->open();

        $stmt = $conn->prepare("SELECT *, 
                                products.id AS prodid, 
                                products.name AS prodname, 
                                category.name AS catname,
                                provider.name AS proname, 
                                products.cost AS cost 
                                FROM products 
                                LEFT JOIN category ON category.id=products.category_id 
                                LEFT JOIN provider ON provider.id=products.provider_id 
                                WHERE products.id=:id");
        $stmt->execute(['id'=>$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $pdo->close();

        echo json_encode($row);
    }
?>
