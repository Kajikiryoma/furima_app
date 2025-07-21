<?php
// config.php - 環境設定ファイル

// ----------------------------------------
// データベース設定
// ----------------------------------------
// 自分のMAMP環境に合わせて変更してください
define('DB_HOST', 'localhost');
define('DB_NAME', 'shop_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // MAMPのデフォルトではパスワードは空です。必要に応じて変更してください。

// ----------------------------------------
// 楽天API設定
// ----------------------------------------
// 自分の楽天アプリIDを入力してください
define('RAKUTEN_APP_ID', 'ここの文章を削除して、あなたの楽天アプリIDを入力してください');

// ----------------------------------------
// URLとパスの設定（重要）
// ----------------------------------------
// アプリのルートURL（MAMPのドキュメントルート設定に合わせる）
// 例1: http://localhost/furima_app/
// 例2: http://localhost:8888/furima_app/
// 例3: http://localhost/ （あなたの現在の設定）
define('BASE_URL', 'http://localhost');

// publicディレクトリへのパス（BASE_URLからの相対パス）
define('PUBLIC_ROOT_PATH', '/public/');

?>