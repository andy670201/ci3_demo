-- =============================================================================
-- CI3 Fashion Demo — 資料庫完整結構與初始資料
-- =============================================================================
--
-- 資料庫名稱：ci3_fashion
-- 字元集：utf8mb4（完整 Unicode，支援 Emoji 與中文）
-- 排序規則：utf8mb4_unicode_ci
-- 匯出日期：2026-06-04
--
-- 資料表清單與用途說明：
--
--   admins            — 後台管理員帳號，密碼以 bcrypt 雜湊儲存
--   categories        — 商品分類（上衣／褲子／洋裝／配件等），含 URL slug
--   products          — 商品主檔，含售價、原價、上下架、精選旗標
--   product_variants  — 商品規格，記錄每款商品的尺寸 / 顏色 / 庫存組合
--   members           — 前台會員帳號，密碼以 bcrypt 雜湊儲存
--   orders            — 訂單主檔，含收件人資訊與訂單狀態（共 5 種 ENUM）
--   order_items       — 訂單明細，記錄每筆訂單的購買商品、規格與單價快照
--   contact_messages  — 前台聯絡表單訊息，含已讀 / 未讀狀態
--   news              — 最新消息 / 文章，含草稿與發佈狀態管理
--   ci_sessions       — CodeIgniter 資料庫 Session 儲存表（框架自動管理）
--
-- 匯入方式：
--   mysql -u <用戶> -p < database.sql
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 建立並選用資料庫
-- -----------------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `ci3_fashion`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `ci3_fashion`;


-- =============================================================================
-- 資料表：admins
-- 用途：儲存後台管理員帳號資料。
--       密碼欄位使用 PHP password_hash()（bcrypt）雜湊，不儲存明文。
--       username 設為唯一索引，避免重複帳號。
-- =============================================================================
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id`         int          NOT NULL AUTO_INCREMENT,
  `username`   varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登入帳號（唯一）',
  `password`   varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bcrypt 雜湊密碼',
  `name`       varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '顯示姓名',
  `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 初始管理員：帳號 admin，密碼 password（bcrypt 雜湊）
INSERT INTO `admins` (`id`, `username`, `password`, `name`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '系統管理員', '2026-06-04 00:54:15');


-- =============================================================================
-- 資料表：categories
-- 用途：商品分類主表。
--       slug 欄位作為前台 URL 路徑識別名稱（如 /products/category/tops）。
--       sort_order 控制選單排列順序，數字越小越前面。
-- =============================================================================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id`         int          NOT NULL AUTO_INCREMENT,
  `name`       varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分類顯示名稱',
  `slug`       varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL 識別名稱（英文小寫）',
  `sort_order` int          DEFAULT '0' COMMENT '排列順序，數字越小越前',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 初始分類資料：上衣、褲子、洋裝、配件
INSERT INTO `categories` (`id`, `name`, `slug`, `sort_order`) VALUES
(1, '上衣',   'tops',        1),
(2, '褲子',   'pants',       2),
(3, '洋裝',   'dresses',     3),
(4, '配件',   'accessories', 4);


-- =============================================================================
-- 資料表：products
-- 用途：商品主檔，記錄商品基本資訊與銷售設定。
--       price 為現售價，original_price 為劃線原價（用於顯示折扣）。
--       is_active  = 1 表示上架中，= 0 表示下架（前台不顯示）。
--       is_featured = 1 表示精選商品（顯示於首頁精選區）。
--       與 categories 透過 category_id 外鍵關聯。
-- =============================================================================
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id`             int            NOT NULL AUTO_INCREMENT,
  `name`           varchar(255)   COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品名稱',
  `category_id`    int            NOT NULL COMMENT '所屬分類 ID（FK → categories.id）',
  `description`    text           COLLATE utf8mb4_unicode_ci COMMENT '商品描述',
  `price`          decimal(10,2)  NOT NULL DEFAULT '0.00' COMMENT '現售價',
  `original_price` decimal(10,2)  DEFAULT NULL COMMENT '劃線原價（null 表示無折扣顯示）',
  `is_active`      tinyint(1)     DEFAULT '1' COMMENT '上架狀態：1=上架，0=下架',
  `is_featured`    tinyint(1)     DEFAULT '0' COMMENT '精選旗標：1=首頁精選顯示',
  `stock`          int            DEFAULT '0' COMMENT '總庫存數量',
  `created_at`     timestamp      NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 初始商品資料（12 件，含上架與下架各 1 件）
