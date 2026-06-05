# CI3 Fashion — 原始碼說明

時尚服裝電商 Demo 專案，使用 CodeIgniter 3 建構，包含前台購物網站、會員系統與後台管理系統。
**所有前端資源均為本地端，可完全離線運作。**

> 啟動方式請參考上層目錄的 [README](../README.md)。

---

## 技術棧

| 項目 | 技術 |
|------|------|
| 後端框架 | CodeIgniter 3.1.13 |
| 語言 | PHP 8.2 |
| 資料庫 | MySQL 8.0 |
| 前端框架 | Bootstrap 5.3（本地端） |
| 圖示 | Bootstrap Icons 1.11（本地端） |

---

## 目錄結構

```
src/
├── application/
│   ├── config/
│   │   ├── config.php          ← base_url、session 設定
│   │   ├── database.php        ← 資料庫連線（Docker 環境 hostname 為 db）
│   │   ├── autoload.php        ← 自動載入設定
│   │   └── routes.php          ← 路由定義
│   ├── core/
│   │   └── MY_Controller.php   ← 前台 / 後台基底 Controller
│   ├── controllers/
│   │   ├── Home.php            ← 首頁
│   │   ├── Front/              ← 前台（Products、Cart、Member、Checkout...）
│   │   └── Admin/              ← 後台（Dashboard、Products、Orders...）
│   ├── models/                 ← 7 個 Model（Product、Member、Order...）
│   └── views/
│       ├── layouts/            ← 前台 / 後台共用版型
│       ├── frontend/           ← 前台頁面
│       └── admin/              ← 後台頁面
├── assets/                     ← 前端資源（Bootstrap CSS/JS/Fonts，離線可用）
├── system/                     ← CI3 核心（勿修改）
├── database.sql                ← 資料庫結構與假資料
├── .htaccess                   ← mod_rewrite 設定（RewriteBase /）
└── index.php                   ← 入口點（已處理 PHP 8.2 deprecated 警告）
```

---

## 帳號資訊

| 類型 | 帳號 | 密碼 |
|------|------|------|
| 後台管理員 | `admin` | `password` |
| 前台測試會員 | `test@example.com` | `password` |

---

## 頁面路由

### 前台

| 頁面 | 路徑 |
|------|------|
| 首頁 | `/` |
| 商品列表 | `/products` |
| 商品分類 | `/products/category/tops` |
| 商品詳細 | `/products/1` |
| 購物車 | `/cart` |
| 結帳 | `/checkout` |
| 會員登入 | `/member/login` |
| 會員中心 | `/member/dashboard` |
| 最新消息 | `/news` |
| 聯絡我們 | `/contact` |
| 關於我們 | `/about` |

### 後台

| 頁面 | 路徑 |
|------|------|
| 登入 | `/admin/login` |
| 儀表板 | `/admin/dashboard` |
| 商品管理 | `/admin/products` |
| 訂單管理 | `/admin/orders` |
| 消息管理 | `/admin/news` |
| 訊息管理 | `/admin/contacts` |

---

## 資料庫（10 張資料表）

| 資料表 | 用途 |
|--------|------|
| `admins` | 後台管理員 |
| `members` | 前台會員 |
| `categories` | 商品分類 |
| `products` | 商品主檔 |
| `product_variants` | 商品規格（尺寸／顏色／庫存）|
| `orders` | 訂單主檔 |
| `order_items` | 訂單明細 |
| `news` | 最新消息 |
| `contact_messages` | 聯絡表單訊息 |
| `ci_sessions` | CI3 Session 儲存 |

---

## 開發注意事項

- **編輯檔案後直接重新整理瀏覽器**，Volume 掛載會即時反映變更，無需重啟容器
- **購物車需登入會員**才能使用，存放於 Session，登出後清空
- **新增頁面**：前台繼承 `Front_Controller`，後台繼承 `Admin_Controller`，並在 `routes.php` 補路由
- **前端資源引入**統一使用 `base_url()`，不使用 CDN 或相對路徑

### 常見錯誤

| 症狀 | 解法 |
|------|------|
| 500 錯誤 | `application/cache`、`application/logs` 權限不足 |
| 404（所有頁面）| `.htaccess` 未生效，確認容器內 `mod_rewrite` 已啟用 |
| Session 錯誤 | `ci_sessions` 資料表不存在，重新匯入 `database.sql` |
| 資料庫連線失敗 | `database.php` 的 hostname 須為 `db`（Docker service 名稱）|
