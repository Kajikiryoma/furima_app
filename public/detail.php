<?php
require '../includes/templates/header.php';
require '../includes/db-connect.php';

// URLから商品IDを取得
if (!isset($_GET['id'])) {
    echo '商品が指定されていません。';
    exit;
}
$product_id = $_GET['id'];

// 商品情報を取得
$stmt = $pdo->prepare("SELECT p.*, c.name as seller_name FROM products p JOIN customers c ON p.seller_id = c.id WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo '商品が見つかりません。';
    exit;
}
?>

<div class="product-detail-container">
    <div class="product-detail-image">
        <img src="/public/uploads/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="product-detail-info">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p class="seller">出品者: <?= htmlspecialchars($product['seller_name']) ?></p>
        <p class="price">¥ <?= number_format($product['price']) ?></p>

        <div class="description">
            <h2>商品の説明</h2>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>
        
        <?php if ($product['status'] === 'on_sale'): ?>
            <form action="purchase-input.php" method="post">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" class="purchase-button">購入手続きへ</button>
            </form>
        <?php else: ?>
            <p class="sold-out">この商品は売り切れました</p>
        <?php endif; ?>
    </div>
</div>

<style>
/* このページ専用の簡易的なスタイル */
.product-detail-container { display: flex; gap: 40px; background: #fff; padding: 30px; border-radius: 8px; }
.product-detail-image { flex: 1; }
.product-detail-image img { width: 100%; border-radius: 8px; }
.product-detail-info { flex: 1; }
.product-detail-info h1 { margin: 0 0 10px 0; }
.product-detail-info .price { font-size: 28px; font-weight: bold; color: #d32f2f; margin: 20px 0; }
.purchase-button { width: 100%; padding: 15px; background-color: #d32f2f; color: #fff; border: none; border-radius: 8px; font-size: 18px; font-weight: bold; cursor: pointer; }
.sold-out { text-align: center; padding: 15px; background: #ccc; color: #fff; font-weight: bold; border-radius: 8px;}
</style>

<?php require '../includes/templates/footer.php'; ?>