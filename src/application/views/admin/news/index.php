<?php $success = $this->session->flashdata('success'); ?>
<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i><?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-newspaper me-2 text-orange"></i>消息管理</h4>
    <a href="<?= base_url('admin/news/create') ?>" class="btn btn-orange text-white">
        <i class="bi bi-plus-lg me-1"></i>新增文章
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>標題</th><th>摘要</th><th>狀態</th><th>發布時間</th><th style="width:160px">操作</th></tr>
                </thead>
                <tbody>
                <?php if ($news): foreach ($news as $n): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($n->title) ?></td>
                    <td class="text-muted small" style="max-width:200px;">
                        <div class="text-truncate"><?= htmlspecialchars($n->summary) ?></div>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/news/toggle/'.$n->id) ?>" class="badge text-decoration-none <?= $n->is_published ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $n->is_published ? '已發布' : '草稿' ?>
                        </a>
                    </td>
                    <td class="text-muted small">
                        <?= $n->published_at ? date('Y/m/d', strtotime($n->published_at)) : '—' ?>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/news/edit/'.$n->id) ?>" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= base_url('admin/news/delete/'.$n->id) ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('確定刪除此文章？')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center text-muted py-5">尚無文章</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>.btn-orange{background:#FF6B35;border:none;}.btn-orange:hover{background:#e55a25;}</style>
