<?php
require_once __DIR__ . '/../includes/templates/header.php';

// 修正点 今日の日付を取得
$today = date('Y-m-d');
?>

<div class="form-wrapper">
    <h1>会員登録</h1>
    <form action="/includes/logic/customer-output.php" method="post">
        <p>
            <label for="name">お名前:</label>
            <input type="text" id="name" name="name" required>
        </p>
        <p>
            <label for="email">メールアドレス:</label>
            <input type="email" id="email" name="email" required>
        </p>
        <p>
            <label for="birthdate">生年月日:</label>
            <input type="date" id="birthdate" name="birthdate" required min="1900-01-01" max="<?= $today ?>">
        </p>
        <p>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
        </p>
        <button type="submit">会員登録する</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/templates/footer.php'; ?>