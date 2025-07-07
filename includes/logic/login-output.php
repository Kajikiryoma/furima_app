<?php
session_start();
require '../db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer && password_verify($password, $customer['password'])) {
        $_SESSION['customer'] = [
            'id' => $customer['id'],
            'name' => $customer['name'],
            'email' => $customer['email']
        ];
        // トップページへリダイレクト（ここを修正！）
        header('Location: /public/index.php');
        exit();
    } else {
        echo 'メールアドレスまたはパスワードが違います。';
        echo '<br><a href="/public/login-input.php">ログインページに戻る</a>'; // こちらも念のため修正
    }
}
?>