<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI3 Fashion - 時尚服裝</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        :root {
            --orange:       #FF6B35;
            --orange-light: #FF8C42;
            --orange-pale:  #FFF3E0;
            --dark:         #1A1A2E;
            --dark-2:       #16213E;
        }
        body { font-family: 'Noto Sans TC', '微軟正黑體', sans-serif; }
        .navbar-brand span { color: var(--orange); }
        .navbar { background-color: var(--dark) !important; }
        .nav-link { color: rgba(255,255,255,0.85) !important; transition: color .2s; }
        .nav-link:hover, .nav-link.active { color: var(--orange) !important; }
        .btn-orange { background-color: var(--orange); border-color: var(--orange); color: #fff; }
        .btn-orange:hover { background-color: var(--orange-light); border-color: var(--orange-light); color: #fff; }
        .btn-outline-orange { border-color: var(--orange); color: var(--orange); }
        .btn-outline-orange:hover { background-color: var(--orange); color: #fff; }
        .cart-badge { background-color: var(--orange); }
        .toast-container { position: fixed; bottom: 1rem; right: 1rem; z-index: 9999; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="<?= base_url() ?>">
            <i class="bi bi-bag-heart-fill" style="color:var(--orange)"></i>
            <span>CI3</span> Fashion
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>">首頁</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">商品</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= base_url('products') ?>">全部商品</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                        <li><a class="dropdown-item" href="<?= base_url('products/category/' . $cat->slug) ?>"><?= $cat->name ?></a></li>
                        <?php endforeach; endif; ?>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('news') ?>">最新消息</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">關於我們</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('contact') ?>">聯絡我們</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= base_url('cart') ?>" class="btn btn-outline-light position-relative">
                    <i class="bi bi-cart3"></i>
                    <?php if (!empty($cart_count) && $cart_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>
                <?php if (!empty($member)): ?>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($member['name']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('member/dashboard') ?>"><i class="bi bi-grid me-2"></i>我的帳戶</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('member/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>登出</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a href="<?= base_url('member/login') ?>" class="btn btn-orange">
                    <i class="bi bi-person me-1"></i>登入
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div id="toastContainer" class="toast-container"></div>

<script>
function showToast(msg, type='success') {
    var t = document.createElement('div');
    t.className = 'toast align-items-center text-bg-' + type + ' border-0 show mb-2';
    t.setAttribute('role','alert');
    t.innerHTML = '<div class="d-flex"><div class="toast-body">' + msg + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(function(){ t.remove(); }, 3000);
}
</script>
