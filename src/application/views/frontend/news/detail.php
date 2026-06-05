<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" style="color:var(--orange)" class="text-decoration-none">首頁</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('news') ?>" style="color:var(--orange)" class="text-decoration-none">最新消息</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($article->title) ?></li>
        </ol>
    </nav>
    <div class="row g-4">
        <div class="col-lg-8">
            <article class="card shadow-sm" style="border:none; border-radius:16px">
                <div class="card-body p-5">
                    <p class="text-muted small mb-2"><i class="bi bi-calendar3 me-1"></i><?= date('Y/m/d', strtotime($article->published_at)) ?></p>
                    <h2 class="fw-bold mb-4"><?= htmlspecialchars($article->title) ?></h2>
                    <hr>
                    <div class="mt-4" style="line-height:1.9"><?= $article->content ?></div>
                </div>
            </article>
            <a href="<?= base_url('news') ?>" class="btn btn-outline-secondary mt-4">
                <i class="bi bi-arrow-left me-2"></i>返回消息列表
            </a>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm" style="border:none; border-radius:16px">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">最新消息</h6>
                    <?php foreach ($recent_news as $n): ?>
                    <div class="mb-3 pb-3 border-bottom">
                        <p class="text-muted small mb-1"><i class="bi bi-calendar3 me-1"></i><?= date('Y/m/d', strtotime($n->published_at)) ?></p>
                        <a href="<?= base_url('news/' . $n->id) ?>" class="text-decoration-none fw-semibold" style="color:var(--dark); font-size:.9rem;"><?= htmlspecialchars($n->title) ?></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
