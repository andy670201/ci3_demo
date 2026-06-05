<style>
.hero { background: linear-gradient(135deg, #1A1A2E 0%, #16213E 40%, #FF6B35 100%); min-height: 85vh; display:flex; align-items:center; }
.category-card { border: none; border-radius: 16px; overflow: hidden; transition: transform .3s, box-shadow .3s; cursor: pointer; }
.category-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(255,107,53,0.25); }
.category-icon { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; background: var(--orange-pale); color: var(--orange); }
.product-card { border: none; border-radius: 16px; overflow: hidden; transition: transform .3s, box-shadow .3s; }
.product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
.product-img { height: 250px; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #f8f9fa, #e9ecef); color: var(--orange); }
.badge-sale { background: var(--orange); position: absolute; top: 1rem; right: 1rem; }
.news-card { border: none; border-radius: 16px; transition: transform .3s; }
.news-card:hover { transform: translateY(-4px); }
.section-title { font-weight: 700; position: relative; display: inline-block; }
.section-title::after { content: ''; display: block; width: 50px; height: 3px; background: var(--orange); margin-top: .5rem; }
.cta-section { background: linear-gradient(135deg, var(--orange), var(--orange-light)); }
</style>

<!-- Hero Banner -->
<section class="hero">
    <div class="container text-center text-white py-5">
        <p class="text-uppercase tracking-wider mb-3" style="color:rgba(255,255,255,0.7); letter-spacing:3px; font-size:.85rem;">2024 秋冬系列</p>
        <h1 class="display-3 fw-bold mb-4">都會浪漫<br><span style="color:var(--orange)">時尚新美學</span></h1>
        <p class="lead mb-5" style="color:rgba(255,255,255,0.8); max-width:500px; margin:0 auto;">精選服裝，展現您獨特的時尚品味<br>從日常到特殊場合，找到屬於您的完美穿搭</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="<?= base_url('products') ?>" class="btn btn-orange btn-lg px-5 py-3 fw-semibold">
                <i class="bi bi-bag2 me-2"></i>立即選購
            </a>
            <a href="<?= base_url('about') ?>" class="btn btn-outline-light btn-lg px-5 py-3">
                品牌故事
            </a>
        </div>
    </div>
</section>

<!-- 分類區塊 -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title mx-auto">商品分類</h2>
            <p class="text-muted mt-3">探索各種風格，找到最適合您的穿搭</p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php
            $icons = ['tops'=>'bi-person-standing','pants'=>'bi-layout-sidebar-reverse','dresses'=>'bi-stars','accessories'=>'bi-gem'];
            $colors_bg = ['tops'=>'#FFF3E0','pants'=>'#E8F4F8','dresses'=>'#FCE4EC','accessories'=>'#F3E5F5'];
            $colors_text = ['tops'=>'#FF6B35','pants'=>'#0288D1','dresses'=>'#E91E63','accessories'=>'#9C27B0'];
            if (!empty($categories)): foreach ($categories as $cat): ?>
            <div class="col-6 col-md-3">
                <a href="<?= base_url('products/category/' . $cat->slug) ?>" class="text-decoration-none">
                    <div class="category-card card text-center p-4">
                        <div class="category-icon mx-auto mb-3" style="background:<?= $colors_bg[$cat->slug] ?? '#FFF3E0' ?>; color:<?= $colors_text[$cat->slug] ?? '#FF6B35' ?>">
                            <i class="bi <?= $icons[$cat->slug] ?? 'bi-tag' ?>"></i>
                        </div>
                        <h5 class="fw-bold mb-0"><?= $cat->name ?></h5>
                        <p class="small text-muted mt-1 mb-0">查看商品 <i class="bi bi-arrow-right"></i></p>
                    </div>
                </a>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- 熱銷商品 -->
<section class="py-5" style="background:#f8f9fa">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="section-title">熱銷商品</h2>
                <p class="text-muted mt-3 mb-0">精選最受歡迎的時尚單品</p>
            </div>
            <a href="<?= base_url('products') ?>" class="btn btn-outline-orange">查看全部 <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <?php if (!empty($featured_products)): foreach ($featured_products as $p): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card card h-100 position-relative">
                    <?php if ($p->original_price && $p->price < $p->original_price): ?>
                    <span class="badge badge-sale rounded-pill">特價</span>
                    <?php endif; ?>
                    <a href="<?= base_url('products/' . $p->id) ?>" class="text-decoration-none">
                        <div class="product-img">
                            <i class="bi bi-image" style="font-size:4rem; opacity:.3"></i>
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
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <a href="<?= base_url('products/' . $p->id) ?>" class="btn btn-orange w-100 btn-sm">
                            <i class="bi bi-bag-plus me-1"></i>查看詳情
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- 最新消息 -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="section-title">最新消息</h2>
                <p class="text-muted mt-3 mb-0">掌握最新品牌動態</p>
            </div>
            <a href="<?= base_url('news') ?>" class="btn btn-outline-orange">查看全部 <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <?php if (!empty($latest_news)): foreach ($latest_news as $n): ?>
            <div class="col-md-4">
                <div class="news-card card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <p class="small text-muted mb-2"><i class="bi bi-calendar3 me-1"></i><?= date('Y/m/d', strtotime($n->published_at)) ?></p>
                        <h5 class="fw-bold mb-2"><?= htmlspecialchars($n->title) ?></h5>
                        <p class="text-muted small mb-3"><?= htmlspecialchars($n->summary) ?></p>
                        <a href="<?= base_url('news/' . $n->id) ?>" class="btn btn-sm btn-outline-orange">閱讀更多 <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section py-5 text-white text-center">
    <div class="container py-3">
        <h2 class="fw-bold mb-3">加入 CI3 Fashion 會員</h2>
        <p class="lead mb-4 opacity-90">立即註冊享首購九折優惠，搶先獲得新品資訊</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="<?= base_url('member/register') ?>" class="btn btn-light btn-lg px-5 fw-semibold" style="color:var(--orange)">
                <i class="bi bi-person-plus me-2"></i>免費註冊
            </a>
            <a href="<?= base_url('products') ?>" class="btn btn-outline-light btn-lg px-5">探索商品</a>
        </div>
    </div>
</section>
