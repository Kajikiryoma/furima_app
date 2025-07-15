<?php
require '../includes/templates/header.php';
if (!isset($_SESSION['customer'])) {
    header('Location: login-input.php');
    exit();
}
?>

<div class="listing-container">

    <div class="listing-section">
        <h2 class="section-title">まず、出品する商品を検索</h2>
        <div class="section-content">
            <div class="rakuten-search-group">
                <input type="text" id="rakuten-keyword" class="form-input" placeholder="商品名やJANコードで検索">
                <button id="rakuten-search-btn">楽天で検索</button>
            </div>
            <div id="rakuten-results"></div>
        </div>
    </div>

    <form action="../includes/logic/listing-output.php" method="post" enctype="multipart/form-data" id="listing-form">
        <div id="form-errors" class="form-errors"></div>

        <div class="listing-section">
            <h2 class="section-title">商品の出品</h2>
            <div class="section-content">
                <div class="form-group">
                    <label class="form-label">出品画像 <span class="label-required">必須</span></label>
                    <label for="image" class="image-upload-box">
                        <div id="upload-box-content">
                            <p>クリックまたはドラッグ＆ドロップ</p>
                            <span class="dummy-button">画像を選択する</span>
                        </div>
                    </label>
                    <input type="file" id="image" name="image" required>
                </div>
            </div>
        </div>

        <div class="listing-section">
            <h2 class="section-title">商品の詳細</h2>
            <div class="section-content">
                <div class="form-group">
                    <label for="name" class="form-label">商品名 <span class="label-required">必須</span></label>
                    <input type="text" id="name" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">商品の説明 <span class="label-required">必須</span></label>
                    <textarea id="description" name="description" class="form-textarea" rows="5" required></textarea>
                </div>
            </div>
        </div>

        <div class="listing-section">
            <h2 class="section-title">販売価格</h2>
            <div class="section-content">
                <div id="price-errors" class="form-errors"></div>
                <p class="form-notice">当サービスは転売対策のため、参考価格（定価）以上の値段を設定することはできません。</p>
                <div class="form-group">
                    <label for="price" class="form-label">あなたの販売価格 <span class="label-required">必須</span></label>
                    <div class="price-input-group">
                        <span class="currency-symbol">¥</span>
                        <input type="number" id="price" name="price" class="form-input" placeholder="0" required min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label for="regular_price" class="form-label">参考価格（楽天での価格）</label>
                    <div class="price-input-group">
                        <span class="currency-symbol">¥</span>
                        <input type="number" id="regular_price" name="regular_price" class="form-input" readonly>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="form-notice">公平な取引のため、同一と見なされる商品の出品は3品までに制限されています。</p>
        <button type="submit" class="listing-submit-button">この内容で出品する</button>
    </form>
</div>

<style>
    .rakuten-search-group { display: flex; gap: 10px; }
    .rakuten-search-group input { flex-grow: 1; }
    #rakuten-results { margin-top: 15px; }
    .result-item { display: flex; align-items: center; gap: 15px; padding: 10px; border: 1px solid #eee; margin-bottom: 5px; border-radius: 4px; }
    .result-item img { width: 60px; height: 60px; object-fit: cover; }
    .result-item .info { flex-grow: 1; }
    .result-item .select-item-btn { padding: 5px 10px; cursor: pointer; }
    #upload-box-content img { max-width: 100%; max-height: 200px; object-fit: contain; }
    .form-errors {
        background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;
        padding: 1rem; border-radius: 4px; margin-bottom: 20px; display: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- 全体の要素を取得 ---
    const searchBtn = document.getElementById('rakuten-search-btn');
    const keywordInput = document.getElementById('rakuten-keyword');
    const resultsContainer = document.getElementById('rakuten-results');
    const nameInput = document.getElementById('name');
    const regularPriceInput = document.getElementById('regular_price');
    const descriptionInput = document.getElementById('description');
    const uploadBox = document.querySelector('.image-upload-box');
    const fileInput = document.getElementById('image');
    const uploadBoxContent = document.getElementById('upload-box-content');
    const listingForm = document.getElementById('listing-form');
    const generalErrorContainer = document.getElementById('form-errors');
    const priceErrorContainer = document.getElementById('price-errors');
    const submitButton = listingForm.querySelector('.listing-submit-button');

    // --- 機能1：楽天商品検索 ---
    searchBtn.addEventListener('click', async function() {
        const keyword = keywordInput.value.trim();
        if (!keyword) { alert('キーワードを入力してください。'); return; }
        resultsContainer.innerHTML = '検索中...';
        try {
            const response = await fetch(`/api/rakuten-search.php?keyword=${encodeURIComponent(keyword)}`);
            const data = await response.json();
            resultsContainer.innerHTML = '';
            if (data.Items && data.Items.length > 0) {
                data.Items.forEach(itemInfo => {
                    const item = itemInfo.Item;
                    const resultDiv = document.createElement('div');
                    resultDiv.className = 'result-item';
                    resultDiv.innerHTML = `
                        <img src="${item.smallImageUrls[0].imageUrl}" alt="">
                        <div class="info"><p>${item.itemName}</p><strong>¥${item.itemPrice.toLocaleString()}</strong></div>
                        <button type="button" class="select-item-btn">この商品で出品</button>
                    `;
                    resultDiv.querySelector('.select-item-btn').addEventListener('click', function() {
                        nameInput.value = item.itemName;
                        descriptionInput.value = item.itemCaption;
                        regularPriceInput.value = item.itemPrice;
                        resultsContainer.innerHTML = '<p><strong>商品情報が入力されました。残りの項目を入力してください。</strong></p>';
                    });
                    resultsContainer.appendChild(resultDiv);
                });
            } else {
                resultsContainer.innerHTML = '該当する商品が見つかりませんでした。';
            }
        } catch (error) {
            resultsContainer.innerHTML = '検索中にエラーが発生しました。';
            console.error(error);
        }
    });

    // --- 機能2：画像アップロードとプレビュー ---
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadBoxContent.innerHTML = `<img src="${e.target.result}" alt="プレビュー">`;
            }
            reader.readAsDataURL(file);
        }
    });
    uploadBox.addEventListener('dragover', (e) => { e.preventDefault(); uploadBox.classList.add('is-dragover'); });
    uploadBox.addEventListener('dragleave', (e) => { e.preventDefault(); uploadBox.classList.remove('is-dragover'); });
    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.classList.remove('is-dragover');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // --- 機能3：フォームの非同期送信とエラー表示 ---
    listingForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        generalErrorContainer.style.display = 'none';
        priceErrorContainer.style.display = 'none';
        submitButton.disabled = true;
        submitButton.textContent = '処理中...';
        const formData = new FormData(listingForm);
        try {
            const response = await fetch('/includes/logic/listing-output.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                window.location.href = result.redirect_url;
            } else {
                if (result.error_type === 'price') {
                    priceErrorContainer.textContent = result.message;
                    priceErrorContainer.style.display = 'block';
                } else {
                    generalErrorContainer.textContent = result.message;
                    generalErrorContainer.style.display = 'block';
                }
            }
        } catch (err) {
            generalErrorContainer.textContent = '通信中にエラーが発生しました。';
            generalErrorContainer.style.display = 'block';
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'この内容で出品する';
        }
    });

});
</script>

<?php require '../includes/templates/footer.php'; ?>