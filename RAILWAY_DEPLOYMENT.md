# Railway.app 部署指南

## 為什麼選擇 Railway？

- ✅ 每月 $5 免費額度（足夠小型專案使用）
- ✅ 內建 MySQL 資料庫服務
- ✅ 自動偵測 PHP 環境
- ✅ 部署超簡單（類似 Heroku）
- ✅ 不會自動休眠
- ✅ 速度快，介面現代化

---

## 步驟一：註冊 Railway 帳號

1. 前往 [Railway.app](https://railway.app/)
2. 點擊 **"Start a New Project"** 或 **"Login"**
3. 使用 GitHub 帳號登入（推薦）

---

## 步驟二：建立新專案

### 方法一：從 GitHub 部署（推薦）

1. 在 Railway Dashboard 點擊 **"New Project"**
2. 選擇 **"Deploy from GitHub repo"**
3. 授權 Railway 存取你的 GitHub
4. 選擇 `EchoEsport` 專案
5. Railway 會自動開始部署

### 方法二：從本地 Git 部署

1. 確保專案已經是 Git Repository：
```powershell
# 如果還沒有初始化 Git
git init
git add .
git commit -m "Initial commit for Railway deployment"
```

2. 安裝 Railway CLI：
```powershell
npm install -g @railway/cli
```

3. 登入 Railway：
```powershell
railway login
```

4. 初始化專案：
```powershell
railway init
```

5. 部署：
```powershell
railway up
```

---

## 步驟三：新增 MySQL 資料庫

1. 在你的 Railway 專案中，點擊 **"New"** → **"Database"** → **"Add MySQL"**
2. Railway 會自動建立 MySQL 資料庫實例
3. 等待資料庫啟動完成（約 30-60 秒）

---

## 步驟四：匯入資料庫結構

### 方法一：使用 Railway CLI

1. 連接到 Railway MySQL：
```powershell
railway connect MySQL
```

2. 匯入資料庫結構：
```sql
source database_heroku.sql
```

3. 離開 MySQL：
```sql
exit
```

### 方法二：使用 MySQL 客戶端（Navicat / MySQL Workbench）

1. 在 Railway Dashboard 點擊 **MySQL 服務**
2. 切換到 **"Variables"** 分頁
3. 複製以下連線資訊：
   - `MYSQLHOST`（主機）
   - `MYSQLPORT`（通常是 3306）
   - `MYSQLUSER`（使用者）
   - `MYSQLPASSWORD`（密碼）
   - `MYSQLDATABASE`（資料庫名稱）

4. 在 Navicat 建立新連線：
   - 主機：貼上 `MYSQLHOST`
   - 連接埠：貼上 `MYSQLPORT`
   - 使用者名稱：貼上 `MYSQLUSER`
   - 密碼：貼上 `MYSQLPASSWORD`
   - 資料庫：貼上 `MYSQLDATABASE`

5. 連線成功後，執行 `database_heroku.sql` 檔案

---

## 步驟五：設定環境變數（可選）

Railway 的 MySQL 會自動設定以下環境變數：
- `MYSQL_URL`（完整連線 URL）
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLUSER`
- `MYSQLPASSWORD`
- `MYSQLDATABASE`

我們的 `config/database.php` 已經自動支援這些變數，無需額外設定！

如果需要新增其他環境變數：
1. 點擊你的服務
2. 切換到 **"Variables"** 分頁
3. 點擊 **"New Variable"**
4. 輸入變數名稱和值

---

## 步驟六：檢查部署狀態

1. 在 Railway Dashboard 點擊你的服務
2. 切換到 **"Deployments"** 分頁
3. 查看部署日誌，確保沒有錯誤
4. 等待狀態變成 **"Success"**（綠色勾勾）

---

## 步驟七：取得網站網址

1. 在服務頁面，切換到 **"Settings"** 分頁
2. 找到 **"Domains"** 區塊
3. 點擊 **"Generate Domain"**
4. Railway 會自動產生一個網址（例如：`echoesport-production.up.railway.app`）
5. 點擊網址測試網站

---

## 步驟八：測試網站功能

### 1. 測試首頁
- 前往你的 Railway 網址
- 確認頁面正常載入（沒有藍屏或載入圈圈）

### 2. 測試會員註冊
- 點擊「註冊」
- 填寫資料並送出
- 確認可以成功註冊

### 3. 測試會員登入
- 使用剛註冊的帳號登入
- 確認可以進入會員中心

### 4. 測試管理後台
- 前往 `你的網址/admin`
- 帳號：`admin`
- 密碼：`admin123`
- 確認可以登入後台

---

## 常見問題

### Q1: 部署失敗怎麼辦？
1. 查看 Deployments 分頁的錯誤日誌
2. 確認 `nixpacks.toml` 和 `railway.json` 檔案存在
3. 確認所有檔案都已經 commit 到 Git

### Q2: 資料庫連不上？
1. 確認 MySQL 服務已經啟動（綠色勾勾）
2. 檢查 Variables 分頁是否有 MySQL 環境變數
3. 重新部署應用程式

### Q3: 網站顯示 500 錯誤？
1. 查看部署日誌的錯誤訊息
2. 確認資料庫結構已經匯入
3. 檢查 `config/database.php` 設定

### Q4: 如何查看即時日誌？
```powershell
railway logs
```

### Q5: 如何重新部署？
方法一（推薦）：
```powershell
git add .
git commit -m "Update"
git push
```
Railway 會自動偵測並重新部署

方法二：
在 Railway Dashboard 點擊服務 → Deployments → 右上角 **"Redeploy"**

---

## 成本估算

Railway 免費方案：
- **每月 $5 免費額度**
- **500 小時執行時間**

以這個專案來說：
- PHP 應用程式：約 $0.002/小時
- MySQL 資料庫：約 $0.003/小時
- **總計：約 $3.6/月**（在免費額度內）

超過免費額度後：$5/月 起

---

## 進階設定

### 自訂網域

1. 在 Settings → Domains 點擊 **"Custom Domain"**
2. 輸入你的網域（例如：`echoesport.com`）
3. 按照指示在你的 DNS 服務商新增 CNAME 記錄
4. 等待 DNS 生效（通常 5-30 分鐘）

### 自動部署

如果使用 GitHub：
- 每次 push 到 `main` 或 `master` 分支
- Railway 會自動重新部署
- 無需手動操作

---

## 遷移檢查清單

- [ ] 註冊 Railway 帳號
- [ ] 建立新專案並連結 GitHub
- [ ] 新增 MySQL 資料庫
- [ ] 匯入資料庫結構（`database_heroku.sql`）
- [ ] 確認部署成功
- [ ] 產生網域名稱
- [ ] 測試首頁載入
- [ ] 測試會員註冊/登入
- [ ] 測試管理後台
- [ ] 測試訂單功能
- [ ] 更新 Funpoint 付款設定（如需要）
- [ ] 修改預設管理員密碼

---

## 需要協助？

- Railway 官方文件：https://docs.railway.app/
- Railway Discord 社群：https://discord.gg/railway
- 專案問題：檢查 GitHub Issues

---

**部署愉快！🚀**
