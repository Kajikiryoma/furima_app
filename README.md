# 転売対策フリマアプリ セットアップ手順

## 1. 必須環境
- MAMP, XAMPPなどのPHP・MySQLが動作するローカルサーバー環境

## 2. インストール手順
1.  このプロジェクトをダウンロードし、MAMPなどのWebサーバーのドキュメントルート（例：`htdocs`）に配置する。
2.  xamppのapacheのhttpd.confのdocumentrootとdirectoryをアプリの場所に変更する (例:"C:/xamp/htdocs/furima_app-main/) 。mampは設定アイコンの「server」からdocumentrootを設定する。
3.  設定終了後xampp (or mamp) のapacheを一度停止し、再度起動する。  
4.  phpMyAdminを使い、`shop_db` という名前で新しいデータベースを作成する。（照合順序は `utf8mb4_general_ci` を推奨）
5.  作成した`shop_db`データベースを選択し、`db/shop.sql`ファイルをインポートしてテーブルを作成する。エラーが出る場合は先頭に　drop database　を追加する。　　　  
6.  `includes/` フォルダにある  `config.php` という名前のファイルを開く。  
7.  `config.php`を開いた後、、以下の項目を自分の環境に合わせて設定する。  
    - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`：自分のデータベース接続情報  
    - `RAKUTEN_APP_ID`：自分で取得した楽天アプリID　
8.  ブラウザで設定した `BASE_URL` と `PUBLIC_ROOT_PATH` を組み合わせたURL（例: `http://localhost/public/`）にアクセスする。
9. (`http://localhost/public/index.php`)がメインページである。
以上でセットアップは完了となる。# furima_app