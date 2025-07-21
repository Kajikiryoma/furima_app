<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db-connect.php';

// ログインチェック
if (!isset($_SESSION['customer'])) {
    exit('ログインが必要です。');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profile_text = $_POST['profile_text'] ?? '';
    $customer_id = $_SESSION['customer']['id'];

    $stmt = $pdo->prepare("UPDATE customers SET profile_text = ? WHERE id = ?");
    if ($stmt->execute([$profile_text, $customer_id])) {
        // 更新成功
        header('Location: ' . PUBLIC_ROOT_PATH . 'account-settings.php?status=updated');
        exit();
    } else {
        // 更新失敗
        header('Location: ' . PUBLIC_ROOT_PATH . 'account-settings.php?status=error');
        exit();
    }
}
?>