<?php $success = $this->session->flashdata('success'); ?>
<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i><?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-orange"></i>商品管理</h4>
    <a href="<?= base_url('admin/products/create') ?>" class="btn btn-orange text-white">
        <i class="bi bi-plus-lg me-1"></i>新增商品
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">ID</th>
                        <th>商品名稱</th>
                        <th>分類</th>
                        <th>售價</th>
                        <th>庫存</th>
                        <th>狀態</th>
                        <th>精選</th>
                        <th style="width:160px">操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($products): foreach ($products as $p): ?>
                <tr>
                    <td class="text-muted small"><?= $p->id ?></td>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($p->name) ?></div>
                        <?php if ($p->original_price > $p->price): ?>
                        <small class="text-muted"><del>$<?= number_format($p->original_price) ?></del></small>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-light text-dark"><?= $p->category_name ?></span></td>
                    <td class="fw-semibold" style="color:#FF6B35;">$<?= number_format($p->price) ?></td>
                    <td><?= $p->stock ?></td>
                    <td>
                        <a href="<?= base_url('admin/products/toggle/'.$p->id) ?>" class="badge text-decoration-none <?= $p->is_active ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $p->is_active ? '上架中' : '已下架' ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($p->is_featured): ?>
                        <i class="bi bi-star-fill text-warning"></i>
                        <?php else: ?>
                        <i class="bi bi-star text-muted"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/products/edit/'.$p->id) ?>" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= base_url('admin/products/delete/'.$p->id) ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('確定要刪除「<?= htmlspecialchars($p->name) ?>」嗎？')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" class="text-center text-muted py-5">尚無商品</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
