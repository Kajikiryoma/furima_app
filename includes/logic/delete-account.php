<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db-connect.php';

// ログインしているユーザー本人かを確認
if (!isset($_SESSION['customer'])) {
    exit('不正な操作です。');
}

$customer_id = $_SESSION['customer']['id'];

try {
    // データベース操作をトランザクションで囲む
    $pdo->beginTransaction();

    // 1. ユーザーがいいねした履歴を削除
    $stmt_fav = $pdo->prepare("DELETE FROM favorites WHERE customer_id = ?");
    $stmt_fav->execute([$customer_id]);

    // 2. ユーザーの購入履歴を削除
    $stmt_purchases = $pdo->prepare("DELETE FROM purchases WHERE buyer_id = ?");
    $stmt_purchases->execute([$customer_id]);
    
    // 3. ユーザーが出品した商品を削除
    // 注意：他の人が購入した履歴も消えてしまうため、実際のサービスでは
    // 商品を「非公開」にするなどの対応が望ましいですが、今回は削除します。
    $stmt_products = $pdo->prepare("DELETE FROM products WHERE seller_id = ?");
    $stmt_products->execute([$customer_id]);

    // 4. 最後にユーザー本人を削除
    $stmt_customer = $pdo->prepare("DELETE FROM customers WHERE id = ?");
    $stmt_customer->execute([$customer_id]);

    // 全ての削除が成功したら、変更を確定
    $pdo->commit();

} catch (Exception $e) {
    // 途中でエラーが起きたら、全ての変更を取り消す
    $pdo->rollBack();
    exit("エラーが発生しました: " . $e->getMessage());
}

// 完全にログアウトさせる
session_destroy();

// トップページにリダイレクトして完了メッセージを表示
header('Location: ' . PUBLIC_ROOT_PATH . 'index.php?action=deleted');
exit();
?>