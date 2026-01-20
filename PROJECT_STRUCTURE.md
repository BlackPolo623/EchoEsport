# EchoEsport 專案結構說明

## 完整目錄樹狀圖

```
E:\htdocs\EchoEsport\
│
├── index.php                    # 首頁（形象官網）
├── login.php                    # 會員登入頁面
├── register.php                 # 會員註冊頁面
├── order.php                    # 下單頁面
├── boosters.php                 # 打手介紹頁面
├── pricing.php                  # 價目表頁面
├── events.php                   # 活動優惠頁面
│
├── database.sql                 # 資料庫結構檔案
├── Procfile                     # Heroku 部署配置
├── composer.json                # PHP 依賴配置
├── .htaccess                    # Apache 配置
├── README.md                    # 專案說明文件
├── DEPLOYMENT_GUIDE.md          # 完整部署指南
└── PROJECT_STRUCTURE.md         # 本文件
│
├── config/                      # 配置檔案目錄
│   ├── database.php            # 資料庫連線配置
│   └── payment.php             # 金流設定檔
│
├── css/                         # 樣式表目錄
│   ├── style.css               # 主要樣式表
│   └── pricing-toggle.css      # 價格切換樣式
│
├── js/                          # JavaScript 目錄
│   ├── main.js                 # 主要 JavaScript
│   └── pricing-toggle.js       # 價格切換邏輯
│
├── images/                      # 圖片資源目錄
│   ├── Logo.png                # 品牌 Logo
│   ├── booster1.jpg            # 打手頭像 1
│   ├── booster2.jpg            # 打手頭像 2
│   ├── booster3.jpg            # 打手頭像 3
│   ├── booster4.jpg            # 打手頭像 4
│   ├── booster5.jpg            # 打手頭像 5
│   ├── booster6.jpg            # 打手頭像 6
│   ├── discount1.jpg           # 折扣活動圖 1
│   ├── discount2.jpg           # 折扣活動圖 2
│   ├── discount3.jpg           # 折扣活動圖 3
│   ├── giveaway1.jpg           # 大放送活動圖 1
│   ├── giveaway2.jpg           # 大放送活動圖 2
│   └── giveaway3.jpg           # 大放送活動圖 3
│
├── video/                       # 影片資源目錄
│   └── hero-video.mp4          # 首頁背景影片
│
├── member/                      # 會員中心目錄
│   ├── dashboard.php           # 會員儀表板
│   ├── orders.php              # 訂單記錄頁面
│   └── profile.php             # 個人資料頁面
│
├── admin/                       # 管理後台目錄
│   ├── index.php               # 後台首頁（登入+儀表板）
│   ├── orders.php              # 訂單管理頁面
│   ├── members.php             # 會員管理頁面
│   └── api.php                 # 後台 API
│
├── php/                         # PHP 後端目錄
│   ├── payment_notify.php      # 金流回調處理
│   ├── payment_result.php      # 付款結果頁面
│   ├── atm_info.php            # ATM 虛擬帳號資訊
│   │
│   └── api/                    # API 端點目錄
│       ├── auth.php            # 會員認證 API
│       ├── order.php           # 訂單 API
│       └── profile.php         # 個人資料 API
│
└── data/                        # 資料存儲目錄（自動建立）
    ├── logs/                   # 日誌檔案目錄
    └── uploads/                # 上傳檔案目錄
```

---

## 主要檔案說明

### 前台頁面

#### `index.php` - 首頁
- **用途**: 形象官網首頁
- **功能**:
  - 英雄區域（背景影片）
  - 特色服務展示
  - 為什麼選擇我們
  - 導航欄（會員狀態顯示）
  - 頁腳資訊

#### `login.php` - 登入頁面
- **用途**: 會員登入
- **功能**:
  - 帳號/密碼登入表單
  - AJAX 提交驗證
  - 記住我功能
  - 導向會員中心

#### `register.php` - 註冊頁面
- **用途**: 會員註冊
- **功能**:
  - 註冊表單（帳號、Email、密碼等）
  - 前端表單驗證
  - AJAX 提交
  - 註冊成功導向登入頁

#### `order.php` - 下單頁面
- **用途**: 線上下單
- **功能**:
  - 服務類型選擇
  - 伺服器選擇（台灣/大陸）
  - 金額輸入
  - 付款方式選擇
  - 整合金流系統

#### `boosters.php` - 打手介紹
- **用途**: 展示專業打手資訊
- **功能**:
  - 6 位打手卡片展示
  - KD 比、場次、專長等資訊
  - 預約打手按鈕

#### `pricing.php` - 價目表
- **用途**: 服務價格展示
- **功能**:
  - 台灣服/大陸服切換
  - 多種服務價格卡片
  - 常見問題 FAQ
  - LocalStorage 記憶選擇

