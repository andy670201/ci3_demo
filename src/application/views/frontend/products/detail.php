<style>
.product-img-box { height: 420px; border-radius: 20px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); display:flex; align-items:center; justify-content:center; }
.size-btn, .color-btn { min-width: 50px; border: 2px solid #dee2e6; background: #fff; border-radius: 8px; padding: .35rem .75rem; cursor: pointer; transition: all .2s; }
.size-btn.selected, .color-btn.selected { border-color: var(--orange); color: var(--orange); background: #fff3e0; }
.related-card { border: none; border-radius: 12px; overflow: hidden; transition: transform .2s; }
.related-card:hover { transform: translateY(-4px); }
.related-img { height: 180px; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #f8f9fa, #e9ecef); }
</style>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-decoration-none" style="color:var(--orange)">首頁</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('products') ?>" class="text-decoration-none" style="color:var(--orange)">商品</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($product->name) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- 商品圖片 -->
        <div class="col-md-6">
            <div class="product-img-box">
                <div class="text-center">
                    <i class="bi bi-image" style="font-size:6rem; color:var(--orange); opacity:.3"></i>
                    <p class="text-muted mt-3"><?= htmlspecialchars($product->name) ?></p>
                </div>
            </div>
        </div>

        <!-- 商品資訊 -->
        <div class="col-md-6">
            <p class="text-muted mb-2"><i class="bi bi-tag me-1"></i><?= htmlspecialchars($product->category_name) ?></p>
            <h1 class="fw-bold mb-3"><?= htmlspecialchars($product->name) ?></h1>
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="fs-2 fw-bold" style="color:var(--orange)">NT$ <?= number_format($product->price) ?></span>
                <?php if ($product->original_price && $product->price < $product->original_price): ?>
                <span class="fs-5 text-muted text-decoration-line-through">NT$ <?= number_format($product->original_price) ?></span>
                <span class="badge" style="background:var(--orange)">
                    省 NT$ <?= number_format($product->original_price - $product->price) ?>
                </span>
                <?php endif; ?>
            </div>
            <p class="text-muted mb-4"><?= htmlspecialchars($product->description) ?></p>

            <?php
            $sizes = array_unique(array_column((array)$variants, 'size'));
            $colors = array_unique(array_column((array)$variants, 'color'));
            $sizes = array_filter($sizes);
            $colors = array_filter($colors);
            ?>

            <!-- 尺寸選擇 -->
            <?php if (!empty($sizes)): ?>
            <div class="mb-4">
                <p class="fw-semibold mb-2">尺寸</p>
                <div class="d-flex flex-wrap gap-2" id="sizeGroup">
                    <?php foreach ($sizes as $s): ?>
                    <button class="size-btn" onclick="selectVariant(this, 'size')" data-value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="selectedSize" value="">
            </div>
            <?php endif; ?>

            <!-- 顏色選擇 -->
            <?php if (!empty($colors)): ?>
            <div class="mb-4">
                <p class="fw-semibold mb-2">顏色</p>
                <div class="d-flex flex-wrap gap-2" id="colorGroup">
                    <?php foreach ($colors as $c): ?>
                    <button class="color-btn" onclick="selectVariant(this, 'color')" data-value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="selectedColor" value="">
            </div>
            <?php endif; ?>

            <!-- 數量 -->
            <div class="mb-4">
                <p class="fw-semibold mb-2">數量</p>
                <div class="input-group" style="width:140px">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">−</button>
                    <input type="number" class="form-control text-center" id="qty" value="1" min="1" max="99">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                </div>
            </div>

            <!-- 庫存 -->
            <p class="text-muted small mb-4"><i class="bi bi-box-seam me-1"></i>庫存：<?= $product->stock ?> 件</p>

            <!-- 加入購物車 -->
            <button class="btn btn-orange btn-lg w-100 py-3" onclick="addToCart()">
                <i class="bi bi-bag-plus me-2"></i>加入購物車
            </button>
            <a href="<?= base_url('products') ?>" class="btn btn-outline-secondary w-100 mt-2">
                <i class="bi bi-arrow-left me-2"></i>繼續購物
            </a>
        </div>
    </div>

    <!-- 相關商品 -->
    <?php if (!empty($related)): ?>
    <div class="mt-5 pt-4">
        <h4 class="fw-bold mb-4">同類商品</h4>
        <div class="row g-4">
            <?php foreach ($related as $r): if ($r->id == $product->id) continue; ?>
            <div class="col-6 col-md-3">
                <div class="related-card card">
                    <a href="<?= base_url('products/' . $r->id) ?>" class="text-decoration-none">
                        <div class="related-img"><i class="bi bi-image" style="font-size:3rem; color:var(--orange); opacity:.3"></i></div>
                    </a>
                    <div class="card-body p-3">
                        <h6 class="fw-semibold mb-1 small"><a href="<?= base_url('products/' . $r->id) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($r->name) ?></a></h6>
                        <span style="color:var(--orange)" class="fw-bold">NT$ <?= number_format($r->price) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
var productId = <?= $product->id ?>;

function selectVariant(btn, type) {
    var group = type === 'size' ? document.getElementById('sizeGroup') : document.getElementById('colorGroup');
    var input = type === 'size' ? document.getElementById('selectedSize') : document.getElementById('selectedColor');
    group.querySelectorAll('button').forEach(function(b){ b.classList.remove('selected'); });
    btn.classList.add('selected');
    input.value = btn.dataset.value;
}

function changeQty(delta) {
    var input = document.getElementById('qty');
    var v = parseInt(input.value) + delta;
    if (v < 1) v = 1;
    if (v > 99) v = 99;
    input.value = v;
}

function addToCart() {
    var size  = document.getElementById('selectedSize') ? document.getElementById('selectedSize').value : '';
    var color = document.getElementById('selectedColor') ? document.getElementById('selectedColor').value : '';
    var qty   = document.getElementById('qty').value;
    fetch('<?= base_url('cart/add') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + productId + '&size=' + encodeURIComponent(size) + '&color=' + encodeURIComponent(color) + '&quantity=' + qty
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
        if (data.success) {
            showToast(data.message, 'success');
            var badge = document.querySelector('.cart-badge');
            if (badge) badge.textContent = data.cart_count;
            else {
                var cartBtn = document.querySelector('a[href*="cart"]');
                if (cartBtn) {
                    var b = document.createElement('span');
                    b.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge';
                    b.style.background = 'var(--orange)';
                    b.textContent = data.cart_count;
                    cartBtn.style.position = 'relative';
                    cartBtn.appendChild(b);
                }
            }
        } else {
            if (data.redirect) { window.location.href = data.redirect; }
            else showToast(data.message, 'danger');
        }
    });
}
</script>
