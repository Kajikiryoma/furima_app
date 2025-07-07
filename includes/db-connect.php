<?php
// includes/db-connect.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'shop_db');
define('DB_USER', 'root');      // ←ここを 'root' に変更
define('DB_PASS', 'root');      // ←ここを 'root' に変更

try {
    // PDOインスタンスの作成
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS
    );
    // エラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続エラーの場合はメッセージを出力して終了
    exit('データベース接続失敗。' . $e->getMessage());
}
?>