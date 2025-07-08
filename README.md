# 転売対策フリマアプリ セットアップ手順

## 1. 必須環境
- MAMP, XAMPPなどのPHP・MySQLが動作するローカルサーバー環境

## 2. インストール手順
1.  このプロジェクトをダウンロードし、MAMPなどのWebサーバーのドキュメントルート（例：`htdocs`）に配置します。
2.  phpMyAdminを使い、`shop_db` という名前で新しいデータベースを作成します。（照合順序は `utf8mb4_general_ci` を推奨）
3.  作成した`shop_db`データベースを選択し、`db/shop.sql`ファイルをインポートしてテーブルを作成します。
4.  `includes/` フォルダにある `config.sample.php` をコピーして、同じフォルダ内に `config.php` という名前で複製します。
5.  `config.php`を開き、以下の項目を自分の環境に合わせて設定します。
    - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`：自分のデータベース接続情報
    - `RAKUTEN_APP_ID`：自分で取得した楽天アプリID
    - `BASE_URL`, `PUBLIC_ROOT_PATH`: 自分のローカルサーバーのURL設定
6.  ブラウザで設定した `BASE_URL` と `PUBLIC_ROOT_PATH` を組み合わせたURL（例: `http://localhost/furima_app/public/`）にアクセスします。
以上でセットアップは完了です。# furima_app
