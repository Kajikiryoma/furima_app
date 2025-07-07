<?php
// rakuten-search.php

// 楽天APIの基本情報
define('RAKUTEN_APP_ID', '1010388565407325408'); // ★ステップ1で取得したID

// フロントエンドからのキーワードを受け取る
$keyword = $_GET['keyword'] ?? '';

if (empty($keyword)) {
    // キーワードが空の場合はエラー
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Keyword is required.']);
    exit;
}

// 楽天APIへのリクエストURLを構築
$base_url = 'https://app.rakuten.co.jp/services/api/IchibaItem/Search/20220601';
$params = [
    'applicationId' => RAKUTEN_APP_ID,
    'format' => 'json',
    'keyword' => $keyword,
    'hits' => 5 // 検索結果は5件に絞る
];
$request_url = $base_url . '?' . http_build_query($params);

// APIを呼び出し、結果を取得（エラーを抑制しつつ）
$response_json = @file_get_contents($request_url);

// レスポンスをそのままフロントエンドに返す
header('Content-Type: application/json; charset=utf-8');
if ($response_json === false) {
    echo json_encode(['error' => 'Failed to fetch data from Rakuten API.']);
} else {
    echo $response_json;
}
?>