INSERT INTO `products` (`id`, `name`, `category_id`, `description`, `price`, `original_price`, `is_active`, `is_featured`, `stock`, `created_at`) VALUES
( 1, '經典棉質圓領T恤',   1, '100% 純棉材質，柔軟舒適，適合日常穿著，多色可選。',     590.00,  790.00, 1, 1, 100, '2026-06-04 00:54:15'),
( 2, '條紋長袖上衣',       1, '海軍風條紋設計，法式休閒風格，適合搭配各式下著。',       890.00, 1200.00, 1, 1,  80, '2026-06-04 00:54:15'),
( 3, 'V領雪紡上衣',        1, '輕盈雪紡材質，V領設計修飾頸線，優雅百搭。',              750.00,  990.00, 1, 0,  60, '2026-06-04 00:54:15'),
( 4, '修身直筒牛仔褲',     2, '經典修身剪裁，高品質丹寧布料，百搭必備款。',            1290.00, 1690.00, 1, 1,  90, '2026-06-04 00:54:15'),
( 5, '寬鬆休閒長褲',       2, '舒適寬鬆版型，高腰設計顯瘦，多種場合適用。',            1090.00, 1390.00, 1, 0,  70, '2026-06-04 00:54:15'),
( 6, 'A字短裙',             2, '清新A字版型，腰部設計修飾曲線，通勤休閒皆宜。',          790.00,  990.00, 1, 0,  50, '2026-06-04 00:54:15'),
( 7, '碎花連身洋裝',       3, '浪漫碎花印花，腰部綁帶設計，約會出遊首選。',            1590.00, 2190.00, 1, 1,  40, '2026-06-04 00:54:15'),
( 8, '簡約素色洋裝',       3, '極簡風格設計，優質面料垂墜感佳，職場休閒兩用。',        1390.00, 1790.00, 1, 0,  45, '2026-06-04 00:54:15'),
( 9, '針織開襟外套',       1, '柔軟針織材質，百搭開襟設計，春秋必備單品。',            1290.00, 1690.00, 1, 1,  55, '2026-06-04 00:54:15'),
(10, '真皮皮帶',           4, '頭層牛皮材質，經典扣環設計，耐用質感俱佳。',              690.00,  890.00, 1, 0,  30, '2026-06-04 00:54:15'),
(11, '帆布托特包',         4, '大容量帆布包，多層收納設計，通勤購物皆適用。',            890.00, 1190.00, 1, 1,  25, '2026-06-04 00:54:15'),
(12, '絲巾',               4, '100% 蠶絲材質，多種配戴方式，提升整體造型質感。',         590.00,  790.00, 0, 0,   0, '2026-06-04 00:54:15');


-- =============================================================================
-- 資料表：product_variants
-- 用途：商品規格明細，記錄每件商品的尺寸（size）、顏色（color）與該規格庫存。
--       一件商品可對應多筆規格。
--       透過 product_id 外鍵關聯至 products，商品刪除時規格連帶刪除（CASCADE）。
-- =============================================================================
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
  `id`         int         NOT NULL AUTO_INCREMENT,
  `product_id` int         NOT NULL COMMENT '所屬商品 ID（FK → products.id）',
  `size`       varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '尺寸（如 S/M/L 或 26/27/28）',
  `color`      varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '顏色名稱',
  `stock`      int         DEFAULT '0' COMMENT '此規格庫存數量',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 初始規格資料：商品 1（T恤）、2（條紋上衣）、4（牛仔褲）、7（洋裝）、9（外套）
