# Railway 快速部署（5分鐘完成）

## 最快速的方法

### 1️⃣ 註冊並建立專案（1分鐘）
```
1. 前往 https://railway.app/
2. 點擊 "Start a New Project"
3. 使用 GitHub 登入
4. 點擊 "Deploy from GitHub repo"
5. 選擇你的 EchoEsport 專案
```

### 2️⃣ 新增資料庫（1分鐘）
```
1. 在專案頁面點擊 "New" → "Database" → "Add MySQL"
2. 等待資料庫啟動完成（綠色勾勾）
```

### 3️⃣ 匯入資料庫（2分鐘）
```
1. 點擊 MySQL 服務 → Variables 分頁
2. 複製連線資訊到 Navicat：
   - Host: MYSQLHOST 的值
   - Port: MYSQLPORT 的值
   - User: MYSQLUSER 的值
   - Password: MYSQLPASSWORD 的值
   - Database: MYSQLDATABASE 的值
3. 連線後執行 database_heroku.sql 檔案
```

### 4️⃣ 取得網址（30秒）
```
1. 點擊你的 PHP 服務
2. Settings → Domains → Generate Domain
3. 複製網址並開啟測試
```

### 5️⃣ 完成！（30秒）
```
測試以下功能：
✅ 首頁載入正常
✅ 會員註冊/登入
✅ 管理後台：你的網址/admin
   帳號：admin
   密碼：admin123
```

---

## 已經完成的設定

✅ Railway 配置檔案（`railway.json`、`nixpacks.toml`）
✅ 資料庫自動偵測（支援 Railway MySQL 環境變數）
✅ Session 設定（Heroku/Railway 相容）
✅ 背景影片問題已修復（已註解）

---

## 如果遇到問題

### 部署失敗
```powershell
# 確認檔案已 commit
git status
git add .
git commit -m "Prepare for Railway"
git push
```

### 資料庫連不上
1. 檢查 MySQL 服務是否啟動（綠色勾勾）
2. 確認已執行 database_heroku.sql
3. 重新部署 PHP 服務

### 頁面顯示錯誤
1. 查看 Deployments 分頁的日誌
2. 確認環境變數正確設定

---

**需要詳細步驟？** 請查看 `RAILWAY_DEPLOYMENT.md`

**祝部署順利！🚀**
