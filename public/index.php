<?php
require_once __DIR__ . '/../includes/templates/header.php';
require_once __DIR__ . '/../includes/db-connect.php';

// 新着商品を10件取得
$stmt = $pdo->query("SELECT * FROM products WHERE status = 'on_sale' ORDER BY created_at DESC LIMIT 10");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>新着商品</h1>
<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>現在出品されている商品はありません。</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>detail.php?id=<?= htmlspecialchars($product['id'], ENT_QUOTES) ?>">
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