<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>後台登入 — CI3 Fashion</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        body { background: #0f0f1a; font-family: 'Noto Sans TC', '微軟正黑體', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { background: #1A1A2E; border-radius: 20px; padding: 48px 40px; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,.5); }
        .logo-ring { width: 72px; height: 72px; background: linear-gradient(135deg,#FF6B35,#FF8C42); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; color: #fff; }
        .form-control { background: #16213E; border: 1px solid #2d3561; color: #e0e0e0; border-radius: 10px; padding: 12px 16px; }
        .form-control:focus { background: #16213E; border-color: #FF6B35; box-shadow: 0 0 0 .2rem rgba(255,107,53,.25); color: #e0e0e0; }
        .form-label { color: #adb5bd; font-size: .9rem; }
        .btn-login { background: linear-gradient(135deg,#FF6B35,#FF8C42); border: none; border-radius: 10px; padding: 12px; font-size: 1rem; font-weight: 600; letter-spacing: .5px; }
        .btn-login:hover { background: linear-gradient(135deg,#e55a25,#FF6B35); }
        h5 { color: #fff; }
        .text-muted { color: #6c757d !important; }
        .input-group-text { background: #16213E; border: 1px solid #2d3561; color: #adb5bd; }
    </style>
</head>
<body>
<div class="login-box">
    <div class="logo-ring"><i class="bi bi-bag-heart-fill"></i></div>
    <h5 class="text-center fw-bold mb-1">CI3 Fashion</h5>
    <p class="text-center text-muted mb-4" style="font-size:.85rem;">管理後台</p>

    <?php if (isset($error)): ?>
    <div class="alert alert-danger d-flex align-items-center py-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <small><?= $error ?></small>
    </div>
    <?php endif; ?>

    <?= form_open('admin/login') ?>
    <div class="mb-3">
        <label class="form-label">帳號</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="username" class="form-control" placeholder="請輸入帳號" autocomplete="username" required>
        </div>
    </div>
    <div class="mb-4">
        <label class="form-label">密碼</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="請輸入密碼" autocomplete="current-password" required>
        </div>
    </div>
    <button type="submit" class="btn btn-login text-white w-100">
        <i class="bi bi-box-arrow-in-right me-2"></i>登入後台
    </button>
    <?= form_close() ?>

    <p class="text-center mt-4 mb-0" style="font-size:.8rem;">
        <a href="<?= base_url() ?>" class="text-muted text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>返回前台
        </a>
    </p>
</div>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
