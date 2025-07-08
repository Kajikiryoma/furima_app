<?php
require_once __DIR__ . '/../includes/templates/header.php';
require_once __DIR__ . '/../includes/db-connect.php';

$keyword = '';
$sql = "SELECT * FROM products WHERE status = 'on_sale'";
$params = [];

if (!empty($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $sql .= " AND name LIKE ?";
    $params[] = '%' . $keyword . '%';
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>商品一覧
    <?php if ($keyword): ?>
        <small>（検索キーワード: <?= htmlspecialchars($keyword, ENT_QUOTES) ?>）</small>
    <?php endif; ?>
</h1>

<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>該当する商品が見つかりませんでした。</p>
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