INSERT INTO `product_variants` (`id`, `product_id`, `size`, `color`, `stock`) VALUES
-- 商品 1：經典棉質圓領T恤（S/M/L × 白色/黑色）
( 1,  1, 'S', '白色',     20),
( 2,  1, 'M', '白色',     30),
( 3,  1, 'L', '白色',     20),
( 4,  1, 'S', '黑色',     15),
( 5,  1, 'M', '黑色',     25),
( 6,  1, 'L', '黑色',     10),
-- 商品 2：條紋長袖上衣（S/M/L × 藍白條紋）
( 7,  2, 'S', '藍白條紋', 20),
( 8,  2, 'M', '藍白條紋', 30),
( 9,  2, 'L', '藍白條紋', 15),
-- 商品 4：修身直筒牛仔褲（26/27/28 × 深藍）
(10,  4, '26', '深藍',    20),
(11,  4, '27', '深藍',    25),
(12,  4, '28', '深藍',    20),
-- 商品 7：碎花連身洋裝（S/M/L × 碎花）
(13,  7, 'S', '碎花',     15),
(14,  7, 'M', '碎花',     15),
(15,  7, 'L', '碎花',     10),
-- 商品 9：針織開襟外套（S/M/L × 米白）
(16,  9, 'S', '米白',     15),
(17,  9, 'M', '米白',     20),
(18,  9, 'L', '米白',     10);


-- =============================================================================
-- 資料表：members
-- 用途：前台會員帳號資料。
--       email 作為登入帳號（唯一索引），密碼以 bcrypt 雜湊儲存。
--       address 欄位預留供未來會員預設地址功能使用。
-- =============================================================================
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id`         int          NOT NULL AUTO_INCREMENT,
  `name`       varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '會員姓名',
  `email`      varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登入 Email（唯一）',
  `password`   varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bcrypt 雜湊密碼',
  `phone`      varchar(20)  COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '聯絡電話',
  `address`    text         COLLATE utf8mb4_unicode_ci COMMENT '預設收件地址（保留欄位）',
  `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 初始會員資料：測試帳號（密碼均為 password，bcrypt 雜湊）
