# CI3 Fashion — Docker 開發環境

時尚服裝電商 Demo 專案，使用 CodeIgniter 3 建構，透過 Docker 一鍵啟動，無需在本機安裝 PHP、Apache 或 MySQL。

---

## 環境需求

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Git

---

## 快速啟動

### 1. Clone 專案

```bash
git clone <repo-url>
cd ci3_demo
```

### 2. 啟動 Docker 容器

```bash
docker compose up -d --build
```

> 首次執行會 build image，約需 1–2 分鐘。之後重啟直接用 `docker compose up -d`。

### 3. 匯入資料庫（首次需執行一次）

```bash
# Linux
docker exec -i ci3-db mysql -u admin -ppassword < src/database.sql
```
```bash
# Windows
# 第一步：把 sql 檔案複製進容器
docker cp src/database.sql ci3-db:/tmp/database.sql

# 第二步：在容器內直接執行（完全不經過 PowerShell 編碼轉換）
docker exec -i ci3-db mysql -u admin -ppassword --default-character-set=utf8mb4 ci3_fashion -e "source /tmp/database.sql"
```

### 4. 開啟瀏覽器

| 服務 | 網址 |
|------|------|
| 網站前台 | http://localhost:8080 |
| 後台管理 | http://localhost:8080/admin/login |
| phpMyAdmin | http://localhost:8081 |

完成！

---

## 帳號資訊

| 類型 | 帳號 | 密碼 |
|------|------|------|
| 後台管理員 | `admin` | `password` |
| 前台測試會員 | `test@example.com` | `password` |
| MySQL | `admin` | `password` |

---

## 常用指令

```bash
# 啟動
docker compose up -d

# 停止
docker compose down

# 查看 log
docker compose logs -f web

# 重新 build（修改 Dockerfile 後才需要）
docker compose up -d --build
```

---

## 目錄結構

```
ci3_demo/
├── docker-compose.yml   ← 服務定義（web / db / phpmyadmin）
├── Dockerfile           ← PHP 8.2 + Apache 映像設定
├── README.md            ← 本文件
└── src/                 ← CI3 原始碼（Windows 上直接編輯此目錄）
    ├── application/     ← Controllers / Models / Views / Config
    ├── assets/          ← Bootstrap 5、Bootstrap Icons（本地端）
    ├── database.sql     ← 資料庫結構與假資料
    └── index.php        ← CI3 入口點
```

> `src/` 透過 Volume 掛載到容器內，在 Windows 上編輯檔案後，瀏覽器重新整理即可看到變更，**無需重啟容器**。

---

## 服務說明

| 容器 | Image | 對外 Port |
|------|-------|-----------|
| ci3-web | php:8.2-apache（自建） | 8080 |
| ci3-db | mysql:8.0 | 3306 |
| ci3-pma | phpmyadmin/phpmyadmin | 8081 |

資料庫資料存放於 Docker Volume `ci3_db_data`，`docker compose down` 不會清除資料。
若要完全重置資料庫：

```bash
docker compose down -v
```

---

## 常見問題

| 問題 | 解法 |
|------|------|
| 網站顯示 404 | 確認容器都在執行：`docker compose ps` |
| 樣式破版 | 重新整理（Ctrl+Shift+R），清除瀏覽器快取 |
| 資料庫連線失敗 | 等待 ci3-db 完全啟動（約 10 秒），再重試 |
| 容器名稱衝突 | 先執行 `docker compose down`，再重新啟動 |
| 資料庫是空的 | 重新執行步驟 3 的 `docker exec` 匯入指令 |
