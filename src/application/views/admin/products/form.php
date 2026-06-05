<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-<?= $product ? 'pencil-square' : 'plus-circle' ?> me-2 text-orange"></i>
        <?= $product ? '編輯商品' : '新增商品' ?>
    </h4>
    <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>返回列表
    </a>
</div>

<?php $action = $product ? base_url('admin/products/update/'.$product->id) : base_url('admin/products/store'); ?>
<?= form_open($action) ?>

<div class="row g-4">
    <!-- 左欄：基本資訊 -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">基本資訊</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">商品名稱 <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= $product ? htmlspecialchars($product->name) : '' ?>" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">分類 <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id ?>" <?= ($product && $product->category_id == $cat->id) ? 'selected' : '' ?>>
                                <?= $cat->name ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">售價 <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="price" class="form-control" value="<?= $product ? $product->price : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">原價</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="original_price" class="form-control" value="<?= $product ? $product->original_price : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">總庫存</label>
                    <input type="number" name="stock" class="form-control" value="<?= $product ? $product->stock : 0 ?>">
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">商品描述</label>
                    <textarea name="description" class="form-control" rows="5"><?= $product ? htmlspecialchars($product->description) : '' ?></textarea>
                </div>
            </div>
        </div>

        <!-- 商品規格 -->
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">商品規格</h6>
                <button type="button" class="btn btn-sm btn-orange text-white" id="addVariant">
                    <i class="bi bi-plus me-1"></i>新增規格
                </button>
            </div>
            <div class="card-body">
                <table class="table table-sm align-middle mb-0" id="variantTable">
                    <thead class="table-light">
                        <tr><th>尺寸</th><th>顏色</th><th>庫存</th><th></th></tr>
                    </thead>
                    <tbody id="variantBody">
                    <?php if ($variants): foreach ($variants as $v): ?>
                    <tr>
                        <td><input type="text" name="sizes[]" class="form-control form-control-sm" value="<?= htmlspecialchars($v->size) ?>"></td>
                        <td><input type="text" name="colors[]" class="form-control form-control-sm" value="<?= htmlspecialchars($v->color) ?>"></td>
                        <td><input type="number" name="variant_stocks[]" class="form-control form-control-sm" value="<?= $v->stock ?>"></td>
                        <td><button type="button" class="btn btn-sm btn-outline-danger removeVariant"><i class="bi bi-x"></i></button></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr id="emptyRow"><td colspan="4" class="text-center text-muted py-3 small">尚未新增規格</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 右欄：狀態設定 -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">狀態設定</h6></div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                        <?= (!$product || $product->is_active) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-semibold" for="isActive">上架販售</label>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1"
                        <?= ($product && $product->is_featured) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-semibold" for="isFeatured">精選商品（首頁顯示）</label>
                </div>
                <button type="submit" class="btn btn-orange text-white w-100 py-2 fw-semibold">
                    <i class="bi bi-check-lg me-2"></i><?= $product ? '儲存變更' : '建立商品' ?>
                </button>
                <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary w-100 mt-2">取消</a>
            </div>
        </div>
    </div>
</div>

<?= form_close() ?>

<style>
.btn-orange { background:#FF6B35; border:none; }
.btn-orange:hover { background:#e55a25; }
</style>

<script>
document.getElementById('addVariant').addEventListener('click', function() {
    const empty = document.getElementById('emptyRow');
    if (empty) empty.remove();
    const row = document.createElement('tr');
    row.innerHTML = `<td><input type="text" name="sizes[]" class="form-control form-control-sm" placeholder="例：M"></td>
                     <td><input type="text" name="colors[]" class="form-control form-control-sm" placeholder="例：白色"></td>
                     <td><input type="number" name="variant_stocks[]" class="form-control form-control-sm" value="0"></td>
                     <td><button type="button" class="btn btn-sm btn-outline-danger removeVariant"><i class="bi bi-x"></i></button></td>`;
    document.getElementById('variantBody').appendChild(row);
});
document.getElementById('variantBody').addEventListener('click', function(e) {
    if (e.target.closest('.removeVariant')) {
        e.target.closest('tr').remove();
    }
});
</script>
