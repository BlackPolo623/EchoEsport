# 迴響電競 Echo Esports

專業的電競陪玩與代練服務平台

## 專案簡介

迴響電競（Echo Esports）是一個提供電競陪玩、代練服務的專業平台。我們致力於為玩家提供高品質的遊戲體驗，包含三角洲行動、CS2、APEX、PUBG等主流射擊遊戲。

### 主要特色

- 專業打手團隊
- 透明的價格體系
- 台灣服與大陸服支援
- 會員管理系統
- 訂單追蹤系統
- 響應式設計

## 功能列表

### 前台功能

- **首頁** (`index.php`) - 展示平台特色與服務
- **打手介紹** (`boosters.php`) - 展示專業打手團隊資訊
- **價目表** (`pricing.php`) - 服務價格一覽，支援台灣服/大陸服切換
- **近期活動** (`events.php`) - 優惠活動與促銷資訊
- **會員註冊** (`register.php`) - 新用戶註冊
- **會員登入** (`login.php`) - 會員登入系統
- **下單系統** (`order.php`) - 線上下單功能

### 會員中心

- **會員儀表板** (`member/dashboard.php`) - 個人資訊總覽
- **訂單管理** (`member/orders.php`) - 查看訂單狀態
- **個人資料** (`member/profile.php`) - 修改個人資訊

### 後台管理

- **管理員登入** (`admin/login.php`)
- **管理儀表板** (`admin/dashboard.php`)
- **訂單管理** (`admin/orders.php`)
- **會員管理** (`admin/users.php`)
- **打手管理** (`admin/boosters.php`)

## 技術架構

### 前端技術

- HTML5
- CSS3 (含自定義動畫)
- JavaScript (ES6+)
- 響應式設計 (RWD)

### 後端技術

- PHP 8.0+
- MySQL / MariaDB
- Session 管理
- PDO 資料庫連接

### 特殊功能

- **價格切換系統** - iOS 風格的台灣服/大陸服切換開關
- **LocalStorage** - 記憶用戶偏好設定
- **平滑動畫** - 頁面切換與互動動畫
- **載入畫面** - 提升用戶體驗

## 安裝步驟

### 本地開發環境

1. **環境需求**
   - PHP 8.0 或以上
   - MySQL 5.7 或以上 / MariaDB 10.3 或以上
   - Apache 2.4 或 Nginx
   - Composer (選用)

2. **克隆專案**
   ```bash
   git clone https://github.com/yourusername/echoesport.git
   cd echoesport
   ```

3. **資料庫設定**
   - 建立資料庫
   ```sql
   CREATE DATABASE echoesport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
   - 匯入資料庫結構（如果有提供 SQL 文件）
   ```bash
   mysql -u root -p echoesport < database.sql
   ```

4. **配置文件**
   - 複製 `config/config.example.php` 到 `config/config.php`
   - 修改資料庫連接資訊
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'echoesport');
   define('DB_USER', 'root');
   define('DB_PASS', 'your_password');
   ```

5. **啟動開發伺服器**
   ```bash
   php -S localhost:8000
   ```

6. **訪問網站**
   - 打開瀏覽器訪問 `http://localhost:8000`

### Railway 部署 ⭐ 推薦

Railway.app 提供簡單快速的部署體驗，每月 $5 免費額度，適合這個專案使用。

**快速部署（5分鐘）**

