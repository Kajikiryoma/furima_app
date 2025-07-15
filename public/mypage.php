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
$customer_name = $_SESSION['customer']['name'];

// ユーザーの統計情報を取得
// 1. 出品した商品数
$stmt_listed = $pdo->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ?");
$stmt_listed->execute([$customer_id]);
$listed_count = $stmt_listed->fetchColumn();

// 2. 購入した商品数
$stmt_purchased = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE buyer_id = ?");
$stmt_purchased->execute([$customer_id]);
$purchased_count = $stmt_purchased->fetchColumn();
?>

<h1>マイページ</h1>

<div class="mypage-container">
    <aside class="mypage-sidebar">
        <ul class="mypage-nav-list">
            <li class="mypage-nav-item"><a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>listed-items.php">出品した商品</a></li>
            <li class="mypage-nav-item"><a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>history.php">購入した商品</a></li>
            <li class="mypage-nav-item"><a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>favorite-list.php">いいね！一覧</a></li>
            <li class="mypage-nav-item"><a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>account-settings.php">アカウント設定</a></li>
        </ul>
    </aside>

    <div class="mypage-content">
        <div class="profile-header">
            <div class="avatar">
                <?php if (!empty($_SESSION['customer']['avatar_path'])): ?>
                    <img src="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>uploads/avatars/<?= htmlspecialchars($_SESSION['customer']['avatar_path'], ENT_QUOTES) ?>" alt="プロフィール画像">
                <?php endif; ?>
            </div>
            <h2><?= htmlspecialchars($customer_name, ENT_QUOTES) ?></h2>
        </div>
        <style>.avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }</style>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">出品した数</div>
                <div class="value"><?= $listed_count ?></div>
            </div>
            <div class="stat-card">
                <div class="label">購入した数</div>
                <div class="value"><?= $purchased_count ?></div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>