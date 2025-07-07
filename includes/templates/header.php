<?php
// includes/templates/header.php
session_start(); // セッションを開始
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>
<header>
    <div class="header-container">
        <a href="index.php" class="logo-link">
            <img src="/public/assets/images/by2buyers2.JPG" alt="フリマアプリ ロゴ">
        </a>
        <form action="product.php" method="get" class="search-form">
            <input type="text" name="keyword" placeholder="なにをお探しですか？">
            <button type="submit">検索</button>
        </form>
        <nav>
            <?php if (isset($_SESSION['customer'])): ?>
                <span>ようこそ、<?= htmlspecialchars($_SESSION['customer']['name'], ENT_QUOTES) ?>様</span>
                <a href="listing-input.php">出品</a>
                <a href="history.php">購入履歴</a>
                <a href="../includes/logic/logout.php">ログアウト</a>
            <?php else: ?>
                <a href="login-input.php">ログイン</a>
                <a href="customer-input.php">会員登録</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>