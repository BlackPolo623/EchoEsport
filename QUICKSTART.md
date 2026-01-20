# EchoEsport 快速開始指南

## 🚀 5 分鐘快速上手

本指南將帶您快速建立並運行 EchoEsport 專案。

---

## 📋 前置需求

確保您的電腦已安裝：
- ✅ PHP 8.0 或更高版本
- ✅ MySQL 5.7 或更高版本
- ✅ Apache 或 Nginx Web Server

**推薦使用 XAMPP** (包含 PHP + MySQL + Apache)
- 下載: https://www.apachefriends.org/

---

## 🎯 步驟一：準備專案

### 1. 確認專案位置
專案已位於：`E:\htdocs\EchoEsport`

### 2. 複製到 Web 伺服器目錄

**XAMPP 用戶:**
```bash
複製整個 EchoEsport 資料夾到 C:\xampp\htdocs\
```

**WAMP 用戶:**
```bash
複製整個 EchoEsport 資料夾到 C:\wamp64\www\
```

---

## 🗄️ 步驟二：建立資料庫

### 方法一：使用 phpMyAdmin（推薦新手）

1. **啟動 XAMPP/WAMP**
   - 啟動 Apache
   - 啟動 MySQL

2. **開啟 phpMyAdmin**
   - 瀏覽器訪問：`http://localhost/phpmyadmin`

3. **建立資料庫**
   - 點選左側的「新增」
   - 資料庫名稱輸入：`echoesport`
   - 編碼選擇：`utf8mb4_unicode_ci`
   - 點選「建立」

4. **匯入資料庫結構**
   - 選擇剛建立的 `echoesport` 資料庫
   - 點選上方的「匯入」標籤
   - 點選「選擇檔案」
   - 選擇 `E:\htdocs\EchoEsport\database.sql`
   - 拉到最下方，點選「執行」
   - 看到「匯入已經成功完成」即表示成功 ✅

### 方法二：使用 MySQL 命令列

```bash
# 1. 建立資料庫
mysql -u root -p
CREATE DATABASE echoesport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# 2. 匯入資料庫結構
mysql -u root -p echoesport < E:\htdocs\EchoEsport\database.sql
```

---

## 🔧 步驟三：配置資料庫連線

### 檢查配置檔案

開啟 `E:\htdocs\EchoEsport\config\database.php`

確認本地環境設定正確：
```php
// 本地開發環境預設值
$host = 'localhost';
$username = 'root';
$password = '';  // XAMPP 預設無密碼
$database = 'echoesport';
$port = 3306;
```

**如果您的 MySQL 有設定密碼**，請修改 `$password` 的值。

---

## 🌐 步驟四：訪問網站

### 1. 確認服務已啟動
- ✅ Apache 正在運行（綠色）
- ✅ MySQL 正在運行（綠色）

### 2. 開啟瀏覽器訪問

**首頁:**
```
http://localhost/EchoEsport/
```

**會員註冊:**
```
http://localhost/EchoEsport/register.php
```

**會員登入:**
```
http://localhost/EchoEsport/login.php
```

**管理後台:**
```
http://localhost/EchoEsport/admin/
```
- 帳號: `admin`
- 密碼: `admin123`

---

## ✅ 步驟五：測試功能

### 1. 測試會員註冊
1. 訪問 `http://localhost/EchoEsport/register.php`
2. 填寫表單：
   - 帳號: `testuser`
   - Email: `test@example.com`
   - 密碼: `test123456`
   - 確認密碼: `test123456`
   - 姓名: `測試用戶`
3. 點選「註冊」
4. 看到成功訊息 ✅

### 2. 測試會員登入
1. 訪問 `http://localhost/EchoEsport/login.php`
2. 輸入剛才註冊的帳號密碼
3. 點選「登入」
4. 成功導向會員中心 ✅

### 3. 測試管理後台
1. 訪問 `http://localhost/EchoEsport/admin/`
2. 帳號: `admin`
3. 密碼: `admin123`
4. 登入後看到儀表板 ✅

---

## 📱 主要功能導覽

### 前台功能
- **首頁** (`index.php`) - 品牌形象展示
- **打手介紹** (`boosters.php`) - 6 位專業打手
- **價目表** (`pricing.php`) - 服務價格，可切換台灣服/大陸服
- **活動優惠** (`events.php`) - 優惠活動資訊

### 會員功能
- **會員中心** (`member/dashboard.php`) - 訂單統計、最近訂單
- **訂單記錄** (`member/orders.php`) - 所有訂單查詢
- **個人資料** (`member/profile.php`) - 資料編輯、修改密碼

### 下單功能
- **立即下單** (`order.php`) - 選擇服務、付款