#### `events.php` - 活動優惠
- **用途**: 展示優惠活動
- **功能**:
  - 折扣活動卡片
  - 大放送活動卡片
  - 活動須知說明

---

### 會員中心

#### `member/dashboard.php` - 會員儀表板
- **用途**: 會員首頁
- **功能**:
  - 統計資訊卡片
  - 最近訂單列表
  - 快速連結

#### `member/orders.php` - 訂單記錄
- **用途**: 查看所有訂單
- **功能**:
  - 訂單列表（分頁）
  - 訂單狀態篩選
  - 訂單詳情查看

#### `member/profile.php` - 個人資料
- **用途**: 編輯個人資料
- **功能**:
  - 基本資料編輯
  - 修改密碼
  - Email 變更

---

### 管理後台

#### `admin/index.php` - 後台首頁
- **用途**: 管理員登入和儀表板
- **功能**:
  - 登入頁面
  - 統計報表
  - 最近訂單列表
  - Session 驗證

#### `admin/orders.php` - 訂單管理
- **用途**: 管理所有訂單
- **功能**:
  - 訂單列表（分頁）
  - 搜尋訂單
  - 狀態篩選
  - 更新訂單狀態
  - 訂單詳情查看

#### `admin/members.php` - 會員管理
- **用途**: 管理所有會員
- **功能**:
  - 會員列表（分頁）
  - 搜尋會員
  - 啟用/停用會員
  - 查看會員訂單記錄

#### `admin/api.php` - 後台 API
- **用途**: 後台操作 API
- **功能**:
  - 訂單操作
  - 會員操作
  - 統計資料取得
  - 搜尋功能

---

### API 端點

#### `php/api/auth.php` - 認證 API
- **功能**:
  - `register` - 會員註冊
  - `login` - 會員登入
  - `logout` - 登出
  - `check_session` - 檢查登入狀態

#### `php/api/order.php` - 訂單 API
- **功能**:
  - `create_order` - 建立訂單
  - `get_order` - 取得訂單資訊
  - `update_order` - 更新訂單

#### `php/api/profile.php` - 個人資料 API
- **功能**:
  - `update_profile` - 更新個人資料
  - `change_password` - 修改密碼

---

### 付款相關

#### `php/payment_notify.php` - 金流回調
- **用途**: 接收金流平台通知
- **功能**:
  - 驗證 CheckMacValue
  - 更新訂單狀態
  - 記錄交易資訊
  - 回應金流平台

#### `php/payment_result.php` - 付款結果
- **用途**: 顯示付款結果
- **功能**:
  - 成功/失敗訊息顯示
  - 訂單資訊展示
  - 自動跳轉

#### `php/atm_info.php` - ATM 資訊
- **用途**: ATM 虛擬帳號顯示
- **功能**:
  - 顯示銀行代碼
  - 顯示虛擬帳號
  - 顯示繳費期限
  - 複製功能

---

### 配置檔案

#### `config/database.php` - 資料庫配置
- **功能**:
  - 本地/Heroku 環境自動偵測
  - PDO 連線建立
  - 錯誤處理

#### `config/payment.php` - 金流配置
- **功能**:
  - 歐買尬金流設定
  - CheckMacValue 產生/驗證
  - 測試/正式環境切換

---

### 前端資源

#### `css/style.css` - 主樣式表
- **功能**:
  - 全域樣式定義
  - CSS 變數配置
  - 響應式設計
  - 動畫效果
  - 表單樣式
  - 會員中心樣式

#### `css/pricing-toggle.css` - 切換樣式
- **功能**:
  - iOS 風格 toggle 開關
  - 伺服器標籤樣式
  - 動畫效果

#### `js/main.js` - 主要邏輯
- **功能**:
  - 載入畫面控制
  - 導航欄滾動效果
  - 表單驗證
  - 工具函數
  - AJAX 請求處理

#### `js/pricing-toggle.js` - 切換邏輯
- **功能**:
  - 台灣服/大陸服切換
  - LocalStorage 記憶
  - 平滑動畫
  - URL 參數支援

---

## 資料庫結構

### `users` - 會員表
```sql
- id (主鍵)
- username (帳號)
- email (Email)
- password (密碼，加密)
- full_name (姓名)
- phone (電話)
- discord_id (Discord ID)
- line_id (Line ID)
- created_at (註冊時間)
- last_login (最後登入)
- is_active (啟用狀態)
```

### `admins` - 管理員表
```sql
- id (主鍵)
- username (帳號)
- password (密碼，加密)
- email (Email)
- role (角色：super_admin/admin/staff)
- created_at (建立時間)
- last_login (最後登入)
- is_active (啟用狀態)
```

