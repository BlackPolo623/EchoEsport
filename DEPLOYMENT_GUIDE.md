# EchoEsport 完整部署指南

## 目錄
1. [專案概述](#專案概述)
2. [本地開發環境設置](#本地開發環境設置)
3. [資料庫設置](#資料庫設置)
4. [Heroku 部署步驟](#heroku-部署步驟)
5. [金流設定](#金流設定)
6. [測試清單](#測試清單)
7. [常見問題](#常見問題)

---

## 專案概述

EchoEsport 是一個整合的電競服務平台，結合了：
- **形象官網** - 展示品牌、服務、打手介紹
- **會員系統** - 註冊、登入、個人資料管理
- **訂單下單** - 線上下單、付款功能
- **會員中心** - 查詢訂單記錄、個人資料
- **管理後台** - 訂單管理、會員管理、統計報表

### 技術架構
- **前端**: HTML5, CSS3, JavaScript (原生)
- **後端**: PHP 8.0+
- **資料庫**: MySQL 5.7+
- **金流**: 歐買尬 (Funpoint)
- **部署**: Heroku

---

## 本地開發環境設置

### 系統需求
- PHP 8.0 或更高版本
- MySQL 5.7 或更高版本
- Apache 或 Nginx Web Server
- Composer (選用)

### 步驟

#### 1. 安裝 XAMPP 或 WAMP (Windows)
下載並安裝 XAMPP: https://www.apachefriends.org/

#### 2. 複製專案到 Web 目錄
```bash
# XAMPP
複製 E:\htdocs\EchoEsport 到 C:\xampp\htdocs\EchoEsport

# WAMP
複製 E:\htdocs\EchoEsport 到 C:\wamp64\www\EchoEsport
```

#### 3. 啟動服務
- 啟動 Apache
- 啟動 MySQL

#### 4. 訪問網站
瀏覽器開啟: `http://localhost/EchoEsport/`

---

## 資料庫設置

### 1. 建立資料庫

**方法一: 使用 phpMyAdmin**
1. 開啟 phpMyAdmin (http://localhost/phpmyadmin)
2. 點選「新增」建立資料庫
3. 資料庫名稱: `echoesport`
4. 編碼: `utf8mb4_unicode_ci`

**方法二: 使用 MySQL 命令列**
```sql
CREATE DATABASE echoesport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. 匯入資料庫結構

**方法一: phpMyAdmin**
1. 選擇 `echoesport` 資料庫
2. 點選「匯入」
3. 選擇 `database.sql` 檔案
4. 點選「執行」

**方法二: 命令列**
```bash
mysql -u root -p echoesport < database.sql
```

### 3. 驗證資料庫

執行以下 SQL 確認表格已建立：
```sql
USE echoesport;
SHOW TABLES;
```

應該看到以下表格：
- `users` - 會員表
- `admins` - 管理員表
- `orders` - 訂單表
- `transactions` - 交易記錄表
- `activity_logs` - 活動記錄表
- `site_settings` - 網站設定表

### 4. 預設帳號

**管理員帳號**
- 帳號: `admin`
- 密碼: `admin123`
- 登入網址: `http://localhost/EchoEsport/admin/`

---

## Heroku 部署步驟

### 前置準備

1. **安裝 Heroku CLI**
   - 下載: https://devcenter.heroku.com/articles/heroku-cli
   - 安裝後重啟終端機

2. **安裝 Git**
   - 下載: https://git-scm.com/downloads

3. **註冊 Heroku 帳號**
   - 註冊: https://signup.heroku.com/

### 部署步驟

#### 1. 初始化 Git 儲存庫
```bash
cd E:\htdocs\EchoEsport
git init
git add .
git commit -m "Initial commit"
```

#### 2. 登入 Heroku
```bash
heroku login
```

#### 3. 建立 Heroku 應用程式
```bash
heroku create echoesport-tw
# 或讓 Heroku 自動產生名稱
heroku create
```

#### 4. 新增 MySQL 資料庫

**選項一: JawsDB MySQL (推薦)**
```bash
heroku addons:create jawsdb:kitefin
```

**選項二: ClearDB MySQL**
```bash
heroku addons:create cleardb:ignite
```

#### 5. 取得資料庫連線資訊
```bash
# JawsDB
heroku config:get JAWSDB_URL

# ClearDB
heroku config:get CLEARDB_DATABASE_URL
```

#### 6. 匯入資料庫結構

**方法一: 使用 Heroku CLI**
```bash
# 取得資料庫連線資訊
heroku config:get JAWSDB_URL

# 解析 URL (格式: mysql://username:password@host:port/database)
# 然後使用 MySQL 命令匯入
mysql -h [host] -u [username] -p[password] [database] < database.sql
```

**方法二: 使用 MySQL Workbench**
1. 開啟 MySQL Workbench
2. 建立新連線，輸入 Heroku 資料庫資訊
3. 匯入 `database.sql`

#### 7. 部署到 Heroku
```bash
git push heroku main
# 如果你的分支是 master
git push heroku master
```

#### 8. 開啟應用程式
```bash
heroku open
```

### 設定環境變數 (選用)

如果需要設定特定環境變數：
```bash
heroku config:set PHP_TIMEZONE=Asia/Taipei
heroku config:set SESSION_LIFETIME=7200
```

---

## 金流設定

### 歐買尬 (Funpoint) 金流設定

#### 1. 申請金流服務
1. 前往歐買尬官網申請商店代號
2. 取得以下資訊：
   - MerchantID (商店代號)
   - HashKey (金鑰)
   - HashIV (向量)

#### 2. 更新金流設定檔

編輯 `config/payment.php`:

```php
// 正式環境設定
'production' => [
    'MerchantID' => '你的商店代號',
    'HashKey' => '你的HashKey',
    'HashIV' => '你的HashIV',
    // ... 其他設定
]
```

#### 3. 設定金流回調 URL

在歐買尬後台設定以下網址：

**本地測試環境**
- ReturnURL (付款結果通知): `http://localhost/EchoEsport/php/payment_notify.php`
- ClientBackURL (返回商店): `http://localhost/EchoEsport/php/payment_result.php`
- PaymentInfoURL (ATM取號通知): `http://localhost/EchoEsport/php/atm_info.php`

**Heroku 正式環境**
- ReturnURL: `https://你的應用名稱.herokuapp.com/php/payment_notify.php`
- ClientBackURL: `https://你的應用名稱.herokuapp.com/php/payment_result.php`
- PaymentInfoURL: `https://你的應用名稱.herokuapp.com/php/atm_info.php`

#### 4. 測試金流

1. 使用測試信用卡號碼進行測試
2. 確認訂單狀態正確更新
3. 確認交易記錄正確儲存

---

## 測試清單

### 功能測試

#### 前台測試
- [ ] 首頁載入正常
- [ ] 導航欄連結正確
- [ ] 打手介紹頁面顯示正常
- [ ] 價目表頁面顯示正常
- [ ] 台灣服/大陸服切換功能正常
- [ ] 活動頁面顯示正常

#### 會員系統測試
- [ ] 會員註冊功能
  - [ ] 表單驗證正常
  - [ ] 帳號重複檢查
  - [ ] Email 格式驗證
  - [ ] 密碼強度檢查
  - [ ] 註冊成功後資料庫記錄正確

- [ ] 會員登入功能
  - [ ] 正確的帳號密碼可登入
  - [ ] 錯誤的帳號密碼無法登入
  - [ ] Session 正確建立
  - [ ] 登入後導航欄顯示會員名稱

- [ ] 會員中心
  - [ ] 未登入導向登入頁
  - [ ] 儀表板統計資料正確
  - [ ] 訂單列表顯示正確
  - [ ] 個人資料可以編輯
  - [ ] 密碼可以修改

#### 訂單系統測試
- [ ] 下單頁面
  - [ ] 服務類型選擇正常
  - [ ] 伺服器選擇正常
  - [ ] 金額計算正確
  - [ ] 訂單摘要顯示正確

- [ ] 付款功能
  - [ ] 信用卡付款流程
  - [ ] ATM 轉帳流程
  - [ ] 超商付款流程
  - [ ] 付款成功後訂單狀態更新
  - [ ] 付款失敗處理正確

#### 管理後台測試
- [ ] 管理員登入
  - [ ] 正確帳密可登入
  - [ ] 錯誤帳密無法登入

- [ ] 訂單管理
  - [ ] 訂單列表顯示正常
  - [ ] 訂單搜尋功能
  - [ ] 訂單狀態篩選
  - [ ] 訂單詳情查看
  - [ ] 訂單狀態更新

- [ ] 會員管理
  - [ ] 會員列表顯示正常
  - [ ] 會員搜尋功能
  - [ ] 會員狀態篩選
  - [ ] 會員詳情查看
  - [ ] 啟用/停用會員

### 安全性測試
- [ ] SQL 注入防護
- [ ] XSS 攻擊防護
- [ ] CSRF 保護
- [ ] Session 安全
- [ ] 密碼加密儲存

### 效能測試
- [ ] 頁面載入速度
- [ ] 資料庫查詢效能
- [ ] 圖片載入優化
- [ ] CSS/JS 壓縮

### 瀏覽器相容性測試
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] 手機瀏覽器

---

## 常見問題

### Q1: 資料庫連線失敗
**A:** 檢查 `config/database.php` 中的資料庫設定是否正確。

本地環境預設設定：
```php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'echoesport';
```

### Q2: Heroku 部署後無法連接資料庫
**A:** 確認已安裝 MySQL addon，並且環境變數正確設定。

檢查方法：
```bash
heroku config
```

### Q3: 金流回調失敗
**A:**
1. 確認金流後台設定的回調 URL 正確
2. 檢查 `config/payment.php` 的 MerchantID、HashKey、HashIV 是否正確
3. 查看 `data/` 目錄下的日誌檔案

### Q4: 管理後台無法登入
**A:**
1. 確認資料庫中 `admins` 表有資料
2. 預設帳號: admin, 密碼: admin123
3. 如果忘記密碼，執行以下 SQL 重設：

```sql
UPDATE admins
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE username = 'admin';
```
這會將密碼重設為 `admin123`

### Q5: 圖片無法顯示
**A:**
1. 確認 `images/` 和 `video/` 目錄存在
2. 檢查檔案權限 (Linux/Mac 需要設定 755)
3. 確認圖片檔案路徑正確

### Q6: Session 一直失效
**A:**
1. 檢查 PHP session 設定
2. 確認有寫入權限到 session 儲存目錄
3. Heroku 環境下，考慮使用資料庫 session 儲存

### Q7: Heroku 應用程式閒置後喚醒很慢
**A:**
免費方案的 Heroku dyno 會在閒置 30 分鐘後進入休眠。
解決方案：
1. 升級到付費方案
2. 使用外部服務定期 ping 你的網站

### Q8: 付款測試時訂單狀態未更新
**A:**
1. 檢查金流回調 URL 是否可從外部訪問
2. 本地測試需要使用 ngrok 等工具建立公開 URL
3. 查看 `data/` 目錄下的日誌確認是否收到回調

---

## 維護建議

### 定期維護
1. **每週**
   - 檢查錯誤日誌
   - 查看訂單狀態
   - 備份資料庫

2. **每月**
   - 更新 PHP 套件
   - 檢查安全性漏洞
   - 分析網站效能

3. **每季**
   - 完整資料庫備份
   - 檢視活動成效
   - 用戶回饋收集

### 備份策略
```bash
# 資料庫備份
mysqldump -u username -p echoesport > backup_$(date +%Y%m%d).sql

# Heroku 資料庫備份
heroku pg:backups:capture
heroku pg:backups:download
```

---

## 聯絡支援

如有任何問題，請聯繫：
- Email: tech@echoesport.com
- Discord: EchoTech#0001

---

**最後更新**: 2025-01-20
**版本**: 1.0.0
