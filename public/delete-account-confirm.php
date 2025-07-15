<?php
require_once __DIR__ . '/../includes/templates/header.php';
// ログインしていなければ、マイページへリダイレクト
if (!isset($_SESSION['customer'])) {
    header('Location: ' . PUBLIC_ROOT_PATH . 'mypage.php');
    exit;
}
?>

<div class="form-wrapper">
    <h1>アカウントの削除</h1>
    <div class="delete-warning">
        <p><strong>本当にアカウントを削除しますか？</strong></p>
        <p>この操作は取り消すことができません。あなたのアカウント情報、購入履歴、出品した商品などがすべて削除されます。</p>
    </div>
    <form action="/includes/logic/delete-account.php" method="post" style="text-align: center;">
        <button type="submit" class="delete-confirm-button">はい、アカウントを削除します</button>
        <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>account-settings.php" class="cancel-link">いいえ、キャンセルします</a>
    </form>
</div>

<style>
    .delete-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .delete-confirm-button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 4px;
        background-color: #dc3545; /* 危険な操作を示す赤色 */
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-bottom: 10px;
    }
    .cancel-link {
        display: inline-block;
        color: #6c757d;
        font-size: 14px;
    }
</style>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>