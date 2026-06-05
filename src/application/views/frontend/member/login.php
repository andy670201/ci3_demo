<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm" style="border-radius:20px; border:none">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div style="font-size:3rem; color:var(--orange)"><i class="bi bi-person-circle"></i></div>
                        <h3 class="fw-bold mt-2">會員登入</h3>
                        <p class="text-muted small">歡迎回來！請輸入您的帳號資料</p>
                    </div>
                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">密碼</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-orange w-100 btn-lg py-3 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-2"></i>登入
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="text-muted small">還沒有帳號？<a href="<?= base_url('member/register') ?>" style="color:var(--orange)">立即免費註冊</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
