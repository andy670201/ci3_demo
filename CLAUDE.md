# CI3 Fashion 服裝買賣網站 - 專案現狀說明書

## 專案概述

時尚電商示範網站，支援完整前後台功能，可完全離線運作（所有前端資源本地化）。

- **技術棧**：CodeIgniter 3.1.13 + Bootstrap 5 (本地) + JavaScript
- **資料庫**：MySQL 8.0
- **部署環境**：Docker（PHP 8.2-apache）
- **CI3 原始碼**：放在 `src/` 目錄，掛載至容器 `/var/www/html`
- **前端字型**：`font-family: 'Noto Sans TC', '微軟正黑體', sans-serif`（不引入 Google Fonts）

---

## 帳號資訊

| 類型 | 帳號 | 密碼 |
|------|------|------|
| 後台管理員 | admin | password |
| MySQL | admin | password |
| 前台測試會員 | test@example.com | password |

---

## 存取網址

| 服務 | 網址 |
|------|------|
| 前台首頁 | http://localhost:8080/ |
| 後台登入 | http://localhost:8080/admin |
| phpMyAdmin | http://localhost:8081/ |

---

## Docker 環境

```
ci3_demo/
├── Dockerfile           # PHP 8.2-apache，安裝 mysqli/pdo/zip/mbstring，啟用 mod_rewrite
├── docker-compose.yml
└── src/                 # CI3 原始碼（掛載為 web root）
```

### 容器說明

| 容器 | Image | Port |
|------|-------|------|
| ci3-web | PHP 8.2-apache (自建) | 8080:80 |
| ci3-db | mysql:8.0 | 3306:3306 |
| ci3-pma | phpmyadmin/phpmyadmin | 8081:80 |

### 常用指令

```bash
docker compose up -d          # 啟動
docker compose down           # 停止
docker compose ps             # 查看狀態
docker exec -it ci3-web bash  # 進入 web 容器

# 還原資料庫
docker exec -i ci3-db mysql -u admin -ppassword < src/database.sql

# 匯出資料庫備份
docker exec ci3-db mysqldump -u admin -ppassword ci3_fashion > src/database.sql
```

---

## 專案目錄結構

```
src/
├── index.php                          # 入口（error_reporting 已修正 PHP 8.2 相容性）
├── .htaccess                          # URL rewrite（RewriteBase /）
├── assets/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   └── bootstrap-icons.css        # 字型路徑已改為 ../fonts/
│   ├── js/
│   │   └── bootstrap.bundle.min.js
│   └── fonts/
│       ├── bootstrap-icons.woff2
│       └── bootstrap-icons.woff
├── database.sql                       # 資料庫備份（含初始資料）
└── application/
    ├── config/
    │   ├── config.php
    │   ├── database.php
    │   ├── autoload.php
    │   └── routes.php
    ├── core/
    │   └── MY_Controller.php          # MY_Controller / Front_Controller / Admin_Controller
    ├── models/
    │   ├── Admin_model.php
    │   ├── Category_model.php
    │   ├── Product_model.php
    │   ├── Member_model.php
    │   ├── Order_model.php
    │   ├── News_model.php
    │   └── Contact_model.php
    ├── controllers/
    │   ├── Home.php                   # 前台首頁（不在 Front/ 子目錄）
    │   ├── Front/
    │   │   ├── About.php
    │   │   ├── Products.php
    │   │   ├── Cart.php
    │   │   ├── Member.php
    │   │   ├── Checkout.php
    │   │   ├── News.php
    │   │   └── Contact.php
    │   └── Admin/
    │       ├── Auth.php
    │       ├── Dashboard.php
    │       ├── Products.php
    │       ├── Orders.php
    │       ├── News.php
    │       └── Contacts.php
    └── views/
        ├── layouts/
        │   ├── header.php             # 前台 Navbar
        │   ├── footer.php
        │   ├── admin_header.php       # 後台 Sidebar（260px）
        │   └── admin_footer.php
        ├── frontend/
        │   ├── home/index.php
        │   ├── products/{index,detail}.php
        │   ├── cart/index.php
        │   ├── checkout/{index,success}.php
        │   ├── member/{login,register,dashboard,order_detail}.php
        │   ├── news/{index,detail}.php
        │   └── pages/{about,contact}.php
        └── admin/
            ├── auth/login.php
            ├── dashboard/index.php
            ├── products/{index,form}.php
            ├── orders/{index,detail}.php
            ├── news/{index,form}.php
            └── contacts/{index,detail}.php
```

---

## 重要設定

### config.php

```php
$config['base_url']                = 'http://localhost:8080/';
$config['index_page']              = '';
$config['encryption_key']          = 'ci3fashion2024secretkey';
$config['sess_driver']             = 'database';
$config['sess_cookie_name']        = 'ci3_session';
$config['sess_expiration']         = 7200;
$config['sess_save_path']          = 'ci_sessions';  // 資料表名稱
$config['sess_match_ip']           = FALSE;
$config['sess_time_to_update']     = 300;
$config['sess_regenerate_destroy'] = FALSE;
```