### 管理後台
- **儀表板** (`admin/index.php`) - 統計報表
- **訂單管理** (`admin/orders.php`) - 管理所有訂單
- **會員管理** (`admin/members.php`) - 管理所有會員

---

## 🎨 自訂設定

### 修改網站名稱和 Logo

1. **Logo 圖片**
   - 替換 `images/Logo.png`
   - 建議尺寸: 200x200 px

2. **網站名稱**
   - 編輯所有頁面的 `<title>` 標籤
   - 編輯導航欄的 Logo 文字

### 修改主色調

編輯 `css/style.css`，找到 `:root` 區塊：
```css
:root {
    --primary-color: rgb(112, 204, 225);  /* 主要顏色 */
    --primary-dark: rgb(70, 160, 185);    /* 深色 */
    --primary-light: rgb(150, 220, 235);  /* 淺色 */
}
```

修改這些顏色值即可改變整個網站的主色調。

### 修改聯絡資訊

編輯每個頁面底部的頁腳區塊：
```html
<div class="footer-section">
    <h4>聯絡我們</h4>
    <ul>
        <li>Email: 您的Email</li>
        <li>Discord: 您的Discord</li>
        <li>Line: 您的Line</li>
    </ul>
</div>
```

---

## 💳 金流設定（選用）

### 如果要測試付款功能

1. **申請歐買尬測試帳號**
   - 前往歐買尬官網申請

2. **修改金流設定**
   - 編輯 `config/payment.php`
   - 填入您的 MerchantID、HashKey、HashIV

3. **設定回調網址**
   - 本地測試需要使用 ngrok 等工具建立公開網址
   - 詳見 `DEPLOYMENT_GUIDE.md`

---

## 🐛 常見問題

### ❌ 無法訪問網站

**問題**: 開啟 `http://localhost/EchoEsport/` 顯示錯誤

**解決方法**:
1. 確認 Apache 已啟動
2. 確認專案位於正確位置（`C:\xampp\htdocs\EchoEsport\`）
3. 檢查 URL 拼寫是否正確

### ❌ 資料庫連線失敗

**問題**: 頁面顯示 "Connection Error"

**解決方法**:
1. 確認 MySQL 已啟動
2. 確認資料庫名稱為 `echoesport`
3. 檢查 `config/database.php` 設定
4. 確認 MySQL 帳號密碼正確

### ❌ 無法登入管理後台

**問題**: 帳號密碼錯誤

**解決方法**:
1. 確認資料庫已正確匯入
2. 預設帳號: `admin`
3. 預設密碼: `admin123`
4. 如果仍無法登入，在 phpMyAdmin 執行：
```sql
UPDATE admins
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE username = 'admin';
```

### ❌ 圖片無法顯示

**問題**: 頁面上的圖片都是 X

**解決方法**:
1. 確認 `images/` 資料夾已複製
2. 確認圖片檔案存在
3. 檢查瀏覽器控制台的錯誤訊息（F12）

### ❌ 台灣服/大陸服切換無效

**問題**: 在價目表點選切換沒有反應

**解決方法**:
1. 確認 `js/pricing-toggle.js` 已載入
2. 開啟瀏覽器控制台（F12）查看 JavaScript 錯誤
3. 清除瀏覽器快取並重新整理

---

## 📚 下一步

✅ 專案已成功運行！接下來您可以：

1. **閱讀完整文檔**
   - `README.md` - 專案總覽
   - `PROJECT_STRUCTURE.md` - 專案結構詳解
   - `DEPLOYMENT_GUIDE.md` - Heroku 部署指南

2. **自訂網站內容**
   - 修改打手介紹資訊
   - 調整價目表價格
   - 更新活動內容

3. **準備上線**
   - 申請金流帳號
   - 購買網域名稱
   - 部署到 Heroku

4. **進階功能**
   - 串接更多付款方式
   - 新增更多服務類型
   - 優化 SEO

---

## 📞 需要幫助？

如果您遇到任何問題：

1. **查看文檔**
   - 先查閱 `DEPLOYMENT_GUIDE.md` 的常見問題章節

2. **檢查日誌**
   - PHP 錯誤日誌：XAMPP 控制台的 Error Log 按鈕
   - Apache 日誌：`C:\xampp\apache\logs\error.log`

3. **聯絡支援**
   - Email: tech@echoesport.com
   - Discord: EchoTech#0001

---

## 🎉 恭喜！

您已經成功建立並運行 EchoEsport 專案！

開始探索並自訂您的電競服務平台吧！

---

**版本**: 1.0.0
**最後更新**: 2025-01-20
**難度**: ⭐⭐☆☆☆ (適合初學者)
