<?php
require_once __DIR__ . '/../includes/templates/header.php';
// ログインしていなければ、ログインページへリダイレクト
if (!isset($_SESSION['customer'])) {
    header('Location: ' . PUBLIC_ROOT_PATH . 'login-input.php');
    exit;
}
?>

<div class="form-wrapper">
    <h1>アカウント設定</h1>

    <h2>プロフィール画像</h2>
    <form action="/includes/logic/upload-avatar.php" method="post" enctype="multipart/form-data">
        <p>新しい画像を選択してください。</p>
        <input type="file" name="avatar" required>
        <br><br>
        <button type="submit">画像をアップロード</button>
    </form>
    
    <hr style="margin: 40px 0;">

    <h2>アカウントの削除</h2>
    <p>アカウントを削除すると、元に戻すことはできません。</p>
    <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>delete-account-confirm.php" style="color: red;">アカウントを削除する</a>

</div>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>