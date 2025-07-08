<?php
// db-connect.php

// 設定ファイルを読み込む
require_once __DIR__ . '/config.php';

try {
    // config.phpで定義した定数を使用する
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('データベース接続失敗。' . $e->getMessage());
}
?>