<?php
session_start();
require '../db-connect.php';

// ログインチェック
if (!isset($_SESSION['customer'])) {
    exit('購入するにはログインが必要です。');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $buyer_id = $_SESSION['customer']['id'];

    try {
        // トランザクション開始
        $pdo->beginTransaction();

        // 1. 商品がまだ購入可能かロックして確認
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'on_sale' FOR UPDATE");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            throw new Exception('商品が売り切れているか、存在しません。');
        }

        // 2. 商品テーブルのステータスを売り切れに更新
        $stmt = $pdo->prepare("UPDATE products SET status = 'sold' WHERE id = ?");
        $stmt->execute([$product_id]);

        // 3. 購入履歴テーブルに記録
        $stmt = $pdo->prepare("INSERT INTO purchases (product_id, buyer_id) VALUES (?, ?)");
        $stmt->execute([$product_id, $buyer_id]);

        // すべて成功したらコミット
        $pdo->commit();

        // 購入完了ページへリダイレクト
        header('Location: /public/purchase-complete.php');
        exit();

    } catch (Exception $e) {
        // エラーが発生したらロールバック
        $pdo->rollBack();
        exit('エラー: ' . $e->getMessage());
    }
}
?>