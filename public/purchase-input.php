<?php
require_once __DIR__ . '/../includes/templates/header.php';
require_once __DIR__ . '/../includes/db-connect.php';

if (!isset($_SESSION['customer'])) {
    header('Location: ' . PUBLIC_ROOT_PATH . 'login-input.php');
    exit;
}

if (!isset($_POST['product_id']) && !isset($_GET['product_id'])) {
    echo '購入する商品が指定されていません。';
    exit;
}
$product_id = $_POST['product_id'] ?? $_GET['product_id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'on_sale'");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo '商品が見つからないか、すでに売り切れています。';
    exit;
}
?>

<h1>購入内容の確認</h1>
<div class="purchase-container">
    <div class="product-info-box">
        <img src="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>uploads/<?= htmlspecialchars($product['image_path']) ?>" alt="">
        <div class="info">
            <p><?= htmlspecialchars($product['name']) ?></p>
            <p class="price">¥ <?= number_format($product['price']) ?></p>
        </div>
    </div>
    <form action="/includes/logic/purchase-output.php" method="post">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <div class="payment-method-box">
            <h2>お支払い方法</h2>
            <div class="payment-options">
                <label><input type="radio" name="payment_method" value="credit_card" checked> クレジットカード</label>
                <label><input type="radio" name="payment_method" value="konbini"> コンビニ払い</label>
                <label><input type="radio" name="payment_method" value="bank"> 銀行振込</label>
            </div>
            <div id="credit-card-form" class="dummy-form">
                <p>カード番号: <input type="text" placeholder="0000 0000 0000 0000"></p>
                <p>有効期限: <input type="text" placeholder="MM/YY"> CVC: <input type="text" placeholder="123"></p>
            </div>
        </div>
        <button type="submit" class="purchase-button">購入を確定する</button>
    </form>
</div>

<style>
.purchase-container { max-width: 600px; margin: auto; }
.product-info-box { display: flex; align-items: center; gap: 20px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;}
.product-info-box img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; }
.payment-method-box { margin-bottom: 30px; }
.payment-options label { display: block; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px; cursor: pointer; }
.payment-options input { margin-right: 10px; }
.dummy-form { border: 1px dashed #ccc; padding: 15px; border-radius: 4px; margin-top: 15px; }
.dummy-form input { padding: 5px; }
.purchase-button { width: 100%; padding: 15px; background-color: #d32f2f; color: #fff; border: none; border-radius: 8px; font-size: 18px; font-weight: bold; cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const creditCardForm = document.getElementById('credit-card-form');
    function toggleCreditCardForm() {
        if (document.querySelector('input[name="payment_method"]:checked').value === 'credit_card') {
            creditCardForm.style.display = 'block';
        } else {
            creditCardForm.style.display = 'none';
        }
    }
    toggleCreditCardForm();
    paymentRadios.forEach(radio => radio.addEventListener('change', toggleCreditCardForm));
});
</script>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>