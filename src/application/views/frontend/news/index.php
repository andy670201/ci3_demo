<div class="container py-5">
    <div class="mb-5">
        <h2 class="fw-bold">最新消息</h2>
        <p class="text-muted">掌握 CI3 Fashion 最新品牌動態與優惠資訊</p>
    </div>

    <div class="row g-4">
        <?php if (!empty($news)): foreach ($news as $n): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm" style="border:none; border-radius:16px; transition:transform .3s">
                <div class="card-body p-4">
                    <p class="small text-muted mb-2"><i class="bi bi-calendar3 me-1"></i><?= date('Y/m/d', strtotime($n->published_at)) ?></p>
                    <h5 class="fw-bold mb-3"><?= htmlspecialchars($n->title) ?></h5>
                    <p class="text-muted small mb-4"><?= htmlspecialchars($n->summary) ?></p>
                </div>
                <div class="card-footer bg-white border-0 pb-4 px-4">
                    <a href="<?= base_url('news/' . $n->id) ?>" class="btn btn-outline-orange btn-sm">閱讀更多 <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="col-12 text-center py-5 text-muted">目前沒有消息</div>
        <?php endif; ?>
    </div>

    <!-- 分頁 -->
    <?php
    $total_pages = ceil($total / $per_page);
    if ($total_pages > 1):
    ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>
<style>
.btn-outline-orange { border-color: var(--orange); color: var(--orange); }
.btn-outline-orange:hover { background: var(--orange); color: #fff; }
.page-link { color: var(--orange); }
.page-item.active .page-link { background-color: var(--orange); border-color: var(--orange); }
</style>
