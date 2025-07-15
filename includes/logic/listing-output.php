<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db-connect.php';

// レスポンス用の配列を準備
$response = ['success' => false, 'message' => '', 'error_type' => 'general'];

if (!isset($_SESSION['customer'])) {
    $response['message'] = 'ログインが必要です。';
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['customer']['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
    $regular_price = filter_input(INPUT_POST, 'regular_price', FILTER_VALIDATE_INT);

    // --- バリデーションチェック ---
    if ($price < 0) {
        $response['message'] = '価格には0以上の数値を入力してください。';
        $response['error_type'] = 'price'; // ★ 価格関連のエラーとしてマーク
    } elseif ($regular_price && $price > $regular_price) {
        $response['message'] = "販売価格（" . number_format($price) . "円）が参考価格（" . number_format($regular_price) . "円）を上回っています。";
        $response['error_type'] = 'price'; // ★ 価格関連のエラーとしてマーク
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ? AND name LIKE ? AND status = 'on_sale'");
        $stmt->execute([$seller_id, '%' . $name . '%']);
        if ($stmt->fetchColumn() >= 3) {
            $response['message'] = '類似商品の多重出品は制限されています。出品を中止しました。';
            // このエラーは一般的なエラー(general)のまま
        }
    }

    if (!empty($response['message'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        exit;
    }

    // --- 画像アップロード処理 ---
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = dirname(__FILE__) . '/../../public/uploads/';
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;

        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $stmt = $pdo->prepare(
                "INSERT INTO products (seller_id, name, description, price, regular_price, image_path) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            if ($stmt->execute([$seller_id, $name, $description, $price, $regular_price, $filename])) {
                $response['success'] = true;
                $response['message'] = '出品が完了しました！';
                $response['redirect_url'] = PUBLIC_ROOT_PATH . 'index.php';
            } else { $response['message'] = 'データベースへの登録に失敗しました。'; }
        } else { $response['message'] = 'ファイルのアップロードに失敗しました。'; }
    } else { $response['message'] = '画像ファイルが選択されていません。'; }
} else { $response['message'] = '不正なリクエストです。'; }

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit;
?>