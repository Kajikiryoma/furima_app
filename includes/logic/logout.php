<?php
session_start();
require_once __DIR__ . '/../config.php'; // ★ 修正点: config.phpを読み込む

// セッションから顧客情報を削除
unset($_SESSION['customer']);

// ★ 修正点: リダイレクトパスを定数に置き換え
header('Location: ' . PUBLIC_ROOT_PATH . 'login-input.php');
exit();
?>