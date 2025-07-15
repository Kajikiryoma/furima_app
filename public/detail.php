<?php
require_once __DIR__ . '/../includes/templates/header.php';
require_once __DIR__ . '/../includes/db-connect.php';

if (!isset($_GET['id'])) { exit('商品が指定されていません。'); }
$product_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT p.*, c.name as seller_name FROM products p JOIN customers c ON p.seller_id = c.id WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) { exit('商品が見つかりません。'); }

$is_favorited = false;
if (isset($_SESSION['customer'])) {
    $stmt_fav = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE customer_id = ? AND product_id = ?");
    $stmt_fav->execute([$_SESSION['customer']['id'], $product_id]);
    if ($stmt_fav->fetchColumn() > 0) {
        $is_favorited = true;
    }
}
?>

<div class="product-detail-container">
    <div class="product-detail-image">
        <img src="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>uploads/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="product-detail-info">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p class="seller">出品者: <?= htmlspecialchars($product['seller_name']) ?></p>
        <p class="price">¥ <?= number_format($product['price']) ?></p>

        <div class="actions-container">
            <?php if ($product['status'] === 'on_sale'): ?>
                
                <?php
                $purchase_form_html = '<form class="purchase-form" action="' . htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) . 'purchase-input.php" method="post">
                                        <input type="hidden" name="product_id" value="' . $product['id'] . '">
                                        <button type="submit" class="purchase-button">購入手続きへ</button>
                                      </form>';

                if (isset($_SESSION['customer']) && $_SESSION['customer']['id'] != $product['seller_id']) {
                    echo $purchase_form_html;
                } 
                elseif (isset($_SESSION['customer']) && $_SESSION['customer']['id'] == $product['seller_id']) {
                    echo '<p class="self-listing-notice">あなたが出品した商品です</p>';
                } 
                else {
                    echo $purchase_form_html;
                }

                if (isset($_SESSION['customer'])) {
                    echo '<button id="favorite-btn" class="favorite-button ' . ($is_favorited ? 'active' : '') . '" data-product-id="' . $product['id'] . '">
                            <span class="icon">&#10084;</span>
                            <span class="text">' . ($is_favorited ? 'いいね済み' : 'いいね！') . '</span>
                          </button>';
                }
                ?>

            <?php else: ?>
                <p class="sold-out">この商品は売り切れました</p>
            <?php endif; ?>
        </div>

        <div class="description">
            <h2>商品の説明</h2>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const favBtn = document.getElementById('favorite-btn');
    if (!favBtn) return;
    favBtn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const isFavorited = this.classList.contains('active');
        const endpoint = isFavorited ? '/includes/logic/favorite-delete.php' : '/includes/logic/favorite-insert.php';
        const favText = this.querySelector('.text');
        this.disabled = true;
        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.classList.toggle('active');
                favText.textContent = this.classList.contains('active') ? 'いいね済み' : 'いいね！';
            } else {
                alert('処理に失敗しました。ログインしているか確認してください。');
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            this.disabled = false;
        });
    });
});
</script>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>