<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm" style="border-radius:20px; border:none">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div style="font-size:3rem; color:var(--orange)"><i class="bi bi-person-plus-fill"></i></div>
                        <h3 class="fw-bold mt-2">免費註冊會員</h3>
                        <p class="text-muted small">加入我們，享受更多會員專屬優惠</p>
                    </div>
                    <?= validation_errors('<div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-circle me-2"></i>', '</div>') ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">姓名 <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control" value="<?= set_value('name') ?>" placeholder="您的姓名" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" placeholder="your@email.com" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">密碼 <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="至少 6 個字元" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">手機號碼</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control" value="<?= set_value('phone') ?>" placeholder="09XX-XXX-XXX">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-orange w-100 btn-lg py-3 fw-semibold">
                            <i class="bi bi-person-check me-2"></i>立即註冊
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="text-muted small">已有帳號？<a href="<?= base_url('member/login') ?>" style="color:var(--orange)">立即登入</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