1. **註冊並建立專案**
   - 前往 [Railway.app](https://railway.app/)
   - 使用 GitHub 登入
   - Deploy from GitHub repo → 選擇此專案

2. **新增 MySQL 資料庫**
   - 點擊 "New" → "Database" → "Add MySQL"
   - 等待資料庫啟動完成

3. **匯入資料庫結構**
   - 在 MySQL 服務的 Variables 分頁取得連線資訊
   - 使用 Navicat 連線並執行 `database_heroku.sql`

4. **取得網站網址**
   - Settings → Domains → Generate Domain
   - 開啟網址測試

**詳細步驟：** 請參閱 `RAILWAY_DEPLOYMENT.md` 或 `QUICK_START_RAILWAY.md`

---

### Heroku 部署（替代方案）

如果偏好使用 Heroku，請參考以下步驟：

1. **安裝 Heroku CLI**
   ```bash
   heroku login
   ```

2. **建立應用並添加資料庫**
   ```bash
   heroku create your-app-name
   heroku addons:create jawsdb:kitefin
   ```

3. **部署**
   ```bash
   git add .
   git commit -m "Deploy to Heroku"
   git push heroku main
   ```

**注意：** Heroku 專用檔案已重新命名為 `Procfile.heroku` 和 `.htaccess.heroku`，如需使用請先重新命名。

## 資料庫設定

### 主要資料表

- `users` - 會員資料表
- `orders` - 訂單資料表
- `boosters` - 打手資料表
- `admin_users` - 管理員資料表
- `transactions` - 交易記錄表
- `services` - 服務項目表

### 資料庫連接配置

在 `config/config.php` 中設定：

```php
<?php
// 資料庫配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'echoesport');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// 站點配置
define('SITE_URL', 'http://localhost:8000');
define('SITE_NAME', '迴響電競');

// Session 配置
define('SESSION_LIFETIME', 3600); // 1小時
```

## 目錄結構

```
echoesport/
├── admin/              # 後台管理
│   ├── dashboard.php
│   ├── orders.php
│   ├── users.php
│   └── boosters.php
├── config/             # 配置文件
│   └── config.php
├── css/                # 樣式文件
│   ├── style.css
│   └── pricing-toggle.css
├── images/             # 圖片資源
├── js/                 # JavaScript 文件
│   ├── main.js
│   └── pricing-toggle.js
├── member/             # 會員中心
│   ├── dashboard.php
│   ├── orders.php
│   └── profile.php
├── php/                # PHP 後端邏輯
│   ├── api/
│   └── includes/
├── video/              # 視頻資源
├── .htaccess           # Apache 配置
├── composer.json       # Composer 配置
├── Procfile            # Heroku 配置
├── index.php           # 首頁
├── boosters.php        # 打手介紹
├── pricing.php         # 價目表
├── events.php          # 近期活動
├── login.php           # 登入頁面
├── register.php        # 註冊頁面
├── order.php           # 下單頁面
└── README.md           # 說明文件
```

## 重要提醒

### 安全性

1. **修改預設密碼** - 部署前務必修改所有預設密碼
2. **啟用 HTTPS** - 生產環境必須使用 HTTPS
3. **資料庫安全** - 不要將資料庫憑證提交到版本控制
4. **輸入驗證** - 所有用戶輸入都需要驗證和過濾
5. **SQL 注入防護** - 使用 PDO 預處理語句
6. **XSS 防護** - 使用 `htmlspecialchars()` 處理輸出

### 性能優化

1. **啟用快取** - 使用 `.htaccess` 設定瀏覽器快取
2. **圖片優化** - 壓縮圖片檔案大小
3. **JavaScript/CSS 壓縮** - 使用工具壓縮 JS 和 CSS
4. **CDN 使用** - 靜態資源可考慮使用 CDN

### 瀏覽器支援

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- 移動端瀏覽器

## 開發團隊

- **開發者** - Your Name
- **設計師** - Designer Name
- **專案經理** - Manager Name

## 版本歷史

- **v1.0.0** (2025-01-20)
  - 初始版本發布
  - 基本功能完成
  - 台灣服/大陸服切換功能

## 授權

本專案為私有專案，所有權利保留。

## 聯絡方式

- **Email** - contact@echoesport.com
- **Discord** - EchoEsport#0001
- **Line** - @echoesport

## 常見問題

### Q: 如何修改價格？
A: 在 `pricing.php` 中直接修改價格數值。

### Q: 如何新增打手？
A: 在後台管理系統的「打手管理」中新增。

### Q: 如何自定義主題顏色？
A: 修改 `css/style.css` 中的 CSS 變數。

### Q: 如何備份資料庫？
A: 使用 `mysqldump` 命令或 phpMyAdmin 進行備份。

```bash
mysqldump -u root -p echoesport > backup.sql
```

## 技術支援

如遇到技術問題，請提交 Issue 或聯繫開發團隊。

---

© 2025 迴響電競 Echo Esports. All rights reserved.
