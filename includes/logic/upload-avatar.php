<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db-connect.php';

// ログインチェック
if (!isset($_SESSION['customer'])) {
    exit('ログインが必要です。');
}

// ファイルがアップロードされているか確認
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    
    // 画像ファイルかどうかの簡易チェック（より厳密にするにはMIMEタイプなどをチェック）
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    $file_ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_ext)) {
        exit('許可されていないファイル形式です。');
    }

    $upload_dir = dirname(__FILE__) . '/../../public/uploads/avatars/';
    // ファイル名を一意にする（ユーザーID.拡張子）
    $filename = $_SESSION['customer']['id'] . '.' . $file_ext;
    $target_file = $upload_dir . $filename;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // ファイルを移動
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
        // データベースのavatar_pathを更新
        $stmt = $pdo->prepare("UPDATE customers SET avatar_path = ? WHERE id = ?");
        if ($stmt->execute([$filename, $_SESSION['customer']['id']])) {
            // セッション情報も更新
            $_SESSION['customer']['avatar_path'] = $filename;
            // マイページにリダイレクト
            header('Location: ' . PUBLIC_ROOT_PATH . 'mypage.php');
            exit();
        } else {
            exit('データベースの更新に失敗しました。');
        }
    } else {
        exit('ファイルのアップロードに失敗しました。');
    }
} else {
    exit('ファイルが選択されていないか、アップロード中にエラーが発生しました。');
}
?>