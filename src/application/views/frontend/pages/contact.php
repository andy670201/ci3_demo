<style>
.contact-hero { background: linear-gradient(135deg, #FF6B35, #FF8C42); color: #fff; padding: 80px 0 60px; }
.contact-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
.info-icon { width: 52px; height: 52px; background: #FFF3E0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #FF6B35; }
.btn-orange { background: #FF6B35; border: none; color: #fff; }
.btn-orange:hover { background: #e55a25; color: #fff; }
.form-control:focus { border-color: #FF6B35; box-shadow: 0 0 0 .2rem rgba(255,107,53,.25); }
</style>

<section class="contact-hero text-center">
    <div class="container">
        <h1 class="fw-bold mb-2"><i class="bi bi-envelope-heart me-2"></i>聯絡我們</h1>
        <p class="mb-0 opacity-75">有任何問題或建議，歡迎隨時與我們聯繫</p>
    </div>
</section>

<section class="py-5">
    <div class="container">

        <?php if ($success): ?>
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= $success ?></div>
        </div>
        <?php endif; ?>

        <?php if (validation_errors()): ?>
        <div class="alert alert-danger"><?= validation_errors() ?></div>
        <?php endif; ?>

        <div class="row g-5">
            <!-- 聯絡資訊 -->
            <div class="col-lg-4">
                <h4 class="fw-bold mb-4" style="color:#FF6B35;">聯絡資訊</h4>

                <div class="d-flex align-items-start mb-4">
                    <div class="info-icon me-3 flex-shrink-0"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <div class="fw-semibold mb-1">門市地址</div>
                        <div class="text-muted small">台北市信義區松高路 11 號 3F<br>（週一至週日 11:00 - 21:00）</div>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="info-icon me-3 flex-shrink-0"><i class="bi bi-telephone-fill"></i></div>
                    <div>
                        <div class="fw-semibold mb-1">客服電話</div>
                        <div class="text-muted small">02-2345-6789<br>週一至週五 10:00 - 18:00</div>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="info-icon me-3 flex-shrink-0"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <div class="fw-semibold mb-1">客服信箱</div>
                        <div class="text-muted small">service@ci3fashion.com</div>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="info-icon me-3 flex-shrink-0"><i class="bi bi-instagram"></i></div>
                    <div>
                        <div class="fw-semibold mb-1">社群媒體</div>
                        <div class="text-muted small">@ci3_fashion<br>追蹤我們獲取最新消息</div>
                    </div>
                </div>

                <!-- 地圖佔位 -->
                <div class="mt-4 rounded-3 overflow-hidden" style="height:200px; background:#e9ecef; display:flex; align-items:center; justify-content:center;">
                    <div class="text-center text-muted">
                        <i class="bi bi-map fs-1"></i>
                        <div class="small mt-2">台北市信義區松高路 11 號</div>
                    </div>
                </div>
            </div>

            <!-- 聯絡表單 -->
            <div class="col-lg-8">
                <div class="contact-card card p-4 p-lg-5">
                    <h4 class="fw-bold mb-4" style="color:#FF6B35;">傳送訊息</h4>
                    <?= form_open('contact/send') ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">姓名 <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= set_value('name') ?>" placeholder="請輸入您的姓名">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" placeholder="請輸入 Email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">電話</label>
                            <input type="text" name="phone" class="form-control" value="<?= set_value('phone') ?>" placeholder="選填">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">主旨 <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" value="<?= set_value('subject') ?>" placeholder="請輸入主旨">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">訊息內容 <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="6" placeholder="請輸入您的訊息..."><?= set_value('message') ?></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-orange px-5 py-2 fw-semibold">
                                <i class="bi bi-send me-2"></i>送出訊息
                            </button>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</section>
