# 転売対策フリマアプリ セットアップ手順

## 1. 必須環境
- MAMP, XAMPPなどのPHP・MySQLが動作するローカルサーバー環境

## 2. インストール手順
1.  このプロジェクトをダウンロードし、MAMPなどのWebサーバーのドキュメントルート（例：`htdocs`）に配置する。
2.  phpMyAdminを使い、`shop_db` という名前で新しいデータベースを作成する。（照合順序は `utf8mb4_general_ci` を推奨）
3.  作成した`shop_db`データベースを選択し、`db/shop.sql`ファイルをインポートしてテーブルを作成する。
4.  `includes/` フォルダにある  `config.php` という名前のファイルを開く。
5.  `config.php`を開いた後、、以下の項目を自分の環境に合わせて設定する。
    - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`：自分のデータベース接続情報
    - `RAKUTEN_APP_ID`：自分で取得した楽天アプリID
    - `BASE_URL`, `PUBLIC_ROOT_PATH`: 自分のローカルサーバーのURL設定
6.  ブラウザで設定した `BASE_URL` と `PUBLIC_ROOT_PATH` を組み合わせたURL（例: `http://localhost/furima_app/public/`）にアクセスする。
以上でセットアップは完了となる。# furima_app
