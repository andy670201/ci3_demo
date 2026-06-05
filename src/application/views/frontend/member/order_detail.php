<div class="container py-5">
    <a href="<?= base_url('member/dashboard') ?>" class="btn btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-2"></i>返回我的帳戶
    </a>

    <?php
    $status_labels = ['pending'=>'待確認','confirmed'=>'已確認','shipping'=>'運送中','delivered'=>'已到貨','cancelled'=>'已取消'];
    $status_colors = ['pending'=>'warning','confirmed'=>'primary','shipping'=>'info','delivered'=>'success','cancelled'=>'danger'];
    ?>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm" style="border-radius:16px; border:none">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">訂單詳情</h5>
                        <span class="badge bg-<?= $status_colors[$order->status] ?? 'secondary' ?>"><?= $status_labels[$order->status] ?? $order->status ?></span>
                    </div>
                    <table class="table">
                        <thead class="table-light"><tr><th>商品</th><th>規格</th><th>單價</th><th>數量</th><th class="text-end">小計</th></tr></thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item->product_name) ?></td>
                                <td class="small text-muted"><?= $item->size ?> <?= $item->color ?></td>
                                <td>NT$ <?= number_format($item->price) ?></td>
                                <td><?= $item->quantity ?></td>
                                <td class="text-end fw-semibold">NT$ <?= number_format($item->price * $item->quantity) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">總計</td>
                                <td class="text-end fw-bold" style="color:var(--orange)">NT$ <?= number_format($order->total_amount) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm" style="border-radius:16px; border:none">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">收件資訊</h6>
                    <p class="mb-2"><span class="text-muted small">訂單編號</span><br><strong><?= $order->order_number ?></strong></p>
                    <p class="mb-2"><span class="text-muted small">下單時間</span><br><?= date('Y/m/d H:i', strtotime($order->created_at)) ?></p>
                    <p class="mb-2"><span class="text-muted small">收件人</span><br><?= htmlspecialchars($order->recipient_name) ?></p>
                    <p class="mb-2"><span class="text-muted small">電話</span><br><?= htmlspecialchars($order->recipient_phone) ?></p>
                    <p class="mb-0"><span class="text-muted small">地址</span><br><?= htmlspecialchars($order->recipient_address) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
