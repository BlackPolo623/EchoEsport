# 從 Heroku 遷移到 Railway - 變更總結

## 📅 遷移日期
2025-01-21

---

## 🎯 遷移原因

- Heroku 免費方案已取消
- Railway 提供更好的免費額度（每月 $5）
- Railway 部署更簡單快速
- Railway MySQL 資料庫內建支援
- 網站不會自動休眠

---

## 📝 已完成的變更

### 1. 新增 Railway 配置檔案

#### `railway.json`
- 設定 Railway 部署配置
- 使用 Nixpacks 建置系統
- 設定 PHP 啟動命令

#### `nixpacks.toml`
- 指定 PHP 8.2 及所需擴充套件
- 配置 mysqli, pdo, pdo_mysql, mbstring, openssl

#### `.railwayignore`
- 排除不必要的檔案（文件、IDE設定等）
- 減少部署包大小

### 2. 更新資料庫連線設定

#### `config/database.php`
已修改為支援多種環境：

1. **Railway MySQL**（優先）
   - `MYSQL_URL` 環境變數（完整 URL）
   - 或分開的環境變數（`MYSQLHOST`, `MYSQLUSER` 等）

2. **Heroku MySQL**（向下相容）
   - `JAWSDB_URL`
   - `CLEARDB_DATABASE_URL`

3. **本地開發環境**
   - localhost 設定

### 3. 背景影片問題修復

#### `index.php`
- 已註解掉造成無限載入的背景影片
- 改用純色漸層背景
- 解決頁面卡住問題

### 4. Heroku 檔案處理

#### 重新命名（保留備份）
- `Procfile` → `Procfile.heroku`
- `.htaccess` → `.htaccess.heroku`

#### 建立新的 `.htaccess`
- 適用於 Railway/Apache 環境
- 保留安全性設定和 URL 重寫規則

### 5. 文件更新

#### 新增文件
- `RAILWAY_DEPLOYMENT.md` - 詳細部署指南
- `QUICK_START_RAILWAY.md` - 快速開始指南（5分鐘）
- `MIGRATION_SUMMARY.md` - 本文件

#### 更新文件
- `README.md` - 加入 Railway 部署說明

---

## 🚀 部署步驟

### 選項 A：快速部署（推薦）

請參閱 **`QUICK_START_RAILWAY.md`**，只需 5 分鐘即可完成！

### 選項 B：詳細步驟

請參閱 **`RAILWAY_DEPLOYMENT.md`**，包含完整說明和常見問題解答。

---

## ✅ 部署檢查清單

部署到 Railway 後，請確認以下項目：

- [ ] 網站首頁正常載入（沒有藍屏或載入圈圈）
- [ ] 背景顯示正常（漸層背景）
- [ ] 導航選單可以使用
- [ ] 會員註冊功能正常
- [ ] 會員登入功能正常
- [ ] 管理後台可以登入（`/admin`）
  - 帳號：`admin`
  - 密碼：`admin123`
- [ ] 資料庫連線正常
- [ ] 所有頁面都可以訪問

---

## 🔧 已修復的問題

### 1. ✅ Heroku 背景影片無限載入
- **問題**：hero-video.mp4 載入 20+ 次，頁面卡住
- **解決方案**：註解影片元素，改用漸層背景
- **檔案**：`index.php:39-46`

### 2. ✅ 頁面載入後卡在藍屏
- **問題**：載入完成後主內容不顯示
- **解決方案**：修改 `js/main.js` 確保加入 visible class
- **檔案**：`js/main.js:15-35`

### 3. ✅ 資料庫連線問題
- **問題**：只支援 Heroku 環境變數
- **解決方案**：新增 Railway MySQL 環境變數支援
- **檔案**：`config/database.php:14-38`

---

## 💰 成本比較

### Heroku
- ❌ 免費方案已取消
- 💵 基本方案：$7/月起
- 💵 資料庫：額外費用

### Railway（新平台）
- ✅ 每月 $5 免費額度
- ✅ 內建 MySQL 資料庫
- ✅ 本專案預估使用：$3.6/月（在免費額度內）
- 💵 超過額度：$5/月起

**結論：Railway 更划算！**

---

## 🔄 如果需要回到 Heroku

如果因任何原因需要回到 Heroku：

1. 重新命名檔案：
```powershell
mv Procfile.heroku Procfile
mv .htaccess.heroku .htaccess
```

2. 確認 `config/database.php` 仍支援 Heroku 環境變數（已保留）

3. 部署到 Heroku：
```powershell
git add .
git commit -m "Revert to Heroku"
git push heroku main
```

---

## 📞 需要協助？

- **Railway 文件**：https://docs.railway.app/
- **Railway Discord**：https://discord.gg/railway
- **專案問題**：查看 `RAILWAY_DEPLOYMENT.md` 的常見問題區塊

---

## 🎉 下一步

1. 📖 閱讀 `QUICK_START_RAILWAY.md` 開始部署
2. 🚀 部署到 Railway（5分鐘）
3. ✅ 完成部署檢查清單
4. 🔐 修改預設管理員密碼（重要！）
5. 💳 設定 Funpoint 付款（如需要）

**祝部署順利！** 🎊
