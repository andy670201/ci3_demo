# CI3 Fashion 服裝買賣網站 - 完整建置指南（Docker 版）

> 請 Claude Code 依照本文件從零開始建立整個 CodeIgniter 3 專案。
> 請逐步執行，每個步驟完成後再進行下一步。

---

## 專案概述

- **公司性質**：服裝買賣（時尚電商）
- **技術棧**：CodeIgniter 3 + Bootstrap 5（本地端）+ JavaScript
- **資料庫**：MySQL 8.0
- **部署環境**：Docker（PHP 8.2-apache image）
- **目錄結構**：專案根目錄放 Docker 設定，CI3 原始碼放 `src/`
- **網址**：http://localhost:8080/
- **特別說明**：所有前端資源本地化，可完全離線運作

---

## 帳號資訊

| 類型 | 帳號 | 密碼 |
|------|------|------|
| 後台管理員 | admin | password |
| MySQL | admin | password |
| 前台測試會員 | test@example.com | password |

---

## 第一步：建立專案目錄結構

在 Windows 上建立以下結構：

```
ci3_demo/          ← 專案根目錄（Docker 設定在這裡）
├── Dockerfile
├── docker-compose.yml
└── src/           ← CI3 原始碼放這裡
```

---

## 第二步：建立 Docker 設定檔

### `Dockerfile`（放在專案根目錄）

```dockerfile
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libsqlite3-dev \
    libonig-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    zip \
    mbstring

RUN a2enmod rewrite

RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf
```

### `docker-compose.yml`（放在專案根目錄）

```yaml
services:
  web:
    build: .
    container_name: ci3-web
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
    depends_on:
      - db

  db:
    image: mysql:8.0
    container_name: ci3-db
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: ci3_fashion
      MYSQL_USER: admin
      MYSQL_PASSWORD: password
    ports:
      - "3306:3306"
    volumes:
      - ci3_db_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: ci3-pma
    restart: always
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
    depends_on:
      - db

volumes:
  ci3_db_data:
```

---

## 第三步：下載 CodeIgniter 3 並修正 PHP 8.2 相容性

將 CodeIgniter 3.1.13 解壓縮後，所有內容放入 `src/` 目錄。

修改 `src/index.php`，將：
```php
error_reporting(-1);
```
改為：
```php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
```

---

## 第四步：下載 Bootstrap 5 本地端資源

專案需在無網路環境下 demo，所有前端資源必須本地化。
下載後放入 `src/assets/` 目錄：

```
src/assets/
├── css/
│   ├── bootstrap.min.css
│   └── bootstrap-icons.css
├── js/
│   └── bootstrap.bundle.min.js
└── fonts/
    ├── bootstrap-icons.woff2
    └── bootstrap-icons.woff
```

下載來源：
- `bootstrap.min.css` → https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css
- `bootstrap.bundle.min.js` → https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js
- `bootstrap-icons.css` → https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css
- `bootstrap-icons.woff2` → https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2
- `bootstrap-icons.woff` → https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff

**注意**：`bootstrap-icons.css` 內的字型路徑需改為 `../fonts/`

---

## 第五步：建立 MySQL 資料庫與初始資料

先啟動容器：

```bash
docker compose up -d --build
```

等待 ci3-db 啟動後，建立資料庫結構與初始資料：

