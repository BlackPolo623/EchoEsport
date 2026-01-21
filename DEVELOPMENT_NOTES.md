# EchoEsport 開發注意事項

## 🎉 部署成功！

你的網站已經成功部署到 Railway！

**網站網址：** `echoesport-production.up.railway.app`

---

## 📋 當前狀態

### ✅ 已完成
- Railway 部署設定完成
- MySQL 資料庫建立並匯入
- 網站正常運作
- 所有頁面可以訪問

### ⚠️ 需要注意的事項

#### 1. 預設管理員密碼（重要！）
```
網址：echoesport-production.up.railway.app/admin
帳號：admin
密碼：admin123
```

**⚠️ 強烈建議立即修改預設密碼！**

修改方式：
1. 登入管理後台
2. 前往個人資料或設定頁面
3. 修改密碼
4. 或直接在資料庫中更新 `admins` 表

#### 2. 背景影片問題
- 原本的背景影片（hero-video.mp4）已被註解
- 改用純色漸層背景
- 如需恢復影片，請：
  1. 確保影片檔案不會太大（建議 < 5MB）
  2. 取消 `index.php` 第 39-44 行的註解
  3. 移除第 45 行的臨時背景樣式

#### 3. Funpoint 付款設定
- 付款功能已整合但需設定
- 設定檔：`config/payment.php`
- 需要設定：
  - 商店 ID（MerchantID）
  - HashKey
  - HashIV
  - 回傳網址（ReturnURL）
  - 通知網址（NotifyURL）

---

## 🚀 部署流程

### 本地開發 → Railway 部署

1. **本地修改程式碼**
   ```powershell
   # 編輯檔案...
   ```

2. **提交到 Git**
   ```powershell
   git add .
   git commit -m "描述你的變更"
   git push origin main
   ```

3. **自動部署**
   - Railway 會自動偵測 GitHub 的變更
   - 自動重新部署
   - 約 1-2 分鐘完成

4. **檢查部署狀態**
   - 前往 Railway Dashboard
   - 查看 Deployments 分頁
   - 確認部署成功（綠色 ✅）

---

## 🔧 技術架構

### 前端
- HTML5 + CSS3
- Vanilla JavaScript（無框架）
- 響應式設計

### 後端
- PHP 8.3（FrankenPHP）
- MySQL 資料庫
- Session 管理
- PDO 資料庫連接

### 部署平台
- Railway.app
- FrankenPHP + Caddy
- 自動 HTTPS（需設定自訂網域）

---

## 📁 重要檔案說明

### 配置檔案
- `Caddyfile` - Web 伺服器配置
- `config/database.php` - 資料庫連線（自動偵測 Railway 環境）
- `config/session.php` - Session 設定
- `config/payment.php` - 付款設定

### 資料庫
- `database_heroku.sql` - Railway 使用的資料庫結構（6個表）
- `database.sql` - 本地開發用（含 CREATE DATABASE）

### 部署相關
- `.railwayignore` - Railway 部署時忽略的檔案
- `Procfile.heroku` - Heroku 備份（已不使用）
- `composer.json.backup` - Composer 備份（已不使用）

---

## 💾 資料庫資訊

### Railway MySQL 連線資訊
在 Railway Dashboard → MySQL Service → Variables 中查看：
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLUSER`
- `MYSQLPASSWORD`
- `MYSQLDATABASE`

### 使用 Navicat 連線
使用 **Public URL** 而非內部 URL：
- 從 `MYSQL_URL` 取得連線資訊
- 格式：`mysql://user:password@host:port/database`

### 資料表
1. `users` - 會員資料
2. `admins` - 管理員
3. `orders` - 訂單
4. `transactions` - 交易記錄
5. `activity_logs` - 活動日誌
6. `site_settings` - 網站設定

---

## 🔐 安全性建議

### 立即執行
1. ✅ 修改預設管理員密碼
2. ✅ 檢查資料庫連線資訊未外洩
3. ✅ 確認 `.env` 檔案（如有）未提交到 Git

### 建議執行
1. 為網站設定自訂網域
2. 啟用 Railway 的自動 HTTPS
3. 設定備份策略
4. 監控資料庫使用量

---

## 💰 Railway 成本

### 免費額度
- 每月 $5 免費額度
- 本專案預估：$3-4/月
- 完全在免費額度內 ✅

### 監控使用量
1. Railway Dashboard → Usage
2. 查看當月使用量
3. 接近額度時會收到通知

---

## 🐛 常見問題

### Q1: 網站顯示 502 錯誤
**解決方法：**
1. 檢查 Railway Deployments 日誌
2. 確認 PORT 環境變數設為 8000
3. 檢查 Caddyfile 配置是否正確

### Q2: 資料庫連不上
**解決方法：**
1. 確認 MySQL 服務正在運作（綠色勾勾）
2. 檢查環境變數是否正確
3. 重新部署應用程式

### Q3: 修改程式碼後沒有更新
**解決方法：**
1. 確認已 push 到 GitHub
2. 檢查 Railway 是否自動部署
3. 手動觸發重新部署

### Q4: Session 無法使用
**解決方法：**
- Session 已自動設定為使用 /tmp
- 無需額外配置

---

## 📝 後續開發建議

### 短期目標
1. 修改預設管理員密碼
2. 設定 Funpoint 付款
3. 上傳真實的打手照片和資料
4. 測試完整的訂單流程
5. 調整價格和服務項目

### 中期目標
1. 新增自訂網域
2. 設定 Email 通知功能
3. 完善會員中心功能
4. 新增訂單狀態追蹤
5. 優化 SEO

### 長期目標
1. 新增更多遊戲項目
2. 建立客服系統
3. 整合更多付款方式
4. 建立推薦系統
5. 手機 APP（可選）

---

## 📞 技術支援

### Railway
- 官方文件：https://docs.railway.app/
- Discord 社群：https://discord.gg/railway

### 專案問題
- GitHub Issues：https://github.com/BlackPolo623/EchoEsport/issues
- 查看部署文件：`RAILWAY_DEPLOYMENT.md`

---

## ✨ 下一步

現在你可以：

1. **開始開發新功能**
   - 修改現有頁面
   - 新增新功能
   - 調整設計

2. **設定付款功能**
   - 申請 Funpoint 商店帳號
   - 設定付款參數
   - 測試付款流程

3. **填充內容**
   - 上傳真實打手資料
   - 新增遊戲項目
   - 撰寫活動訊息

4. **優化網站**
   - 壓縮圖片
   - 優化載入速度
   - 改善 SEO

---

**祝開發順利！🚀**

最後更新：2025-01-21
