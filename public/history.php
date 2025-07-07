<?php
require '../includes/templates/header.php';
require '../includes/db-connect.php';

// ログインチェック
if (!isset($_SESSION['customer'])) {
    header('Location: /public/login-input.php');
    exit;
}

$customer_id = $_SESSION['customer']['id'];

// 購入履歴を取得
$stmt = $pdo->prepare(
    "SELECT p.name, p.price, p.image_path, pu.purchase_date 
     FROM purchases pu 
     JOIN products p ON pu.product_id = p.id 
     WHERE pu.buyer_id = ? 
     ORDER BY pu.purchase_date DESC"
);
$stmt->execute([$customer_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>購入履歴</h1>

<?php if (empty($history)): ?>
    <p>購入履歴はありません。</p>
<?php else: ?>
    <table border="1" style="width:100%; border-collapse: collapse;">
        <tr>
            <th>商品</th>
            <th>価格</th>
            <th>購入日時</th>
        </tr>
        <?php foreach ($history as $item): ?>
            <tr>
                <td style="display:flex; align-items:center; gap:15px;">
                    <img src="/public/uploads/<?= htmlspecialchars($item['image_path']) ?>" width="60">
                    <?= htmlspecialchars($item['name']) ?>
                </td>
                <td>¥ <?= number_format($item['price']) ?></td>
                <td><?= htmlspecialchars($item['purchase_date']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require '../includes/templates/footer.php'; ?>