### database.php

> **重要**：`hostname` 必須填 `db`（docker-compose service 名稱），不是 `localhost`。

```php
$db['default'] = array(
    'hostname' => 'db',
    'username' => 'admin',
    'password' => 'password',
    'database' => 'ci3_fashion',
    'dbdriver' => 'mysqli',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
);
```

### autoload.php

```php
$autoload['libraries'] = array('database', 'session', 'form_validation');
$autoload['helper']    = array('url', 'form', 'html', 'security');
```

---

## 路由對照表

### 前台路由

| 路由 | Controller |
|------|-----------|
| `/` | Home::index |
| `/about` | Front/About::index |
| `/products` | Front/Products::index |
| `/products/category/(:any)` | Front/Products::category |
| `/products/(:num)` | Front/Products::detail |
| `/news` | Front/News::index |
| `/news/(:num)` | Front/News::detail |
| `/contact` | Front/Contact::index |
| `/contact/send` | Front/Contact::send |
| `/cart` | Front/Cart::index |
| `/cart/add` | Front/Cart::add (AJAX JSON) |
| `/cart/update` | Front/Cart::update |
| `/cart/remove/(:num)` | Front/Cart::remove |
| `/member/register` | Front/Member::register |
| `/member/login` | Front/Member::login |
| `/member/logout` | Front/Member::logout |
| `/member/dashboard` | Front/Member::dashboard |
| `/member/orders/(:any)` | Front/Member::order_detail |
| `/checkout` | Front/Checkout::index |
| `/checkout/confirm` | Front/Checkout::confirm |
| `/checkout/success/(:any)` | Front/Checkout::success |

### 後台路由

| 路由 | Controller |
|------|-----------|
| `/admin` | Admin/Auth::login |
| `/admin/dashboard` | Admin/Dashboard::index |
| `/admin/products` | Admin/Products::index |
| `/admin/products/create` | Admin/Products::create |
| `/admin/products/edit/(:num)` | Admin/Products::edit |
| `/admin/products/toggle/(:num)` | Admin/Products::toggle |
| `/admin/orders` | Admin/Orders::index |
| `/admin/orders/(:num)` | Admin/Orders::detail |
| `/admin/orders/status/(:num)` | Admin/Orders::update_status |
| `/admin/news` | Admin/News::index |
| `/admin/contacts` | Admin/Contacts::index |

---

## 架構說明

### Controller 繼承層級

```
CI_Controller
└── MY_Controller
    ├── Front_Controller   ← 前台控制器基底
    │   └── 自動載入 Product_model、Category_model
    │   └── render() 注入：cart_count、member session、categories
    └── Admin_Controller   ← 後台控制器基底
        └── 未登入自動跳轉 admin/login
        └── render() 注入：admin_name、unread_count
```

> `Admin_Controller` 檢查 `$this->session->userdata('admin_logged_in')`，未登入直接 redirect。

### 購物車機制

購物車存放於 Session，key 格式：`{product_id}_{size}_{color}`，值包含 `product_id / name / price / size / color / quantity`。

`Cart::add` 回傳 JSON（AJAX）；加入購物車須先登入會員，否則回傳 `redirect` 欄位引導至登入頁。

### 訂單編號格式

`ORD` + `YmdHis` + 3位亂數，例：`ORD20240601123456789`

---

## 資料庫 Schema（資料表清單）

| 資料表 | 說明 |
|--------|------|
| admins | 後台管理員 |
| members | 前台會員 |
| categories | 商品分類（上衣/褲子/洋裝/配件） |
| products | 商品（含 is_active / is_featured） |
| product_variants | 商品規格（size / color / stock） |
| ci_sessions | CI3 資料庫 Session 儲存 |
| orders | 訂單（status: pending/confirmed/shipping/delivered/cancelled） |
| order_items | 訂單明細 |
| news | 最新消息 |
| contact_messages | 聯絡表單訊息（is_read） |

---

## 前端規範

### 資源引入（本地，無 CDN）

```html
<link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/bootstrap-icons.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
```

### 色彩系統

```css
:root {
    --orange:       #FF6B35;
    --orange-light: #FF8C42;
    --orange-pale:  #FFF3E0;
    --dark:         #1A1A2E;
}
```

- 前台 Navbar：深色背景 `#1A1A2E` + 橘色 Logo
- 後台 Sidebar：260px 寬，橘色 active 狀態
- Hero Banner：橘色漸層

---

## PHP 8.2 相容性注意

`src/index.php` 的 `error_reporting` 已修改為：

```php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
```

原始 CI3 的 `error_reporting(-1)` 在 PHP 8.2 會輸出大量 deprecated 警告，影響頁面渲染。
