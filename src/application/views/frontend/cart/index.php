<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2" style="color:var(--orange)"></i>購物車</h2>

    <?php if (empty($cart)): ?>
    <div class="text-center py-5">
        <i class="bi bi-cart-x" style="font-size:5rem; color:#ccc"></i>
        <h4 class="mt-4 text-muted">購物車是空的</h4>
        <p class="text-muted">快去選購您喜歡的商品吧！</p>
        <a href="<?= base_url('products') ?>" class="btn btn-orange mt-2">
            <i class="bi bi-bag2 me-2"></i>去逛逛
        </a>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm" style="border-radius:16px; border:none">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 ps-4">商品</th>
                                    <th class="py-3">規格</th>
                                    <th class="py-3">單價</th>
                                    <th class="py-3">數量</th>
                                    <th class="py-3">小計</th>
                                    <th class="py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $key => $item): ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <span class="fw-semibold"><?= htmlspecialchars($item['name']) ?></span>
                                    </td>
                                    <td class="text-muted small">
                                        <?php if ($item['size']): ?><span class="badge bg-light text-dark me-1"><?= htmlspecialchars($item['size']) ?></span><?php endif; ?>
                                        <?php if ($item['color']): ?><span class="badge bg-light text-dark"><?= htmlspecialchars($item['color']) ?></span><?php endif; ?>
                                    </td>
                                    <td>NT$ <?= number_format($item['price']) ?></td>
                                    <td>
                                        <form method="POST" action="<?= base_url('cart/update') ?>" class="d-flex align-items-center gap-1">
                                            <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                                            <input type="number" name="quantity" class="form-control form-control-sm text-center" style="width:65px" value="<?= $item['quantity'] ?>" min="1" max="99" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="fw-bold" style="color:var(--orange)">NT$ <?= number_format($item['price'] * $item['quantity']) ?></td>
                                    <td>
                                        <a href="<?= base_url('cart/remove/' . $item['product_id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('確定移除？')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <a href="<?= base_url('products') ?>" class="btn btn-outline-secondary mt-3">
                <i class="bi bi-arrow-left me-2"></i>繼續購物
            </a>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm" style="border-radius:16px; border:none">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">訂單摘要</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">商品合計</span>
                        <span>NT$ <?= number_format($total) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">運費</span>
                        <span class="text-success">免費</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">總計</span>
                        <span class="fw-bold fs-5" style="color:var(--orange)">NT$ <?= number_format($total) ?></span>
                    </div>
                    <?php if (!empty($member)): ?>
                    <a href="<?= base_url('checkout') ?>" class="btn btn-orange w-100 btn-lg">
                        <i class="bi bi-credit-card me-2"></i>前往結帳
                    </a>
                    <?php else: ?>
                    <a href="<?= base_url('member/login') ?>" class="btn btn-orange w-100 btn-lg">
                        <i class="bi bi-person me-2"></i>登入後結帳
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
