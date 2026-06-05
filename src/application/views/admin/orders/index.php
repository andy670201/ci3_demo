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
    <h4 class="fw-bold mb-0"><i class="bi bi-bag-check me-2 text-orange"></i>訂單管理</h4>
    <span class="text-muted small">共 <?= count($orders) ?> 筆訂單</span>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>訂單編號</th>
                        <th>收件人</th>
                        <th>金額</th>
                        <th>狀態</th>
                        <th>建立時間</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($orders): foreach ($orders as $o): ?>
                <?php $s = $status_map[$o->status] ?? [$o->status, 'secondary']; ?>
                <tr>
                    <td><span class="fw-semibold" style="color:#FF6B35;"><?= $o->order_number ?></span></td>
                    <td><?= htmlspecialchars($o->recipient_name) ?></td>
                    <td class="fw-semibold">$<?= number_format($o->total_amount) ?></td>
                    <td><span class="badge bg-<?= $s[1] ?>"><?= $s[0] ?></span></td>
                    <td class="text-muted small"><?= date('Y/m/d H:i', strtotime($o->created_at)) ?></td>
                    <td>
                        <a href="<?= base_url('admin/orders/'.$o->id) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>詳情
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center text-muted py-5">尚無訂單</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
