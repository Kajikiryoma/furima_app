<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db-connect.php';

$response = ['success' => false];
if (isset($_SESSION['customer'], $_POST['product_id'])) {
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE customer_id = ? AND product_id = ?");
    if ($stmt->execute([$_SESSION['customer']['id'], $_POST['product_id']])) {
        $response['success'] = true;
    }
}
header('Content-Type: application/json');
echo json_encode($response);
?>