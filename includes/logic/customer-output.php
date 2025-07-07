<?php
// includes/logic/customer-output.php
require '../db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate']; // 生年月日を受け取る
    // パスワードをハッシュ化
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // メールアドレスの重複チェック
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo 'このメールアドレスは既に使用されています。';
        echo '<a href="../../public/customer-input.php">登録フォームに戻る</a>';
        exit;
    }

    // データベースに登録
    $stmt = $pdo->prepare("INSERT INTO customers (name, email, birthdate, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $birthdate, $password])) {
        echo '登録が完了しました。';
        echo '<a href="../../public/login-input.php">ログインページへ</a>';
    } else {
        echo '登録に失敗しました。';
    }
}
?>