```bash
docker exec -i ci3-db mysql -u admin -ppassword << 'EOF'
CREATE DATABASE IF NOT EXISTS ci3_fashion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ci3_fashion;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    sort_order INT DEFAULT 0
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    original_price DECIMAL(10,2),
    is_active TINYINT(1) DEFAULT 1,
    is_featured TINYINT(1) DEFAULT 0,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size VARCHAR(20),
    color VARCHAR(50),
    stock INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE ci_sessions (
    id VARCHAR(128) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    timestamp INT(10) UNSIGNED DEFAULT 0 NOT NULL,
    data BLOB NOT NULL,
    PRIMARY KEY (id),
    KEY ci_sessions_timestamp (timestamp)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    member_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','confirmed','shipping','delivered','cancelled') DEFAULT 'pending',
    recipient_name VARCHAR(100) NOT NULL,
    recipient_phone VARCHAR(20) NOT NULL,
    recipient_address TEXT NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    size VARCHAR(20),
    color VARCHAR(50),
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    summary TEXT,
    content LONGTEXT,
    is_published TINYINT(1) DEFAULT 0,
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 管理員（密碼: password）
INSERT INTO admins (username, password, name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '系統管理員');

-- 商品分類
INSERT INTO categories (name, slug, sort_order) VALUES
('上衣', 'tops', 1), ('褲子', 'pants', 2),
('洋裝', 'dresses', 3), ('配件', 'accessories', 4);

-- 商品（12 筆）
INSERT INTO products (name, category_id, description, price, original_price, is_active, is_featured, stock) VALUES
('經典棉質圓領T恤',  1, '100% 純棉材質，柔軟舒適，適合日常穿著，多色可選。',  590,  790, 1, 1, 100),
('條紋長袖上衣',     1, '海軍風條紋設計，法式休閒風格，適合搭配各式下著。',    890, 1200, 1, 1,  80),
('V領雪紡上衣',      1, '輕盈雪紡材質，V領設計修飾頸線，優雅百搭。',           750,  990, 1, 0,  60),
('針織開襟外套',     1, '柔軟針織材質，百搭開襟設計，春秋必備單品。',          1290, 1690, 1, 1,  55),
('修身直筒牛仔褲',   2, '經典修身剪裁，高品質丹寧布料，百搭必備款。',          1290, 1690, 1, 1,  90),
('寬鬆休閒長褲',     2, '舒適寬鬆版型，高腰設計顯瘦，多種場合適用。',          1090, 1390, 1, 0,  70),
('A字短裙',          2, '清新A字版型，腰部設計修飾曲線，通勤休閒皆宜。',         790,  990, 1, 0,  50),
('碎花連身洋裝',     3, '浪漫碎花印花，腰部綁帶設計，約會出遊首選。',           1590, 2190, 1, 1,  40),
('簡約素色洋裝',     3, '極簡風格設計，優質面料垂墜感佳，職場休閒兩用。',       1390, 1790, 1, 0,  45),
('真皮皮帶',         4, '頭層牛皮材質，經典扣環設計，耐用質感俱佳。',            690,  890, 1, 0,  30),
('帆布托特包',       4, '大容量帆布包，多層收納設計，通勤購物皆適用。',           890, 1190, 1, 1,  25),
('絲巾',             4, '100% 蠶絲材質，多種配戴方式，提升整體造型質感。',        590,  790, 0, 0,   0);

-- 商品規格
INSERT INTO product_variants (product_id, size, color, stock) VALUES
(1,'S','白色',20),(1,'M','白色',30),(1,'L','白色',20),
(1,'S','黑色',15),(1,'M','黑色',25),(1,'L','黑色',10),
(2,'S','藍白條紋',20),(2,'M','藍白條紋',30),(2,'L','藍白條紋',15),
(4,'S','米白',15),(4,'M','米白',20),(4,'L','米白',10),
(5,'26','深藍',20),(5,'27','深藍',25),(5,'28','深藍',20),
(8,'S','碎花',15),(8,'M','碎花',15),(8,'L','碎花',10);

-- 最新消息（3 則）
INSERT INTO news (title, summary, content, is_published, published_at) VALUES
('2024 秋冬新品正式上市', '本季以「都會浪漫」為主題，帶來全新秋冬系列。',
 '<p>CI3 Fashion 2024 秋冬系列正式登場！本季融合優雅與實用，帶來多款必備單品。</p>',
 1, NOW()),
('會員專屬優惠活動', '即日起加入會員，享首購九折優惠。',
 '<p>感謝各位長期以來的支持，特別推出會員專屬優惠活動。</p>',
 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('實體門市重新開幕', '全新裝潢的旗艦門市盛大開幕。',
 '<p>歷經三個月全新裝潢，旗艦門市正式重新開幕。</p>',
 1, DATE_SUB(NOW(), INTERVAL 7 DAY));

-- 測試會員（密碼: password）
INSERT INTO members (name, email, password, phone) VALUES
('測試會員', 'test@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '0912-345-678');
EOF
echo "資料庫建立完成"
```

或直接匯入已備妥的 `src/database.sql`：

```bash
docker exec -i ci3-db mysql -u admin -ppassword < src/database.sql
```

---

## 第六步：CI3 設定檔

### `src/application/config/config.php` 修改以下欄位

```php
$config['base_url']                = 'http://localhost:8080/';
$config['index_page']              = '';
$config['encryption_key']          = 'ci3fashion2024secretkey';
$config['sess_driver']             = 'database';
$config['sess_cookie_name']        = 'ci3_session';
$config['sess_expiration']         = 7200;
$config['sess_save_path']          = 'ci_sessions';
$config['sess_match_ip']           = FALSE;
$config['sess_time_to_update']     = 300;
$config['sess_regenerate_destroy'] = FALSE;
```

### `src/application/config/database.php`

> **重要**：Docker 環境中 hostname 必須填 `db`（docker-compose service 名稱），不是 `localhost`。

```php
$db['default'] = array(
    'dsn'          => '',
    'hostname'     => 'db',
    'username'     => 'admin',
    'password'     => 'password',
    'database'     => 'ci3_fashion',
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => FALSE,
    'db_debug'     => TRUE,
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8mb4',
    'dbcollat'     => 'utf8mb4_unicode_ci',
    'swap_pre'     => '',
    'encrypt'      => FALSE,
    'compress'     => FALSE,
    'stricton'     => FALSE,
    'failover'     => array(),
    'save_queries' => TRUE
);
```

### `src/application/config/autoload.php`

```php
$autoload['libraries'] = array('database', 'session', 'form_validation');
$autoload['helper']    = array('url', 'form', 'html', 'security');
```

### `src/application/config/routes.php`

