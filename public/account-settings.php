<?php
require_once __DIR__ . '/../includes/templates/header.php';
require_once __DIR__ . '/../includes/db-connect.php';

if (!isset($_SESSION['customer'])) {
    header('Location: ' . PUBLIC_ROOT_PATH . 'login-input.php');
    exit;
}

// 現在のプロフィール情報を取得
$stmt = $pdo->prepare("SELECT profile_text FROM customers WHERE id = ?");
$stmt->execute([$_SESSION['customer']['id']]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
$current_profile = $customer['profile_text'] ?? '';
?>

<div class="form-wrapper">
    <h1>アカウント設定</h1>
    
    <?php // 更新結果メッセージの表示
    if (isset($_GET['status']) && $_GET['status'] === 'updated') {
        echo '<div class="alert-success" style="margin-bottom: 20px;">プロフィールを更新しました。</div>';
    }
    if (isset($_GET['status']) && $_GET['status'] === 'error') {
        echo '<div class="form-errors" style="display:block; margin-bottom: 20px;">更新に失敗しました。</div>';
    }
    ?>

    <div class="account-section">
        <h2>プロフィール画像</h2>
        <form action="/includes/logic/upload-avatar.php" method="post" enctype="multipart/form-data">
            <p>新しい画像を選択してください。</p>
            <input type="file" name="avatar" required>
            <button type="submit" style="margin-top: 10px;">画像をアップロード</button>
        </form>
    </div>

    <hr style="margin: 40px 0;">

    <div class="account-section">
        <h2>自己紹介</h2>
        <form action="/includes/logic/update-profile.php" method="post">
            <textarea name="profile_text" class="form-textarea" rows="8" placeholder="例：ご覧いただきありがとうございます。丁寧な対応を心がけています。"><?= htmlspecialchars($current_profile) ?></textarea>
            <button type="submit" style="margin-top: 10px;">自己紹介を更新</button>
        </form>
    </div>

    <hr style="margin: 40px 0;">

    <div class="account-section">
        <h2>アカウントの削除</h2>
        <p>アカウントを削除すると、元に戻すことはできません。</p>
        <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>delete-account-confirm.php" class="delete-link">アカウントを削除する</a>
    </div>
</div>
<style>
    .account-section h2 { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
    .delete-link { color: #dc3545; }
</style>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>