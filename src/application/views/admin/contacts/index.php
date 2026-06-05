<?php $success = $this->session->flashdata('success'); ?>
<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i><?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-envelope me-2 text-orange"></i>聯絡訊息</h4>
    <span class="text-muted small">共 <?= count($messages) ?> 則訊息</span>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th></th><th>寄件人</th><th>主旨</th><th>電話</th><th>時間</th><th>操作</th></tr>
                </thead>
                <tbody>
                <?php if ($messages): foreach ($messages as $m): ?>
                <tr class="<?= !$m->is_read ? 'table-warning' : '' ?>">
                    <td>
                        <?php if (!$m->is_read): ?>
                        <span class="badge bg-danger">新</span>
                        <?php else: ?>
                        <i class="bi bi-check2 text-muted"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="fw-semibold <?= !$m->is_read ? '' : 'text-muted' ?>"><?= htmlspecialchars($m->name) ?></div>
                        <div class="small text-muted"><?= htmlspecialchars($m->email) ?></div>
                    </td>
                    <td class="<?= !$m->is_read ? 'fw-semibold' : 'text-muted' ?>"><?= htmlspecialchars($m->subject) ?></td>
                    <td class="text-muted small"><?= $m->phone ? htmlspecialchars($m->phone) : '—' ?></td>
                    <td class="text-muted small"><?= date('Y/m/d H:i', strtotime($m->created_at)) ?></td>
                    <td>
                        <a href="<?= base_url('admin/contacts/'.$m->id) ?>" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-eye me-1"></i>查看
                        </a>
                        <a href="<?= base_url('admin/contacts/delete/'.$m->id) ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('確定刪除此訊息？')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center text-muted py-5">尚無訊息</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
