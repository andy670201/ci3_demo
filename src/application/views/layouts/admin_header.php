<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI3 Fashion 後台管理</title>
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
        body { font-family: 'Noto Sans TC', '微軟正黑體', sans-serif; background: #f5f6fa; }
        #sidebar {
            width: 260px; min-height: 100vh;
            background: var(--dark);
            position: fixed; top: 0; left: 0; z-index: 100;
            display: flex; flex-direction: column;
        }
        #sidebar .brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
        }
        #sidebar .brand span { color: var(--orange); }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: .65rem 1.5rem;
            border-radius: 0;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,107,53,0.2);
            border-left: 3px solid var(--orange);
        }
        #sidebar .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        #main-content { margin-left: 260px; }
        .top-bar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: .75rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .btn-orange { background-color: var(--orange); border-color: var(--orange); color: #fff; }
        .btn-orange:hover { background-color: var(--orange-light); border-color: var(--orange-light); color: #fff; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 12px; }
        .badge-orange { background-color: var(--orange); }
        .table th { background: #f8f9fa; font-weight: 600; }
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
<div id="sidebar">
    <div class="brand">
        <i class="bi bi-bag-heart-fill me-2" style="color:var(--orange)"></i>
        <span>CI3</span> Fashion
        <div class="small mt-1" style="color:rgba(255,255,255,0.5); font-weight:400; font-size:.75rem;">後台管理系統</div>
    </div>
    <nav class="nav flex-column mt-2">
        <a class="nav-link <?= strpos(current_url(), 'admin/dashboard') !== false ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
            <i class="bi bi-speedometer2"></i> 儀表板
        </a>
        <a class="nav-link <?= strpos(current_url(), 'admin/products') !== false ? 'active' : '' ?>" href="<?= base_url('admin/products') ?>">
            <i class="bi bi-bag"></i> 商品管理
        </a>
        <a class="nav-link <?= strpos(current_url(), 'admin/orders') !== false ? 'active' : '' ?>" href="<?= base_url('admin/orders') ?>">
            <i class="bi bi-receipt"></i> 訂單管理
        </a>
        <a class="nav-link <?= strpos(current_url(), 'admin/news') !== false ? 'active' : '' ?>" href="<?= base_url('admin/news') ?>">
            <i class="bi bi-newspaper"></i> 消息管理
        </a>
        <a class="nav-link <?= strpos(current_url(), 'admin/contacts') !== false ? 'active' : '' ?>" href="<?= base_url('admin/contacts') ?>">
            <i class="bi bi-envelope"></i> 聯絡訊息
            <?php if (!empty($unread_count) && $unread_count > 0): ?>
            <span class="badge rounded-pill ms-auto" style="background:var(--orange)"><?= $unread_count ?></span>
            <?php endif; ?>
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= base_url() ?>" class="d-block text-decoration-none mb-2" style="color:rgba(255,255,255,0.5); font-size:.85rem;">
            <i class="bi bi-house me-2"></i>前台首頁
        </a>
        <a href="<?= base_url('admin/logout') ?>" class="d-block text-decoration-none" style="color:rgba(255,100,53,0.8); font-size:.85rem;">
            <i class="bi bi-box-arrow-right me-2"></i>登出
        </a>
    </div>
</div>
<div id="main-content">
    <div class="top-bar">
        <span class="fw-semibold text-muted small">後台管理</span>
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-circle" style="color:var(--orange)"></i>
            <span class="small fw-semibold"><?= isset($admin_name) ? htmlspecialchars($admin_name) : '' ?></span>
        </div>
    </div>
    <div class="p-4">
