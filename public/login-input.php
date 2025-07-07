<?php require '../includes/templates/header.php'; ?>

<div class="form-wrapper">
    <h1>ログイン</h1>
    <form action="../includes/logic/login-output.php" method="post">
        <p>
            <label for="email">メールアドレス:</label>
            <input type="email" id="email" name="email" required>
        </p>
        <p>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
        </p>
        <button type="submit">ログインする</button>
    </form>
</div>

<?php require '../includes/templates/footer.php'; ?>