### `orders` - 訂單表
```sql
- id (主鍵)
- order_number (訂單編號)
- user_id (會員ID，可為空)
- guest_email (訪客Email，可為空)
- service_type (服務類型)
- server_type (伺服器類型)
- amount (金額)
- payment_method (付款方式)
- payment_status (付款狀態)
- order_status (訂單狀態)
- trade_no (金流交易編號)
- bank_code (銀行代碼)
- virtual_account (虛擬帳號)
- payment_date (付款時間)
- expire_date (到期日)
- description (訂單描述)
- customer_notes (客戶備註)
- admin_notes (管理員備註)
- created_at (建立時間)
- updated_at (更新時間)
```

### `transactions` - 交易記錄表
```sql
- id (主鍵)
- order_id (訂單ID)
- transaction_id (交易ID)
- trade_no (金流編號)
- amount (金額)
- payment_type (付款類型)
- payment_date (付款時間)
- status (狀態)
- rtn_code (回傳碼)
- rtn_msg (回傳訊息)
- raw_data (原始資料)
- created_at (建立時間)
```

### `activity_logs` - 活動記錄表
```sql
- id (主鍵)
- user_type (用戶類型)
- user_id (用戶ID)
- action (動作)
- description (描述)
- ip_address (IP 位址)
- user_agent (瀏覽器資訊)
- created_at (建立時間)
```

### `site_settings` - 網站設定表
```sql
- id (主鍵)
- setting_key (設定鍵)
- setting_value (設定值)
- setting_type (設定類型)
- description (描述)
- updated_at (更新時間)
```

---

## 工作流程

### 會員註冊流程
```
1. 訪客進入 register.php
2. 填寫註冊表單
3. 前端 JavaScript 驗證
4. AJAX 提交到 php/api/auth.php?action=register
5. 後端驗證（帳號重複、Email 格式等）
6. 密碼加密儲存
7. 寫入 users 表
8. 記錄 activity_logs
9. 返回成功訊息
10. 導向 login.php
```

### 會員登入流程
```
1. 訪客進入 login.php
2. 輸入帳號密碼
3. AJAX 提交到 php/api/auth.php?action=login
4. 後端驗證密碼
5. 建立 Session
6. 更新 last_login
7. 記錄 activity_logs
8. 返回用戶資訊
9. 導向 member/dashboard.php
```

### 下單付款流程
```
1. 用戶進入 order.php
2. 選擇服務、伺服器、金額、付款方式
3. 提交表單到 php/api/order.php
4. 建立訂單記錄（status: pending）
5. 產生訂單編號
6. 計算 CheckMacValue
7. 重定向到歐買尬金流
8. 用戶完成付款
9. 金流回調 php/payment_notify.php
10. 驗證 CheckMacValue
11. 更新訂單狀態（status: paid）
12. 寫入 transactions 表
13. 記錄 activity_logs
14. 導向 php/payment_result.php
15. 顯示付款成功
```

### 管理員管理訂單流程
```
1. 管理員登入 admin/index.php
2. 進入 admin/orders.php
3. 查看訂單列表
4. 點選訂單詳情
5. 更新訂單狀態（處理中/已完成）
6. AJAX 提交到 admin/api.php?action=update_order_status
7. 後端驗證權限
8. 更新訂單狀態
9. 記錄 activity_logs
10. 返回成功訊息
```

---

## 安全性措施

### 1. SQL 注入防護
- 所有資料庫查詢使用 PDO 預處理語句
- 參數綁定，不直接拼接 SQL

### 2. XSS 防護
- 所有輸出使用 `htmlspecialchars()` 編碼
- 表單輸入過濾

### 3. CSRF 防護
- Session token 驗證
- 重要操作需要二次確認

### 4. 密碼安全
- 使用 `password_hash()` 加密
- 使用 `password_verify()` 驗證
- 不儲存明文密碼

### 5. Session 安全
- HttpOnly cookie
- Secure cookie (HTTPS)
- Session timeout

### 6. 權限控制
- 會員中心需要登入驗證
- 管理後台需要管理員權限
- API 操作需要權限檢查

---

## 效能優化

### 1. 資料庫優化
- 適當的索引設定
- 查詢結果快取
- 連線池管理

### 2. 前端優化
- CSS/JS 壓縮
- 圖片延遲載入
- 瀏覽器快取

### 3. 程式碼優化
- 減少資料庫查詢
- 避免 N+1 查詢
- 使用適當的資料結構

---

## 維護指南

### 定期檢查項目
1. 錯誤日誌
2. 資料庫大小
3. 磁碟空間
4. 伺服器效能

### 備份策略
1. 每日資料庫備份
2. 每週完整備份
3. 備份檔案異地儲存

### 更新策略
1. 定期更新 PHP 版本
2. 定期更新依賴套件
3. 定期檢查安全性漏洞

---

**文件版本**: 1.0.0
**最後更新**: 2025-01-20
**維護者**: EchoEsport 開發團隊