INSERT INTO `members` (`id`, `name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, '測試會員', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912-345-678', NULL, '2026-06-04 00:54:15');


-- =============================================================================
-- 資料表：orders
-- 用途：訂單主檔，每筆訂單對應一位會員。
--       order_number 為系統自動產生的唯一訂單編號（格式：ORD + YmdHis + 3位亂數）。
--       status 欄位為 ENUM，共 5 種狀態：
--         pending（待確認）→ confirmed（已確認）→ shipping（運送中）
--         → delivered（已送達）/ cancelled（已取消）
--       收件人資訊（姓名、電話、地址）在下單時快照儲存，不隨會員資料異動。
-- =============================================================================
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id`                 int           NOT NULL AUTO_INCREMENT,
  `order_number`       varchar(50)   COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '唯一訂單編號（ORD+時間戳+亂數）',
  `member_id`          int           NOT NULL COMMENT '下單會員 ID（FK → members.id）',
  `total_amount`       decimal(10,2) NOT NULL COMMENT '訂單總金額',
  `status`             enum('pending','confirmed','shipping','delivered','cancelled')
                                     COLLATE utf8mb4_unicode_ci DEFAULT 'pending' COMMENT '訂單狀態',
  `recipient_name`     varchar(100)  COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收件人姓名',
  `recipient_phone`    varchar(20)   COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收件人電話',
  `recipient_address`  text          COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收件地址',
  `note`               text          COLLATE utf8mb4_unicode_ci COMMENT '買家備註',
  `created_at`         timestamp     NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 無初始訂單資料（由使用者實際結帳產生）


-- =============================================================================
-- 資料表：order_items
-- 用途：訂單明細，記錄每筆訂單購買的商品項目。
--       product_name、size、color、price 在下單時快照，
--       不會因商品後續修改而影響歷史訂單顯示。
--       透過 order_id 外鍵關聯至 orders，訂單刪除時明細連帶刪除（CASCADE）。
-- =============================================================================
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id`           int           NOT NULL AUTO_INCREMENT,
  `order_id`     int           NOT NULL COMMENT '所屬訂單 ID（FK → orders.id）',
  `product_id`   int           NOT NULL COMMENT '商品 ID（僅作參考，資料以快照欄位為準）',
  `product_name` varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '下單時商品名稱快照',
  `size`         varchar(20)   COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '規格：尺寸',
  `color`        varchar(50)   COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '規格：顏色',
  `quantity`     int           NOT NULL DEFAULT '1' COMMENT '購買數量',
  `price`        decimal(10,2) NOT NULL COMMENT '下單時單價快照',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 無初始訂單明細（隨訂單產生）


-- =============================================================================
-- 資料表：contact_messages
-- 用途：儲存前台聯絡表單送出的訊息。
--       is_read 欄位追蹤已讀 / 未讀狀態（管理員進入詳情頁時自動標記為已讀）。
--       後台儀表板以未讀數量顯示徽章提醒管理員。
-- =============================================================================
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
  `id`         int          NOT NULL AUTO_INCREMENT,
  `name`       varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '寄件人姓名',
  `email`      varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '寄件人 Email',
  `phone`      varchar(20)  COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '寄件人電話（選填）',
  `subject`    varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '訊息主旨',
  `message`    text         COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '訊息內容',
  `is_read`    tinyint(1)   DEFAULT '0' COMMENT '已讀狀態：0=未讀，1=已讀',
  `created_at` timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 無初始聯絡訊息（由使用者透過前台表單送出）


-- =============================================================================
-- 資料表：news
-- 用途：最新消息 / 文章管理。
--       is_published = 0 為草稿（不顯示於前台），= 1 為已發佈。
--       published_at 記錄實際發佈時間；從草稿切換為發佈時自動填入當前時間。
--       summary 為列表頁摘要文字；content 為富文字編輯器完整內文（LONGTEXT）。
-- =============================================================================
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id`           int        NOT NULL AUTO_INCREMENT,
  `title`        varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章標題',
  `summary`      text       COLLATE utf8mb4_unicode_ci COMMENT '摘要（列表頁顯示用）',
  `content`      longtext   COLLATE utf8mb4_unicode_ci COMMENT '完整內文（支援 HTML 富文字）',
  `is_published` tinyint(1) DEFAULT '0' COMMENT '發佈狀態：0=草稿，1=已發佈',
  `published_at` datetime   DEFAULT NULL COMMENT '發佈時間（草稿時為 NULL）',
  `created_at`   timestamp  NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 初始文章資料（3 篇已發佈）
INSERT INTO `news` (`id`, `title`, `summary`, `content`, `is_published`, `published_at`, `created_at`) VALUES
(1,
 '2024 秋冬新品正式上市',
 '本季以「都會浪漫」為主題，帶來全新秋冬系列。',
 '<p>CI3 Fashion 2024 秋冬系列正式登場！</p>',
 1, '2026-06-04 00:54:15', '2026-06-04 00:54:15'),
(2,
 '會員專屬優惠活動',
 '即日起加入會員，享首購九折優惠。',
 '<p>感謝各位長期以來的支持，推出會員專屬優惠活動。</p>',
 1, '2026-06-01 00:54:15', '2026-06-04 00:54:15'),
(3,
 '實體門市重新開幕',
 '全新裝潢的旗艦門市盛大開幕。',
 '<p>歷經三個月全新裝潢，旗艦門市正式重新開幕。</p>',
 1, '2026-05-28 00:54:15', '2026-06-04 00:54:15');


-- =============================================================================
-- 資料表：ci_sessions
-- 用途：CodeIgniter 框架 Session 資料庫驅動所使用的 Session 儲存表。
--       此表由框架自動讀寫，開發者通常不需手動操作。
--       timestamp 欄位供 Session GC（垃圾回收）機制定期清除過期 Session。
--       data 欄位以 BLOB 儲存序列化的 Session 資料。
-- =============================================================================
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id`         varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Session ID',
  `ip_address` varchar(45)  COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用戶端 IP 位址',
  `timestamp`  int unsigned NOT NULL DEFAULT '0' COMMENT 'Unix 時間戳，供 GC 清除過期 Session',
  `data`       blob         NOT NULL COMMENT '序列化的 Session 資料',
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 無初始 Session 資料（由框架自動管理）


-- =============================================================================
SET FOREIGN_KEY_CHECKS = 1;
-- =============================================================================
-- 匯入完成。AUTO_INCREMENT 起始值說明：
--   admins           → 下一筆 ID 從 2 開始
--   categories       → 下一筆 ID 從 5 開始
--   products         → 下一筆 ID 從 13 開始
--   product_variants → 下一筆 ID 從 19 開始
--   members          → 下一筆 ID 從 2 開始
--   news             → 下一筆 ID 從 4 開始
-- =============================================================================
