<?php
// header.php

// セッションを開始
session_start();

// 設定ファイルを読み込む（__DIR__を使ってファイルの場所からの相対パスを保証）
require_once __DIR__ . '/../../includes/config.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>assets/css/style.css">
</head>
<body>
<header>
    <div class="header-container">
        <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>index.php" class="logo-link">
            <img src="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>assets/images/by2buyers2.JPG" alt="フリマアプリ ロゴ">
        </a>
        <form action="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>product.php" method="get" class="search-form">
            <input type="text" name="keyword" placeholder="なにをお探しですか？">
            <button type="submit">検索</button>
        </form>
        <nav>
            <?php if (isset($_SESSION['customer'])): ?>
                <span>ようこそ、<?= htmlspecialchars($_SESSION['customer']['name'], ENT_QUOTES) ?>様</span>
                <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>listing-input.php">出品</a>
                <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>history.php">購入履歴</a>
                <a href="/includes/logic/logout.php">ログアウト</a>
            <?php else: ?>
                <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>login-input.php">ログイン</a>
                <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>customer-input.php">会員登録</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>