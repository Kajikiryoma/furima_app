<?php
session_start();

// セッションから顧客情報を削除
unset($_SESSION['customer']);

// ログインページにリダイレクト
header('Location: ../../public/login-input.php');
exit();
?>