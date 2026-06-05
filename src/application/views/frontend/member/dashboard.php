<style>
.status-pending    { background: #FFF3E0; color: #E65100; }
.status-confirmed  { background: #E3F2FD; color: #0D47A1; }
.status-shipping   { background: #F3E5F5; color: #6A1B9A; }
.status-delivered  { background: #E8F5E9; color: #1B5E20; }
.status-cancelled  { background: #FFEBEE; color: #B71C1C; }
</style>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm" style="border-radius:16px; border:none">
                <div class="card-body p-4 text-center">
                    <div style="font-size:3.5rem; color:var(--orange)"><i class="bi bi-person-circle"></i></div>
                    <h5 class="fw-bold mt-3"><?= htmlspecialchars($member['name']) ?></h5>
                    <p class="text-muted small"><?= htmlspecialchars($member['email']) ?></p>
                    <?php if (!empty($member['phone'])): ?>
                    <p class="text-muted small"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($member['phone']) ?></p>
                    <?php endif; ?>
                    <hr>
                    <a href="<?= base_url('member/logout') ?>" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-box-arrow-right me-2"></i>登出
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h4 class="fw-bold mb-4">我的訂單</h4>
            <?php if (empty($orders)): ?>
            <div class="text-center py-4 text-muted">
                <i class="bi bi-receipt" style="font-size:3rem; color:#ccc"></i>
                <p class="mt-3">您還沒有任何訂單</p>
                <a href="<?= base_url('products') ?>" class="btn btn-orange">去購物</a>
            </div>
            <?php else: ?>
            <?php
            $status_labels = ['pending'=>'待確認','confirmed'=>'已確認','shipping'=>'運送中','delivered'=>'已到貨','cancelled'=>'已取消'];
            foreach ($orders as $o): ?>
            <div class="card shadow-sm mb-3" style="border-radius:12px; border:none">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="fw-bold mb-1"><?= $o->order_number ?></p>
                            <p class="text-muted small mb-0"><?= date('Y/m/d H:i', strtotime($o->created_at)) ?></p>
                        </div>
                        <div class="text-end">
                            <span class="badge rounded-pill status-<?= $o->status ?>"><?= $status_labels[$o->status] ?? $o->status ?></span>
                            <p class="fw-bold mt-1" style="color:var(--orange)">NT$ <?= number_format($o->total_amount) ?></p>
                        </div>
                    </div>
                    <a href="<?= base_url('member/orders/' . $o->order_number) ?>" class="btn btn-sm btn-outline-secondary mt-2">查看詳情</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
