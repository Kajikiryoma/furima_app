<?php
require_once __DIR__ . '/../includes/templates/header.php';
?>

<div class="form-wrapper">
    <h1>ログイン</h1>
    
    <form action="/includes/logic/login-output.php" method="post" id="login-form">
        <div id="login-errors" class="form-errors"></div>

        <p>
            <label for="email">メールアドレス:</label>
            <input type="email" id="email" name="email" required>
        </p>
        <p>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
        </p>
        <button type="submit" class="login-button">ログインする</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const errorContainer = document.getElementById('login-errors');
    const submitButton = loginForm.querySelector('.login-button');

    loginForm.addEventListener('submit', async function(e) {
        // デフォルトのフォーム送信（ページ遷移）をキャンセル
        e.preventDefault();

        // エラー表示を一旦クリアし、ボタンを無効化
        errorContainer.style.display = 'none';
        errorContainer.textContent = '';
        submitButton.disabled = true;
        submitButton.textContent = 'ログイン中...';

        const formData = new FormData(loginForm);

        try {
            // fetch APIでフォームデータをサーバーに送信
            const response = await fetch('/includes/logic/login-output.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                // 成功した場合、指定されたURLにページを移動
                window.location.href = result.redirect_url;
            } else {
                // 失敗した場合、エラーメッセージを表示
                errorContainer.textContent = result.message;
                errorContainer.style.display = 'block';
            }
        } catch (err) {
            errorContainer.textContent = '通信エラーが発生しました。';
            errorContainer.style.display = 'block';
        } finally {
            // 成功・失敗にかかわらず、ボタンを元に戻す
            submitButton.disabled = false;
            submitButton.textContent = 'ログインする';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>