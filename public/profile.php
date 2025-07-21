<?php
require_once __DIR__ . '/../includes/templates/header.php';
require_once __DIR__ . '/../includes/db-connect.php';

if (!isset($_GET['id'])) { exit('ユーザーが指定されていません。'); }
$seller_id = $_GET['id'];

$stmt_seller = $pdo->prepare("SELECT name, avatar_path, profile_text FROM customers WHERE id = ?");
$stmt_seller->execute([$seller_id]);
$seller = $stmt_seller->fetch(PDO::FETCH_ASSOC);

if (!$seller) { exit('指定されたユーザーは見つかりませんでした。'); }

$stmt_products = $pdo->prepare("SELECT * FROM products WHERE seller_id = ? AND status = 'on_sale' ORDER BY created_at DESC");
$stmt_products->execute([$seller_id]);
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="profile-page-container">

    <div class="profile-card">
        <div class="profile-card-header">
            <div class="avatar">
                <?php if (!empty($seller['avatar_path'])): ?>
                    <img src="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>uploads/avatars/<?= htmlspecialchars($seller['avatar_path'], ENT_QUOTES) ?>" alt="プロフィール画像">
                <?php endif; ?>
            </div>
            <h2><?= htmlspecialchars($seller['name'], ENT_QUOTES) ?></h2>
        </div>

        <?php if (!empty($seller['profile_text'])): ?>
            <div class="profile-card-bio">
                <p><?= nl2br(htmlspecialchars($seller['profile_text'])) ?></p>
            </div>
        <?php endif; ?>
    </div>


    <div class="profile-listings">
        <h3 class="profile-listings-title">出品中の商品</h3>
        
        <div class="product-grid">
            <?php if (empty($products)): ?>
                <p>現在、出品中の商品はありません。</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
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
    </div>
</div>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>