<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db-connect.php';

// フロントエンドに返すレスポンスの雛形
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    // ユーザーが存在し、パスワードが一致する場合
    if ($customer && password_verify($password, $customer['password'])) {
        // ログイン成功
        $_SESSION['customer'] = [
            'id' => $customer['id'],
            'name' => $customer['name'],
            'email' => $customer['email'],
            'avatar_path' => $customer['avatar_path']
        ];
        $response['success'] = true;
        $response['redirect_url'] = PUBLIC_ROOT_PATH . 'mypage.php'; // ログイン後はマイページへ
    } else {
        // ログイン失敗
        $response['message'] = 'メールアドレスまたはパスワードが違います。';
    }
} else {
    $response['message'] = '不正なリクエストです。';
}

// 最終的なレスポンスをJSON形式で出力
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit();
?>