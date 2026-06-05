<style>
.product-card { border: none; border-radius: 16px; overflow: hidden; transition: transform .3s, box-shadow .3s; }
.product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
.product-img { height: 220px; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #f8f9fa, #e9ecef); }
.filter-btn { border-radius: 50px; padding: .4rem 1.2rem; }
.filter-btn.active { background: var(--orange); border-color: var(--orange); color: #fff; }
</style>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <?php if (!empty($current_category)): ?>
                <?= isset($category) ? htmlspecialchars($category->name) : '商品' ?>
                <?php else: ?>
                全部商品
                <?php endif; ?>
            </h2>
            <p class="text-muted">共 <?= $total ?> 件商品</p>
        </div>
    </div>

    <!-- 分類篩選 -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="<?= base_url('products') ?>" class="btn btn-outline-secondary filter-btn <?= empty($current_category) ? 'active' : '' ?>">全部</a>
        <?php if (!empty($categories)): foreach ($categories as $cat): ?>
        <a href="<?= base_url('products/category/' . $cat->slug) ?>" class="btn btn-outline-secondary filter-btn <?= $current_category == $cat->slug ? 'active' : '' ?>">
            <?= htmlspecialchars($cat->name) ?>
        </a>
        <?php endforeach; endif; ?>
    </div>

    <div class="row g-4">
        <?php if (!empty($products)): foreach ($products as $p): ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card card h-100 position-relative">
                <?php if ($p->original_price && $p->price < $p->original_price): ?>
                <span class="badge rounded-pill position-absolute" style="background:var(--orange); top:1rem; right:1rem;">特價</span>
                <?php endif; ?>
                <a href="<?= base_url('products/' . $p->id) ?>" class="text-decoration-none">
                    <div class="product-img">
                        <i class="bi bi-image" style="font-size:3.5rem; color:var(--orange); opacity:.3"></i>
                    </div>
                </a>
                <div class="card-body">
                    <p class="small text-muted mb-1"><?= htmlspecialchars($p->category_name) ?></p>
                    <h6 class="fw-bold mb-2">
                        <a href="<?= base_url('products/' . $p->id) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($p->name) ?></a>
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="color:var(--orange)">NT$ <?= number_format($p->price) ?></span>
                        <?php if ($p->original_price && $p->price < $p->original_price): ?>
                        <span class="small text-muted text-decoration-line-through">NT$ <?= number_format($p->original_price) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-3 pt-0">
                    <a href="<?= base_url('products/' . $p->id) ?>" class="btn btn-orange w-100 btn-sm">查看詳情</a>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox" style="font-size:4rem; color:#ccc"></i>
            <p class="text-muted mt-3">此分類目前沒有商品</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- 分頁 -->
    <?php
    $total_pages = ceil($total / $per_page);
    if ($total_pages > 1):
    ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>"><i class="bi bi-chevron-left"></i></a>
            </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>"><i class="bi bi-chevron-right"></i></a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<style>
.page-link { color: var(--orange); }
.page-item.active .page-link { background-color: var(--orange); border-color: var(--orange); color: #fff; }
</style>
