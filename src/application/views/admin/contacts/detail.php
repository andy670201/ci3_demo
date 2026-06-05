<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-envelope-open me-2 text-orange"></i>訊息詳情</h4>
    <a href="<?= base_url('admin/contacts') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>返回列表
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="fw-bold mb-0"><?= htmlspecialchars($message->subject) ?></h5>
            </div>
            <div class="card-body">
                <div class="p-3 bg-light rounded-3 mb-3" style="white-space:pre-wrap;"><?= htmlspecialchars($message->message) ?></div>
                <div class="d-flex gap-2">
                    <a href="mailto:<?= htmlspecialchars($message->email) ?>?subject=Re: <?= urlencode($message->subject) ?>"
                       class="btn btn-orange text-white">
                        <i class="bi bi-reply me-2"></i>回覆 Email
                    </a>
                    <a href="<?= base_url('admin/contacts/delete/'.$message->id) ?>" class="btn btn-outline-danger"
                       onclick="return confirm('確定刪除此訊息？')">
                        <i class="bi bi-trash me-1"></i>刪除
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">寄件人資訊</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="text-muted small mb-1"><i class="bi bi-person me-1"></i>姓名</div>
                        <div class="fw-semibold"><?= htmlspecialchars($message->name) ?></div>
                    </li>
                    <li class="mb-3">
                        <div class="text-muted small mb-1"><i class="bi bi-envelope me-1"></i>Email</div>
                        <div><a href="mailto:<?= htmlspecialchars($message->email) ?>" style="color:#FF6B35;"><?= htmlspecialchars($message->email) ?></a></div>
                    </li>
                    <?php if ($message->phone): ?>
                    <li class="mb-3">
                        <div class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>電話</div>
                        <div><?= htmlspecialchars($message->phone) ?></div>
                    </li>
                    <?php endif; ?>
                    <li>
                        <div class="text-muted small mb-1"><i class="bi bi-clock me-1"></i>收到時間</div>
                        <div><?= date('Y/m/d H:i', strtotime($message->created_at)) ?></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>.btn-orange{background:#FF6B35;border:none;}.btn-orange:hover{background:#e55a25;}</style>
