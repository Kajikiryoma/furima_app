<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db-connect.php';

// エラーページを表示して処理を終了する関数
function display_error_page($message) {
    require_once __DIR__ . '/../templates/header.php';
    ?>
    <div class="completion-container">
        <div class="icon" style="color: #d32f2f;">&#10008;</div> <h1>登録エラー</h1>
        <p><?= htmlspecialchars($message, ENT_QUOTES) ?></p>
        <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>customer-input.php" class="button-primary" style="background-color: #d32f2f;">登録フォームに戻る</a>
    </div>
    <?php
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // --- 日付の妥当性チェック ---
    $today = new DateTime('today');
    $birthdate_obj = new DateTime($birthdate);
    $min_date = new DateTime('1900-01-01');

    if ($birthdate_obj > $today) {
        display_error_page('生年月日には本日以前の日付を指定してください。');
    }
    if ($birthdate_obj < $min_date) {
        display_error_page('生年月日には1900年1月1日以降の日付を指定してください。');
    }

    // --- メールアドレスの重複チェック ---
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        display_error_page('このメールアドレスは既に使用されています。');
    }

    // --- データベースに登録 ---
    $stmt = $pdo->prepare("INSERT INTO customers (name, email, birthdate, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $birthdate, $password])) {
        // ★★★ 登録成功時のオシャレな表示 ★★★
        require_once __DIR__ . '/../templates/header.php';
        ?>
        <div class="completion-container">
            <div class="icon">&#10004;</div> <h1>登録が完了しました</h1>
            <p>ご登録ありがとうございます。以下のボタンからログインしてください。</p>
            <a href="<?= htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) ?>login-input.php" class="button-primary">ログインページへ</a>
        </div>
        <?php
        require_once __DIR__ . '/../templates/footer.php';
    } else {
        // データベースエラーなど、その他のエラー
        display_error_page('不明なエラーにより登録に失敗しました。時間をおいて再度お試しください。');
    }
}
?>