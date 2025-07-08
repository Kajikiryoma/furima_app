<?php
session_start();
require_once __DIR__ . '/../config.php'; // ★ 修正点: config.phpを読み込む
require_once __DIR__ . '/../db-connect.php';

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
        // ★ 修正点: リダイレクトパスを定数に置き換え
        header('Location: ' . PUBLIC_ROOT_PATH . 'index.php');
        exit();
    } else {
        echo 'メールアドレスまたはパスワードが違います。';
        // ★ 修正点: パスを定数に置き換え
        echo '<br><a href="' . htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) . 'login-input.php">ログインページに戻る</a>';
    }
}
?>