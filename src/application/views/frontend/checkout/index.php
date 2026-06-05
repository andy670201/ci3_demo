<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-credit-card me-2" style="color:var(--orange)"></i>結帳</h2>

    <div class="row g-4">
        <!-- 收件人資料 -->
        <div class="col-lg-7">
            <div class="card shadow-sm" style="border-radius:16px; border:none">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">收件人資料</h5>
                    <form method="POST" action="<?= base_url('checkout/confirm') ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">收件人姓名 <span class="text-danger">*</span></label>
                            <input type="text" name="recipient_name" class="form-control" value="<?= htmlspecialchars($member['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">聯絡電話 <span class="text-danger">*</span></label>
                            <input type="text" name="recipient_phone" class="form-control" value="<?= isset($member['phone']) ? htmlspecialchars($member['phone']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">收件地址 <span class="text-danger">*</span></label>
                            <textarea name="recipient_address" class="form-control" rows="3" required><?= isset($member['address']) ? htmlspecialchars($member['address']) : '' ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">備註</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="如有特殊需求請在此說明"></textarea>
                        </div>
                        <div class="p-3 rounded mb-4" style="background:var(--orange-pale)">
                            <p class="mb-0 small"><i class="bi bi-credit-card me-2" style="color:var(--orange)"></i>付款方式：<strong>貨到付款</strong></p>
                        </div>
                        <button type="submit" class="btn btn-orange btn-lg w-100">
                            <i class="bi bi-check-circle me-2"></i>確認下單
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 訂單摘要 -->
        <div class="col-lg-5">
            <div class="card shadow-sm" style="border-radius:16px; border:none">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">訂單商品</h5>
                    <?php foreach ($cart as $item): ?>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="fw-semibold mb-0 small"><?= htmlspecialchars($item['name']) ?></p>
                            <p class="text-muted mb-0" style="font-size:.8rem;">
                                <?= $item['size'] ? '尺寸：' . htmlspecialchars($item['size']) : '' ?>
                                <?= $item['color'] ? '　顏色：' . htmlspecialchars($item['color']) : '' ?>
                                　x <?= $item['quantity'] ?>
                            </p>
                        </div>
                        <span class="fw-semibold">NT$ <?= number_format($item['price'] * $item['quantity']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">總計</span>
                        <span class="fw-bold fs-5" style="color:var(--orange)">NT$ <?= number_format($total) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
