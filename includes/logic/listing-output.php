<?php
session_start();
require_once __DIR__ . '/../config.php'; // ★ 修正点: config.phpを読み込む
require_once __DIR__ . '/../db-connect.php';

// ログインチェック
if (!isset($_SESSION['customer'])) {
    exit('ログインが必要です。');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['customer']['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
    $regular_price = filter_input(INPUT_POST, 'regular_price', FILTER_VALIDATE_INT);

    // ===============================================
    // ▼▼▼ 転売チェック機能 ▼▼▼
    // ===============================================

    // 1. 価格チェック
    if ($regular_price && $price > $regular_price) {
        echo "<h1>エラー</h1>";
        echo "<p>販売価格（" . number_format($price) . "円）が参考価格（" . number_format($regular_price) . "円）を上回っています。</p>";
        echo "<p>価格を修正してください。</p>";
        // ★ 修正点: パスを定数に置き換え
        echo '<a href="' . htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) . 'listing-input.php">出品ページに戻る</a>';
        exit;
    }
    
    // 2. 多重出品チェック
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ? AND name LIKE ? AND status = 'on_sale'");
    $stmt->execute([$seller_id, '%' . $name . '%']);
    $same_item_count = $stmt->fetchColumn();

    if ($same_item_count >= 3) {
        echo "<h1>エラー</h1>";
        echo "<p>類似商品の多重出品は制限されています。出品を中止しました。</p>";
        // ★ 修正点: パスを定数に置き換え
        echo '<a href="' . htmlspecialchars(PUBLIC_ROOT_PATH, ENT_QUOTES) . 'listing-input.php">出品ページに戻る</a>';
        exit;
    }

    // ===============================================
    // ▲▲▲ チェック機能はここまで ▲▲▲
    // ===============================================


    // --- 画像アップロード処理 ---
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // ★ 修正点: パスの指定方法をより確実に
        $upload_dir = dirname(__FILE__) . '/../../public/uploads/';
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // --- データベースに商品を登録 ---
            $stmt = $pdo->prepare(
                "INSERT INTO products (seller_id, name, description, price, regular_price, image_path) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            if ($stmt->execute([$seller_id, $name, $description, $price, $regular_price, $filename])) {
                // ★ 修正点: リダイレクトパスを定数に置き換え
                header('Location: ' . PUBLIC_ROOT_PATH . 'index.php');
                exit();
            } else {
                echo 'データベースへの登録に失敗しました。';
            }
        } else {
            echo 'ファイルのアップロードに失敗しました。';
        }
    } else {
        echo '画像ファイルが選択されていないか、アップロード中にエラーが発生しました。';
    }
}
?>