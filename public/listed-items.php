<?php
require_once __DIR__ . '/../includes/templates/header.php';
require_once __DIR__ . '/../includes/db-connect.php';

// ログインしていなければ、ログインページへリダイレクト
if (!isset($_SESSION['customer'])) {
    header('Location: ' . PUBLIC_ROOT_PATH . 'login-input.php');
    exit;
}

// セッションからユーザーIDを取得
$customer_id = $_SESSION['customer']['id'];

// データベースから、このユーザーが出品した全商品を取得する
$stmt = $pdo->prepare(
    "SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC"
);
$stmt->execute([$customer_id]);
$listed_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>出品した商品</h1>

<?php if (empty($listed_items)): ?>
    <p>出品した商品はありません。</p>
<?php else: ?>
    <div class="history-list">
        <?php foreach ($listed_items as $item): ?>
            <div class="history-item">
                <div class="history-item-image">
                    <img src="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>uploads/<?= htmlspecialchars($item['image_path'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>">
                </div>
                <div class="history-item-details">
                    <p class="history-item-name"><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></p>
                    <div class="history-item-meta">
                        <span class="history-item-price">¥ <?= number_format($item['price']) ?></span>

                        <?php if ($item['status'] === 'on_sale'): ?>
                            <span class="item-status-badge status-on_sale">販売中</span>
                        <?php else: ?>
                            <span class="item-status-badge status-sold">売り切れ</span>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>