```php
$route['default_controller']          = 'Home';
$route['404_override']                = '';
$route['translate_uri_dashes']        = FALSE;

$route['about']                        = 'Front/About/index';
$route['products']                     = 'Front/Products/index';
$route['products/category/(:any)']     = 'Front/Products/category/$1';
$route['products/(:num)']              = 'Front/Products/detail/$1';
$route['news']                         = 'Front/News/index';
$route['news/(:num)']                  = 'Front/News/detail/$1';
$route['contact']                      = 'Front/Contact/index';
$route['contact/send']                 = 'Front/Contact/send';
$route['cart']                         = 'Front/Cart/index';
$route['cart/add']                     = 'Front/Cart/add';
$route['cart/update']                  = 'Front/Cart/update';
$route['cart/remove/(:num)']           = 'Front/Cart/remove/$1';
$route['member/register']              = 'Front/Member/register';
$route['member/login']                 = 'Front/Member/login';
$route['member/logout']                = 'Front/Member/logout';
$route['member/dashboard']             = 'Front/Member/dashboard';
$route['member/orders/(:any)']         = 'Front/Member/order_detail/$1';
$route['checkout']                     = 'Front/Checkout/index';
$route['checkout/confirm']             = 'Front/Checkout/confirm';
$route['checkout/success/(:any)']      = 'Front/Checkout/success/$1';
$route['admin']                        = 'Admin/Auth/login';
$route['admin/login']                  = 'Admin/Auth/login';
$route['admin/logout']                 = 'Admin/Auth/logout';
$route['admin/dashboard']              = 'Admin/Dashboard/index';
$route['admin/products']               = 'Admin/Products/index';
$route['admin/products/create']        = 'Admin/Products/create';
$route['admin/products/store']         = 'Admin/Products/store';
$route['admin/products/edit/(:num)']   = 'Admin/Products/edit/$1';
$route['admin/products/update/(:num)'] = 'Admin/Products/update/$1';
$route['admin/products/delete/(:num)'] = 'Admin/Products/delete/$1';
$route['admin/products/toggle/(:num)'] = 'Admin/Products/toggle/$1';
$route['admin/orders']                 = 'Admin/Orders/index';
$route['admin/orders/(:num)']          = 'Admin/Orders/detail/$1';
$route['admin/orders/status/(:num)']   = 'Admin/Orders/update_status/$1';
$route['admin/news']                   = 'Admin/News/index';
$route['admin/news/create']            = 'Admin/News/create';
$route['admin/news/store']             = 'Admin/News/store';
$route['admin/news/edit/(:num)']       = 'Admin/News/edit/$1';
$route['admin/news/update/(:num)']     = 'Admin/News/update/$1';
$route['admin/news/delete/(:num)']     = 'Admin/News/delete/$1';
$route['admin/news/toggle/(:num)']     = 'Admin/News/toggle/$1';
$route['admin/contacts']               = 'Admin/Contacts/index';
$route['admin/contacts/(:num)']        = 'Admin/Contacts/detail/$1';
$route['admin/contacts/delete/(:num)'] = 'Admin/Contacts/delete/$1';
```

---

## 第七步：建立目錄結構

在 `src/application/` 下建立：

```
controllers/Front/
controllers/Admin/
views/layouts/
views/frontend/home/
views/frontend/products/
views/frontend/news/
views/frontend/cart/
views/frontend/member/
views/frontend/checkout/
views/frontend/pages/
views/admin/auth/
views/admin/dashboard/
views/admin/products/
views/admin/orders/
views/admin/news/
views/admin/contacts/
```

---

## 第八步：MY_Controller

### `src/application/core/MY_Controller.php`

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public function __construct() { parent::__construct(); }
}

// 前台基底：自動載入商品/分類 Model，render() 注入共用資料
class Front_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
    }
    protected function render($view, $data = []) {
        $cart = $this->session->userdata('cart') ?: [];
        $data['cart_count'] = array_sum(array_column($cart, 'quantity'));
        $data['member']     = $this->session->userdata('member');
        $data['categories'] = $this->Category_model->get_all();
        $this->load->view('layouts/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layouts/footer', $data);
    }
}

