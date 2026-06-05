<div class="container py-5">
    <div class="text-center py-5">
        <div class="mb-4" style="font-size:5rem; color:var(--orange)">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h2 class="fw-bold mb-3">訂單成立成功！</h2>
        <?php if ($order): ?>
        <p class="text-muted mb-2">訂單編號：<strong style="color:var(--orange)"><?= $order->order_number ?></strong></p>
        <p class="text-muted mb-4">感謝您的購買，我們將盡快為您處理訂單。</p>
        <div class="card shadow-sm mx-auto" style="max-width:400px; border-radius:16px; border:none">
            <div class="card-body p-4 text-start">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">收件人</span>
                    <span class="fw-semibold"><?= htmlspecialchars($order->recipient_name) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">電話</span>
                    <span class="fw-semibold"><?= htmlspecialchars($order->recipient_phone) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">訂單金額</span>
                    <span class="fw-bold" style="color:var(--orange)">NT$ <?= number_format($order->total_amount) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">訂單狀態</span>
                    <span class="badge" style="background:var(--orange)">待確認</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="d-flex gap-3 justify-content-center mt-4">
            <a href="<?= base_url('member/dashboard') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-list-ul me-2"></i>查看訂單
            </a>
            <a href="<?= base_url('products') ?>" class="btn btn-orange">
                <i class="bi bi-bag2 me-2"></i>繼續購物
            </a>
        </div>
    </div>
</div>
