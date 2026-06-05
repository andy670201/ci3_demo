<?php
$status_map = [
    'pending'   => ['待確認', 'warning'],
    'confirmed' => ['已確認', 'primary'],
    'shipping'  => ['配送中', 'info'],
    'delivered' => ['已送達', 'success'],
    'cancelled' => ['已取消', 'secondary'],
];
$success = $this->session->flashdata('success');
?>
<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i><?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-orange"></i>訂單詳情</h4>
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>返回列表
    </a>
</div>

<div class="row g-4">
    <!-- 訂單資訊 -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0">訂單商品</h6>
            </div>
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>商品</th><th>規格</th><th>單價</th><th>數量</th><th>小計</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($item->product_name) ?></td>
                        <td class="text-muted small">
                            <?= $item->size ? $item->size : '' ?>
                            <?= $item->color ? ' / '.$item->color : '' ?>
                        </td>
                        <td>$<?= number_format($item->price) ?></td>
                        <td>×<?= $item->quantity ?></td>
                        <td class="fw-semibold" style="color:#FF6B35;">$<?= number_format($item->price * $item->quantity) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">訂單總額</td>
                            <td class="fw-bold fs-5" style="color:#FF6B35;">$<?= number_format($order->total_amount) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- 收件人資訊 -->
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">收件人資訊</h6></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <span class="text-muted small">姓名：</span>
                        <span class="fw-semibold"><?= htmlspecialchars($order->recipient_name) ?></span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted small">電話：</span>
                        <span class="fw-semibold"><?= htmlspecialchars($order->recipient_phone) ?></span>
                    </div>
                    <div class="col-12">
                        <span class="text-muted small">地址：</span>
                        <span class="fw-semibold"><?= htmlspecialchars($order->recipient_address) ?></span>
                    </div>
                    <?php if ($order->note): ?>
                    <div class="col-12">
                        <span class="text-muted small">備註：</span>
                        <span><?= htmlspecialchars($order->note) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 側欄：狀態 -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">訂單狀態</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small mb-1">訂單編號</div>
                    <div class="fw-bold" style="color:#FF6B35;"><?= $order->order_number ?></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small mb-1">建立時間</div>
                    <div><?= date('Y/m/d H:i', strtotime($order->created_at)) ?></div>
                </div>
                <div class="mb-4">
                    <div class="text-muted small mb-1">目前狀態</div>
                    <?php $s = $status_map[$order->status] ?? [$order->status,'secondary']; ?>
                    <span class="badge bg-<?= $s[1] ?> fs-6"><?= $s[0] ?></span>
                </div>

                <hr>
                <div class="text-muted small mb-2 fw-semibold">更新訂單狀態</div>
                <?= form_open('admin/orders/status/'.$order->id) ?>
                <select name="status" class="form-select mb-2">
                    <?php foreach ($status_map as $val => $label): ?>
                    <option value="<?= $val ?>" <?= $order->status === $val ? 'selected' : '' ?>><?= $label[0] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-orange text-white w-100">
                    <i class="bi bi-arrow-repeat me-1"></i>更新狀態
                </button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<style>.btn-orange{background:#FF6B35;border:none;}.btn-orange:hover{background:#e55a25;}</style>
