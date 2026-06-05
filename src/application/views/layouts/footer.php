<footer class="mt-auto py-5" style="background-color:var(--dark); color:#aaa;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3" style="color:var(--orange)"><i class="bi bi-bag-heart-fill me-2"></i>CI3 Fashion</h5>
                <p class="small">打造屬於您的時尚風格，每一件衣物都是精心挑選的品質保證。</p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-decoration-none" style="color:var(--orange)"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-decoration-none" style="color:var(--orange)"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-decoration-none" style="color:var(--orange)"><i class="bi bi-line fs-5"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="fw-bold text-white mb-3">快速連結</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= base_url('products') ?>" class="text-decoration-none" style="color:#aaa;">全部商品</a></li>
                    <li class="mb-2"><a href="<?= base_url('news') ?>" class="text-decoration-none" style="color:#aaa;">最新消息</a></li>
                    <li class="mb-2"><a href="<?= base_url('about') ?>" class="text-decoration-none" style="color:#aaa;">關於我們</a></li>
                    <li class="mb-2"><a href="<?= base_url('contact') ?>" class="text-decoration-none" style="color:#aaa;">聯絡我們</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="fw-bold text-white mb-3">會員服務</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= base_url('member/login') ?>" class="text-decoration-none" style="color:#aaa;">會員登入</a></li>
                    <li class="mb-2"><a href="<?= base_url('member/register') ?>" class="text-decoration-none" style="color:#aaa;">免費註冊</a></li>
                    <li class="mb-2"><a href="<?= base_url('member/dashboard') ?>" class="text-decoration-none" style="color:#aaa;">我的訂單</a></li>
                    <li class="mb-2"><a href="<?= base_url('cart') ?>" class="text-decoration-none" style="color:#aaa;">購物車</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-bold text-white mb-3">聯絡資訊</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-geo-alt me-2" style="color:var(--orange)"></i>台北市信義區時尚大道 100 號</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2" style="color:var(--orange)"></i>02-1234-5678</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2" style="color:var(--orange)"></i>service@ci3fashion.com</li>
                    <li class="mb-2"><i class="bi bi-clock me-2" style="color:var(--orange)"></i>週一至週五 10:00 - 18:00</li>
                </ul>
            </div>
        </div>
        <hr style="border-color:#333; margin-top:2rem;">
        <p class="text-center small mb-0" style="color:#666;">© 2024 CI3 Fashion. All rights reserved.</p>
    </div>
</footer>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