// 後台基底：檢查登入狀態，render() 注入管理員名稱與未讀數
class Admin_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('admin_logged_in')) redirect('admin/login');
        $this->load->model('Contact_model');
    }
    protected function render($view, $data = []) {
        $data['admin_name']   = $this->session->userdata('admin_name');
        $data['unread_count'] = $this->Contact_model->count_unread();
        $this->load->view('layouts/admin_header', $data);
        $this->load->view($view, $data);
        $this->load->view('layouts/admin_footer', $data);
    }
}
```

---

## 第九步：Models

### `src/application/models/Admin_model.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_model extends CI_Model {
    public function verify($username, $password) {
        $admin = $this->db->where('username', $username)->get('admins')->row();
        if ($admin && password_verify($password, $admin->password)) return $admin;
        return false;
    }
}
```

### `src/application/models/Category_model.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category_model extends CI_Model {
    public function get_all()        { return $this->db->order_by('sort_order')->get('categories')->result(); }
    public function get_by_slug($s)  { return $this->db->where('slug', $s)->get('categories')->row(); }
    public function get_by_id($id)   { return $this->db->where('id', $id)->get('categories')->row(); }
}
```

### `src/application/models/Product_model.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_model extends CI_Model {
    public function get_active($category_id = null, $limit = null, $offset = 0) {
        $this->db->select('p.*, c.name as category_name')->from('products p')
                 ->join('categories c','c.id = p.category_id')
                 ->where('p.is_active',1)->order_by('p.created_at','DESC');
        if ($category_id) $this->db->where('p.category_id', $category_id);
        if ($limit)       $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
    public function count_active($category_id = null) {
        $this->db->where('is_active',1);
        if ($category_id) $this->db->where('category_id', $category_id);
        return $this->db->count_all_results('products');
    }
    public function get_featured($limit = 8) {
        return $this->db->select('p.*, c.name as category_name')->from('products p')
                        ->join('categories c','c.id = p.category_id')
                        ->where('p.is_active',1)->where('p.is_featured',1)
                        ->limit($limit)->get()->result();
    }
    public function get_by_id($id) {
        return $this->db->select('p.*, c.name as category_name')->from('products p')
                        ->join('categories c','c.id = p.category_id')
                        ->where('p.id',$id)->get()->row();
    }
    public function get_variants($pid)   { return $this->db->where('product_id',$pid)->get('product_variants')->result(); }
    public function get_all_admin() {
        return $this->db->select('p.*, c.name as category_name')->from('products p')
                        ->join('categories c','c.id = p.category_id')
                        ->order_by('p.created_at','DESC')->get()->result();
    }
    public function insert($data)          { $this->db->insert('products',$data); return $this->db->insert_id(); }
    public function insert_variant($data)  { return $this->db->insert('product_variants',$data); }
    public function update($id,$data)      { return $this->db->where('id',$id)->update('products',$data); }
    public function delete_variants($id)   { return $this->db->where('product_id',$id)->delete('product_variants'); }
    public function delete($id)            { return $this->db->where('id',$id)->delete('products'); }
    public function toggle($id) {
        $p = $this->get_by_id($id);
        return $this->db->where('id',$id)->update('products',['is_active'=> $p->is_active ? 0 : 1]);
    }
}
```

### `src/application/models/Member_model.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Member_model extends CI_Model {
    public function get_by_email($email) { return $this->db->where('email',$email)->get('members')->row(); }
    public function get_by_id($id)       { return $this->db->where('id',$id)->get('members')->row(); }
    public function register($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->db->insert('members',$data);
        return $this->db->insert_id();
    }
    public function verify($email, $password) {
        $m = $this->get_by_email($email);
        if ($m && password_verify($password, $m->password)) return $m;
        return false;
    }
}
```

### `src/application/models/Order_model.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order_model extends CI_Model {
    public function create($data, $items) {
        $data['order_number'] = 'ORD'.date('YmdHis').rand(100,999);
        $this->db->insert('orders',$data);
        $oid = $this->db->insert_id();
        foreach ($items as $item) { $item['order_id']=$oid; $this->db->insert('order_items',$item); }
        return $data['order_number'];
    }
    public function get_by_member($mid) { return $this->db->where('member_id',$mid)->order_by('created_at','DESC')->get('orders')->result(); }
    public function get_detail($order_number, $mid = null) {
        $this->db->where('order_number',$order_number);
        if ($mid) $this->db->where('member_id',$mid);
        return $this->db->get('orders')->row();
    }
    public function get_items($oid)      { return $this->db->where('order_id',$oid)->get('order_items')->result(); }
    public function get_all()            { return $this->db->order_by('created_at','DESC')->get('orders')->result(); }
    public function update_status($id,$s){ return $this->db->where('id',$id)->update('orders',['status'=>$s]); }
    public function count_all()          { return $this->db->count_all('orders'); }
}
```

### `src/application/models/News_model.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class News_model extends CI_Model {
    public function get_published($limit = null, $offset = 0) {
        $this->db->where('is_published',1)->order_by('published_at','DESC');
        if ($limit) $this->db->limit($limit,$offset);
        return $this->db->get('news')->result();
    }
    public function count_published() { return $this->db->where('is_published',1)->count_all_results('news'); }
    public function get_by_id($id)    { return $this->db->where('id',$id)->get('news')->row(); }
    public function get_all()         { return $this->db->order_by('created_at','DESC')->get('news')->result(); }
    public function insert($data)     { return $this->db->insert('news',$data); }
    public function update($id,$data) { return $this->db->where('id',$id)->update('news',$data); }
    public function delete($id)       { return $this->db->where('id',$id)->delete('news'); }
    public function toggle($id) {
        $n = $this->get_by_id($id);
        $d = ['is_published'=> $n->is_published ? 0 : 1];
        if (!$n->is_published) $d['published_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id',$id)->update('news',$d);
    }
}
```

### `src/application/models/Contact_model.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contact_model extends CI_Model {
    public function insert($data)  { return $this->db->insert('contact_messages',$data); }
    public function get_all()      { return $this->db->order_by('created_at','DESC')->get('contact_messages')->result(); }
    public function get_by_id($id) { return $this->db->where('id',$id)->get('contact_messages')->row(); }
    public function mark_read($id) { return $this->db->where('id',$id)->update('contact_messages',['is_read'=>1]); }
    public function delete($id)    { return $this->db->where('id',$id)->delete('contact_messages'); }
    public function count_unread() { return $this->db->where('is_read',0)->count_all_results('contact_messages'); }
}
```

---

## 第十步：前台 Controllers

### `src/application/controllers/Home.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends Front_Controller {
    public function __construct() { parent::__construct(); $this->load->model('News_model'); }
    public function index() {
        $data['featured_products'] = $this->Product_model->get_featured(8);
        $data['latest_news']       = $this->News_model->get_published(3);
        $this->render('frontend/home/index', $data);
    }
}
```

### `src/application/controllers/Front/About.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class About extends Front_Controller {
    public function index() { $this->render('frontend/pages/about',[]); }
}
```

### `src/application/controllers/Front/Products.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends Front_Controller {
    public function index() {
        $page=max(1,(int)($this->input->get('page')?:1)); $pp=12;
        $data['products']=$this->Product_model->get_active(null,$pp,($page-1)*$pp);
        $data['total']=$this->Product_model->count_active();
        $data['page']=$page; $data['per_page']=$pp; $data['current_category']=null;
        $this->render('frontend/products/index',$data);
    }
    public function category($slug) {
        $cat=$this->Category_model->get_by_slug($slug);
        if (!$cat) show_404();
        $page=max(1,(int)($this->input->get('page')?:1)); $pp=12;
        $data['category']=$cat;
        $data['products']=$this->Product_model->get_active($cat->id,$pp,($page-1)*$pp);
        $data['total']=$this->Product_model->count_active($cat->id);
        $data['page']=$page; $data['per_page']=$pp; $data['current_category']=$slug;
        $this->render('frontend/products/index',$data);
    }
    public function detail($id) {
        $p=$this->Product_model->get_by_id($id);
        if (!$p||!$p->is_active) show_404();
        $data['product']=$p;
        $data['variants']=$this->Product_model->get_variants($id);
        $data['related']=$this->Product_model->get_active($p->category_id,4);
        $this->render('frontend/products/detail',$data);
    }
}
```

### `src/application/controllers/Front/Cart.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cart extends Front_Controller {
    public function index() {
        $cart=$this->session->userdata('cart')?:[];
        $data['cart']=$cart;
        $data['total']=array_sum(array_map(function($i){return $i['price']*$i['quantity'];},$cart));
        $this->render('frontend/cart/index',$data);
    }
    public function add() {
        if (!$this->session->userdata('member')) {
            echo json_encode(['success'=>false,'message'=>'請先登入會員','redirect'=>base_url('member/login')]);
            return;
        }
        $pid=(int)$this->input->post('product_id');
        $size=$this->input->post('size');
        $color=$this->input->post('color');
        $qty=(int)($this->input->post('quantity')?:1);
        $p=$this->Product_model->get_by_id($pid);
        if (!$p) { echo json_encode(['success'=>false,'message'=>'商品不存在']); return; }
        $cart=$this->session->userdata('cart')?:[];
        $key=$pid.'_'.$size.'_'.$color;
        if (isset($cart[$key])) $cart[$key]['quantity']+=$qty;
        else $cart[$key]=['product_id'=>$pid,'name'=>$p->name,'price'=>$p->price,'size'=>$size,'color'=>$color,'quantity'=>$qty];
        $this->session->set_userdata('cart',$cart);
        echo json_encode(['success'=>true,'cart_count'=>array_sum(array_column($cart,'quantity')),'message'=>'已加入購物車']);
    }
    public function update() {
        $cart=$this->session->userdata('cart')?:[];
        $key=$this->input->post('key'); $qty=(int)$this->input->post('quantity');
        if ($qty<=0) unset($cart[$key]); else $cart[$key]['quantity']=$qty;
        $this->session->set_userdata('cart',$cart); redirect('cart');
    }
    public function remove($pid) {
        $cart=$this->session->userdata('cart')?:[];
        foreach ($cart as $k=>$i) { if ($i['product_id']==$pid) { unset($cart[$k]); break; } }
        $this->session->set_userdata('cart',$cart); redirect('cart');
    }
}
```

### `src/application/controllers/Front/Member.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Member extends Front_Controller {
    public function __construct() { parent::__construct(); $this->load->model('Member_model'); $this->load->model('Order_model'); }
    public function register() {
        if ($this->session->userdata('member')) redirect('/');
        if ($this->input->method()==='post') {
            $this->form_validation->set_rules('name','姓名','required');
            $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[members.email]');
            $this->form_validation->set_rules('password','密碼','required|min_length[6]');
            if ($this->form_validation->run()) {
                $id=$this->Member_model->register(['name'=>$this->input->post('name'),'email'=>$this->input->post('email'),'password'=>$this->input->post('password'),'phone'=>$this->input->post('phone')]);
                $this->session->set_userdata('member',(array)$this->Member_model->get_by_id($id));
                redirect('member/dashboard');
            }
        }
        $this->render('frontend/member/register',[]);
    }
    public function login() {
        if ($this->session->userdata('member')) redirect('/');
        if ($this->input->method()==='post') {
            $m=$this->Member_model->verify($this->input->post('email'),$this->input->post('password'));
            if ($m) { $this->session->set_userdata('member',(array)$m); redirect('member/dashboard'); }
            $this->render('frontend/member/login',['error'=>'Email 或密碼錯誤']); return;
        }
        $this->render('frontend/member/login',[]);
    }
    public function logout() { $this->session->unset_userdata('member'); redirect('/'); }
    public function dashboard() {
        if (!$this->session->userdata('member')) redirect('member/login');
        $m=$this->session->userdata('member');
        $data['orders']=$this->Order_model->get_by_member($m['id']);
        $this->render('frontend/member/dashboard',$data);
    }
    public function order_detail($order_number) {
        if (!$this->session->userdata('member')) redirect('member/login');
        $m=$this->session->userdata('member');
        $data['order']=$this->Order_model->get_detail($order_number,$m['id']);
        if (!$data['order']) show_404();
        $data['items']=$this->Order_model->get_items($data['order']->id);
        $this->render('frontend/member/order_detail',$data);
    }
}
```

### `src/application/controllers/Front/Checkout.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Checkout extends Front_Controller {
    public function __construct() { parent::__construct(); $this->load->model('Order_model'); }
    public function index() {
        if (!$this->session->userdata('member')) redirect('member/login');
        $cart=$this->session->userdata('cart')?:[];
        if (empty($cart)) redirect('cart');
        $data['cart']=$cart;
        $data['total']=array_sum(array_map(function($i){return $i['price']*$i['quantity'];},$cart));
        $data['member']=$this->session->userdata('member');
        $this->render('frontend/checkout/index',$data);
    }
    public function confirm() {
        if (!$this->session->userdata('member')) redirect('member/login');
        $cart=$this->session->userdata('cart')?:[];
        $m=$this->session->userdata('member');
        if (empty($cart)) redirect('cart');
        $items=[];
        foreach ($cart as $item) {
            $items[]=['product_id'=>$item['product_id'],'product_name'=>$item['name'],'size'=>$item['size'],'color'=>$item['color'],'quantity'=>$item['quantity'],'price'=>$item['price']];
        }
        $total=array_sum(array_map(function($i){return $i['price']*$i['quantity'];},$cart));
        $no=$this->Order_model->create(['member_id'=>$m['id'],'total_amount'=>$total,'recipient_name'=>$this->input->post('recipient_name'),'recipient_phone'=>$this->input->post('recipient_phone'),'recipient_address'=>$this->input->post('recipient_address'),'note'=>$this->input->post('note')],$items);
        $this->session->unset_userdata('cart');
        redirect('checkout/success/'.$no);
    }
    public function success($order_number) {
        $m=$this->session->userdata('member');
        $data['order']=$this->Order_model->get_detail($order_number,$m?$m['id']:null);
        $this->render('frontend/checkout/success',$data);
    }
}
```

### `src/application/controllers/Front/News.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class News extends Front_Controller {
    public function __construct() { parent::__construct(); $this->load->model('News_model'); }
    public function index() {
        $page=max(1,(int)($this->input->get('page')?:1)); $pp=9;
        $data['news']=$this->News_model->get_published($pp,($page-1)*$pp);
        $data['total']=$this->News_model->count_published();
        $data['page']=$page; $data['per_page']=$pp;
        $this->render('frontend/news/index',$data);
    }
    public function detail($id) {
        $a=$this->News_model->get_by_id($id);
        if (!$a||!$a->is_published) show_404();
        $data['article']=$a; $data['recent_news']=$this->News_model->get_published(3);
        $this->render('frontend/news/detail',$data);
    }
}
```

### `src/application/controllers/Front/Contact.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contact extends Front_Controller {
    public function __construct() { parent::__construct(); $this->load->model('Contact_model'); }
    public function index() { $data['success']=$this->session->flashdata('success'); $this->render('frontend/pages/contact',$data); }
    public function send() {
        $this->form_validation->set_rules('name','姓名','required');
        $this->form_validation->set_rules('email','Email','required|valid_email');
        $this->form_validation->set_rules('subject','主旨','required');
        $this->form_validation->set_rules('message','訊息','required');
        if ($this->form_validation->run()) {
            $this->Contact_model->insert(['name'=>$this->input->post('name'),'email'=>$this->input->post('email'),'phone'=>$this->input->post('phone'),'subject'=>$this->input->post('subject'),'message'=>$this->input->post('message')]);
            $this->session->set_flashdata('success','感謝您的來信！我們將儘快回覆您。');
        }
        redirect('contact');
    }
}
```

---

## 第十一步：後台 Controllers

### `src/application/controllers/Admin/Auth.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends MY_Controller {
    public function __construct() { parent::__construct(); $this->load->model('Admin_model'); }
    public function login() {
        if ($this->session->userdata('admin_logged_in')) redirect('admin/dashboard');
        if ($this->input->method()==='post') {
            $a=$this->Admin_model->verify($this->input->post('username'),$this->input->post('password'));
            if ($a) { $this->session->set_userdata(['admin_logged_in'=>true,'admin_name'=>$a->name]); redirect('admin/dashboard'); }
            $this->load->view('admin/auth/login',['error'=>'帳號或密碼錯誤']); return;
        }
        $this->load->view('admin/auth/login',[]);
    }
    public function logout() { $this->session->unset_userdata(['admin_logged_in','admin_name']); redirect('admin/login'); }
}
```

### `src/application/controllers/Admin/Dashboard.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends Admin_Controller {
    public function index() {
        $this->load->model('Order_model');
        $data['stats']=['total_products'=>$this->db->count_all('products'),'active_products'=>$this->db->where('is_active',1)->count_all_results('products'),'total_orders'=>$this->Order_model->count_all(),'pending_orders'=>$this->db->where('status','pending')->count_all_results('orders'),'unread_messages'=>$this->Contact_model->count_unread(),'total_members'=>$this->db->count_all('members')];
        $data['recent_orders']=$this->db->order_by('created_at','DESC')->limit(5)->get('orders')->result();
        $data['recent_messages']=$this->db->where('is_read',0)->order_by('created_at','DESC')->limit(5)->get('contact_messages')->result();
        $this->render('admin/dashboard/index',$data);
    }
}
```

### `src/application/controllers/Admin/Products.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends Admin_Controller {
    public function __construct() { parent::__construct(); $this->load->model('Product_model'); $this->load->model('Category_model'); }
    public function index() { $this->render('admin/products/index',['products'=>$this->Product_model->get_all_admin(),'categories'=>$this->Category_model->get_all()]); }
    public function create() { $this->render('admin/products/form',['product'=>null,'variants'=>[],'categories'=>$this->Category_model->get_all()]); }
    public function store() {
        $id=$this->Product_model->insert(['name'=>$this->input->post('name'),'category_id'=>$this->input->post('category_id'),'description'=>$this->input->post('description'),'price'=>$this->input->post('price'),'original_price'=>$this->input->post('original_price'),'stock'=>$this->input->post('stock'),'is_active'=>$this->input->post('is_active')?1:0,'is_featured'=>$this->input->post('is_featured')?1:0]);
        $this->_save_variants($id);
        $this->session->set_flashdata('success','商品已新增'); redirect('admin/products');
    }
    public function edit($id) { $this->render('admin/products/form',['product'=>$this->Product_model->get_by_id($id),'variants'=>$this->Product_model->get_variants($id),'categories'=>$this->Category_model->get_all()]); }
    public function update($id) {
        $this->Product_model->update($id,['name'=>$this->input->post('name'),'category_id'=>$this->input->post('category_id'),'description'=>$this->input->post('description'),'price'=>$this->input->post('price'),'original_price'=>$this->input->post('original_price'),'stock'=>$this->input->post('stock'),'is_active'=>$this->input->post('is_active')?1:0,'is_featured'=>$this->input->post('is_featured')?1:0]);
        $this->Product_model->delete_variants($id); $this->_save_variants($id);
        $this->session->set_flashdata('success','商品已更新'); redirect('admin/products');
    }
    public function delete($id) { $this->Product_model->delete($id); $this->session->set_flashdata('success','商品已刪除'); redirect('admin/products'); }
    public function toggle($id) { $this->Product_model->toggle($id); redirect('admin/products'); }
    private function _save_variants($pid) {
        $sizes=$this->input->post('sizes')?:[];
        $colors=$this->input->post('colors')?:[];
        $stocks=$this->input->post('variant_stocks')?:[];
        foreach ($sizes as $i=>$s) { if ($s&&isset($colors[$i])) $this->Product_model->insert_variant(['product_id'=>$pid,'size'=>$s,'color'=>$colors[$i],'stock'=>$stocks[$i]??0]); }
    }
}
```

### `src/application/controllers/Admin/Orders.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends Admin_Controller {
    public function __construct() { parent::__construct(); $this->load->model('Order_model'); }
    public function index() { $this->render('admin/orders/index',['orders'=>$this->Order_model->get_all()]); }
    public function detail($id) {
        $data['order']=$this->db->where('id',$id)->get('orders')->row();
        if (!$data['order']) show_404();
        $data['items']=$this->Order_model->get_items($id);
        $this->render('admin/orders/detail',$data);
    }
    public function update_status($id) {
        $this->Order_model->update_status($id,$this->input->post('status'));
        $this->session->set_flashdata('success','訂單狀態已更新'); redirect('admin/orders/'.$id);
    }
}
```

### `src/application/controllers/Admin/News.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class News extends Admin_Controller {
    public function __construct() { parent::__construct(); $this->load->model('News_model'); }
    public function index() { $this->render('admin/news/index',['news'=>$this->News_model->get_all()]); }
    public function create() { $this->render('admin/news/form',['news'=>null]); }
    public function store() {
        $p=$this->input->post('is_published')?1:0;
        $this->News_model->insert(['title'=>$this->input->post('title'),'summary'=>$this->input->post('summary'),'content'=>$this->input->post('content'),'is_published'=>$p,'published_at'=>$p?date('Y-m-d H:i:s'):null]);
        $this->session->set_flashdata('success','文章已建立'); redirect('admin/news');
    }
    public function edit($id) { $this->render('admin/news/form',['news'=>$this->News_model->get_by_id($id)]); }
    public function update($id) {
        $p=$this->input->post('is_published')?1:0;
        $this->News_model->update($id,['title'=>$this->input->post('title'),'summary'=>$this->input->post('summary'),'content'=>$this->input->post('content'),'is_published'=>$p]);
        $this->session->set_flashdata('success','文章已更新'); redirect('admin/news');
    }
    public function delete($id) { $this->News_model->delete($id); $this->session->set_flashdata('success','文章已刪除'); redirect('admin/news'); }
    public function toggle($id) { $this->News_model->toggle($id); redirect('admin/news'); }
}
```

### `src/application/controllers/Admin/Contacts.php`
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contacts extends Admin_Controller {
    public function __construct() { parent::__construct(); $this->load->model('Contact_model'); }
    public function index() { $this->render('admin/contacts/index',['messages'=>$this->Contact_model->get_all()]); }
    public function detail($id) {
        $data['message']=$this->Contact_model->get_by_id($id);
        if (!$data['message']) show_404();
        $this->Contact_model->mark_read($id);
        $this->render('admin/contacts/detail',$data);
    }
    public function delete($id) { $this->Contact_model->delete($id); $this->session->set_flashdata('success','訊息已刪除'); redirect('admin/contacts'); }
}
```

---

## 第十二步：Views 建立說明

### 引入方式（所有頁面，本地端路徑）

```html
<link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/bootstrap-icons.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
```

> 不使用任何 CDN，不引入 Google Fonts，字型使用：
> `font-family: 'Noto Sans TC', '微軟正黑體', sans-serif;`

### 色彩規範

```css
:root {
    --orange:       #FF6B35;
    --orange-light: #FF8C42;
    --orange-pale:  #FFF3E0;
    --dark:         #1A1A2E;
}
```

### 需建立的 View 檔案

**layouts/header.php** — 前台 Navbar（深色 #1A1A2E + 橘色 Logo + 購物車徽章 + 會員狀態）
**layouts/footer.php** — 深色 Footer
**layouts/admin_header.php** — 後台 Sidebar（260px，橘色 active 狀態）
**layouts/admin_footer.php**

**frontend/home/index.php** — Hero Banner（橘色漸層）+ 分類卡片 + 熱銷商品 + 最新消息 + CTA
**frontend/products/index.php** — 分類篩選 + 商品卡片 Grid + 分頁
**frontend/products/detail.php** — 商品詳細（尺寸/顏色按鈕選擇 + AJAX 加入購物車 + Toast 通知）
**frontend/cart/index.php** — 購物車列表 + 數量調整 + 訂單摘要
**frontend/checkout/index.php** — 收件資料表單 + 訂單摘要
**frontend/checkout/success.php** — 訂單成立頁（訂單編號）
**frontend/member/login.php** — 會員登入
**frontend/member/register.php** — 會員註冊
**frontend/member/dashboard.php** — 我的帳戶 + 訂單列表
**frontend/member/order_detail.php** — 訂單詳情
**frontend/news/index.php** — 消息列表 + 分頁
**frontend/news/detail.php** — 消息詳細 + 側欄最近 3 則
**frontend/pages/about.php** — 品牌故事
**frontend/pages/contact.php** — 聯絡表單 + 聯絡資訊

**admin/auth/login.php** — 深色全螢幕 + 橘色點綴登入頁
**admin/dashboard/index.php** — 6 張統計卡片 + 最新訂單 + 未讀訊息
**admin/products/index.php** — 商品 Table（上下架切換、編輯、刪除）
**admin/products/form.php** — 新增/編輯商品（共用，規格動態新增）
**admin/orders/index.php** — 訂單列表（狀態彩色徽章）
**admin/orders/detail.php** — 訂單詳情 + 狀態更新下拉
**admin/news/index.php** — 文章列表（發布切換）
**admin/news/form.php** — 新增/編輯文章（共用）
**admin/contacts/index.php** — 訊息列表（未讀粗體）
**admin/contacts/detail.php** — 訊息詳情 + 回覆 Email 按鈕

---

## 第十三步：.htaccess 設定

### `src/.htaccess`

> Docker 環境中 `src/` 就是 web root（`/var/www/html`），RewriteBase 設為 `/`。

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

---

## 第十四步：匯出 database.sql

資料建立完成後，匯出備份存入 `src/database.sql`，方便日後快速重建：

```bash
docker exec ci3-db mysqldump -u admin -ppassword ci3_fashion > src/database.sql
```

---

## 第十五步：確認完成

```bash
# 確認容器都在跑
docker compose ps

# 確認資料庫
docker exec ci3-db mysql -u admin -ppassword ci3_fashion -e "SELECT COUNT(*) AS 商品數 FROM products;"

# 確認 Bootstrap 本地資源存在
ls src/assets/css/ src/assets/js/ src/assets/fonts/
```

瀏覽器開啟：

```
http://localhost:8080/          ← 前台首頁
http://localhost:8080/admin     ← 後台登入
http://localhost:8081/          ← phpMyAdmin
```
