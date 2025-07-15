<?php
require_once __DIR__ . '/../includes/templates/header.php';
require_once __DIR__ . '/../includes/db-connect.php';

if (!isset($_SESSION['customer'])) {
    header('Location: ' . PUBLIC_ROOT_PATH . 'login-input.php');
    exit;
}
$customer_id = $_SESSION['customer']['id'];

// favoritesテーブルとproductsテーブルを結合して、いいねした商品情報を取得
$stmt = $pdo->prepare(
    "SELECT p.* FROM products p 
     JOIN favorites f ON p.id = f.product_id 
     WHERE f.customer_id = ?"
);
$stmt->execute([$customer_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>いいね！一覧</h1>

<div class="product-grid">
    <?php if (empty($favorites)): ?>
        <p>いいねした商品はありません。</p>
    <?php else: ?>
        <?php foreach ($favorites as $product): ?>
            <div class="product-card">
                <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>detail.php?id=<?= $product['id'] ?>">
                    <img src="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>uploads/<?= htmlspecialchars($product['image_path'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>">
                    <div class="product-info">
                        <p class="product-name"><?= htmlspecialchars($product['name'], ENT_QUOTES) ?></p>
                        <p class="product-price">現在 ¥<?= number_format($product['price']) ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>