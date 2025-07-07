<?php
require '../includes/templates/header.php';
?>

<div class="completion-box">
    <h1>商品の購入が完了しました！</h1>
    <p>この度はご購入いただき誠にありがとうございます。</p>
    <p>発送まで今しばらくお待ちください。</p>
    <div class="links">
        <a href="/public/history.php">購入履歴を見る</a>
        <a href="/public/index.php">トップページに戻る</a>
    </div>
</div>

<style>
    .completion-box { text-align: center; background: #fff; padding: 50px; border-radius: 8px; max-width: 600px; margin: 20px auto; }
    .completion-box h1 { color: #4CAF50; }
    .completion-box .links a { display: inline-block; margin: 10px; padding: 10px 20px; border: 1px solid #ccc; border-radius: 4px; }
</style>


<?php require '../includes/templates/footer.php'; ?>