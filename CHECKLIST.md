# ✅ EchoEsport 專案檢查清單

使用本清單確保專案所有功能正常運作。

---

## 📦 檔案完整性檢查

### 根目錄檔案
- [ ] index.php - 首頁
- [ ] login.php - 登入頁面
- [ ] register.php - 註冊頁面
- [ ] order.php - 下單頁面
- [ ] boosters.php - 打手介紹
- [ ] pricing.php - 價目表
- [ ] events.php - 活動頁面
- [ ] database.sql - 資料庫結構
- [ ] Procfile - Heroku 配置
- [ ] composer.json - PHP 依賴
- [ ] .htaccess - Apache 配置
- [ ] README.md - 專案說明
- [ ] DEPLOYMENT_GUIDE.md - 部署指南
- [ ] PROJECT_STRUCTURE.md - 專案結構
- [ ] QUICKSTART.md - 快速開始
- [ ] PROJECT_SUMMARY.md - 專案總結
- [ ] CHECKLIST.md - 本檔案

### config/ 目錄
- [ ] config/database.php - 資料庫配置
- [ ] config/payment.php - 金流配置

### css/ 目錄
- [ ] css/style.css - 主樣式表
- [ ] css/pricing-toggle.css - 切換樣式

### js/ 目錄
- [ ] js/main.js - 主要 JavaScript
- [ ] js/pricing-toggle.js - 切換邏輯

### images/ 目錄
- [ ] images/Logo.png
- [ ] images/booster1.jpg
- [ ] images/booster2.jpg
- [ ] images/booster3.jpg
- [ ] images/booster4.jpg
- [ ] images/booster5.jpg
- [ ] images/booster6.jpg
- [ ] images/discount1.jpg
- [ ] images/discount2.jpg
- [ ] images/discount3.jpg
- [ ] images/giveaway1.jpg
- [ ] images/giveaway2.jpg
- [ ] images/giveaway3.jpg

### video/ 目錄
- [ ] video/hero-video.mp4

### member/ 目錄
- [ ] member/dashboard.php - 會員儀表板
- [ ] member/orders.php - 訂單記錄
- [ ] member/profile.php - 個人資料

### admin/ 目錄
- [ ] admin/index.php - 後台首頁
- [ ] admin/orders.php - 訂單管理
- [ ] admin/members.php - 會員管理
- [ ] admin/api.php - 後台 API

### php/api/ 目錄
- [ ] php/api/auth.php - 認證 API
- [ ] php/api/order.php - 訂單 API
- [ ] php/api/profile.php - 個人資料 API

### php/ 目錄
- [ ] php/payment_notify.php - 金流回調
- [ ] php/payment_result.php - 付款結果
- [ ] php/atm_info.php - ATM 資訊

---

## 🗄️ 資料庫檢查

### 資料庫建立
- [ ] 資料庫名稱為 `echoesport`
- [ ] 編碼為 `utf8mb4_unicode_ci`
- [ ] database.sql 已正確匯入

### 資料表檢查
- [ ] users - 會員表
- [ ] admins - 管理員表
- [ ] orders - 訂單表
- [ ] transactions - 交易記錄表
- [ ] activity_logs - 活動記錄表
- [ ] site_settings - 網站設定表

### 預設資料
- [ ] admins 表有預設管理員（帳號: admin）
- [ ] site_settings 表有預設設定

### 索引檢查
- [ ] users 表的 email 索引
- [ ] users 表的 username 索引
- [ ] orders 表的 order_number 索引
- [ ] orders 表的 user_id 索引

---

## 🌐 前台功能測試

### 首頁 (index.php)
- [ ] 頁面正常載入
- [ ] 載入動畫顯示
- [ ] 導航欄正常顯示
- [ ] 背景影片播放（如有）
- [ ] 特色服務卡片顯示
- [ ] 頁腳顯示正常
- [ ] 響應式設計正常（手機、平板）

### 打手介紹 (boosters.php)
- [ ] 6 位打手卡片顯示
- [ ] 打手頭像載入
- [ ] KD 比、場次資訊顯示
- [ ] 預約按鈕可點選
- [ ] 響應式佈局正常

### 價目表 (pricing.php)
- [ ] 台灣服/大陸服切換開關顯示
- [ ] 切換功能正常運作
- [ ] LocalStorage 記憶功能
- [ ] 價格卡片正確顯示
- [ ] 常見問題可展開/收合
- [ ] URL 參數支援 (?server=taiwan)

### 活動頁面 (events.php)
- [ ] 折扣活動卡片顯示
- [ ] 大放送活動卡片顯示
- [ ] 活動圖片載入
- [ ] 活動須知顯示

---

## 👤 會員系統測試

### 會員註冊 (register.php)
- [ ] 註冊表單顯示
- [ ] 表單驗證正常
  - [ ] 帳號格式驗證
  - [ ] Email 格式驗證
  - [ ] 密碼長度檢查
  - [ ] 密碼確認匹配檢查
- [ ] 帳號重複檢查
- [ ] Email 重複檢查
- [ ] 註冊成功導向登入頁
- [ ] 錯誤訊息正確顯示
- [ ] 資料正確寫入 users 表

### 會員登入 (login.php)
- [ ] 登入表單顯示
- [ ] 正確帳密可登入
- [ ] 錯誤帳密無法登入
- [ ] Session 正確建立
- [ ] 登入後導向會員中心
- [ ] 導航欄顯示會員名稱
- [ ] 下拉選單正常運作

### 會員登出
- [ ] 登出功能正常
- [ ] Session 正確清除
- [ ] 登出後導向首頁
- [ ] 導航欄恢復登入/註冊按鈕

---

## 💰 訂單與付款測試

### 下單頁面 (order.php)
- [ ] 頁面正常載入
- [ ] 服務類型選單正常
- [ ] 伺服器選擇正常
- [ ] 金額輸入框正常
- [ ] 付款方式選擇正常
- [ ] 訂單摘要即時更新
- [ ] 已登入會員自動填入資訊
- [ ] 訪客需要輸入 Email

### 訂單建立
- [ ] 訂單成功建立
- [ ] 訂單編號正確產生（ECHO + 時間戳）
- [ ] 訂單資料正確寫入 orders 表
- [ ] 訂單狀態為 pending

### 付款流程（需要金流設定）
- [ ] 信用卡付款流程
  - [ ] 正確導向金流頁面
  - [ ] CheckMacValue 正確產生
  - [ ] 付款成功回調正常
  - [ ] 訂單狀態更新為 paid
  - [ ] 交易記錄寫入 transactions 表

- [ ] ATM 轉帳流程
  - [ ] 正確導向金流頁面
  - [ ] 虛擬帳號資訊顯示
  - [ ] 銀行代碼正確
  - [ ] 繳費期限顯示

- [ ] 超商付款流程
  - [ ] 正確導向金流頁面
  - [ ] 代碼資訊顯示

### 付款結果頁面
- [ ] 成功訊息正確顯示
- [ ] 失敗訊息正確顯示
- [ ] 訂單資訊完整
- [ ] 倒數計時正常
- [ ] 自動跳轉功能

---

## 🏠 會員中心測試

### 會員儀表板 (member/dashboard.php)
- [ ] 需要登入才能訪問
- [ ] 未登入導向登入頁
- [ ] 統計卡片顯示
  - [ ] 總訂單數正確
  - [ ] 總消費金額正確
  - [ ] 待處理訂單數正確
- [ ] 最近訂單列表顯示
- [ ] 訂單狀態標籤正確

### 訂單記錄 (member/orders.php)
- [ ] 訂單列表正常顯示
- [ ] 分頁功能正常
- [ ] 狀態篩選功能
- [ ] 訂單資訊完整
- [ ] 空狀態顯示（無訂單時）

### 個人資料 (member/profile.php)
- [ ] 基本資料顯示
- [ ] 資料編輯功能
- [ ] Email 修改功能
- [ ] 修改密碼功能
  - [ ] 需要輸入目前密碼
  - [ ] 新密碼長度驗證
  - [ ] 密碼確認匹配
- [ ] 更新成功訊息
- [ ] 資料正確更新到 users 表

---

## 🔧 管理後台測試

### 後台登入 (admin/index.php)
- [ ] 登入頁面顯示
- [ ] 預設帳號可登入（admin / admin123）
- [ ] 錯誤帳密無法登入
- [ ] 登入後顯示儀表板

### 儀表板
- [ ] 統計資料顯示
  - [ ] 總訂單數
  - [ ] 今日訂單數
  - [ ] 總營業額
  - [ ] 本月營業額
  - [ ] 會員總數
  - [ ] 今日新會員
  - [ ] 待處理訂單
- [ ] 最近訂單列表
- [ ] 快速連結正常

### 訂單管理 (admin/orders.php)
- [ ] 訂單列表顯示
- [ ] 分頁功能正常
- [ ] 搜尋功能
  - [ ] 訂單編號搜尋
  - [ ] Email 搜尋
- [ ] 狀態篩選功能
- [ ] 訂單詳情彈窗
- [ ] 訂單狀態更新
  - [ ] 新訂單
  - [ ] 處理中
  - [ ] 已完成
  - [ ] 已取消
- [ ] 操作記錄到 activity_logs

### 會員管理 (admin/members.php)
- [ ] 會員列表顯示
- [ ] 分頁功能正常
- [ ] 搜尋功能
  - [ ] 帳號搜尋
  - [ ] Email 搜尋
  - [ ] 電話搜尋
- [ ] 狀態篩選（啟用/停用）
- [ ] 會員詳情彈窗
  - [ ] 基本資訊顯示
  - [ ] 訂單記錄顯示
- [ ] 啟用/停用會員功能
- [ ] 操作記錄到 activity_logs

---

## 🔒 安全性檢查

### SQL 注入防護
- [ ] 所有資料庫查詢使用 PDO 預處理
- [ ] 無直接 SQL 字串拼接
- [ ] 測試特殊字元輸入（', ", --, ; 等）

### XSS 防護
- [ ] 所有輸出使用 htmlspecialchars
- [ ] 測試 `<script>` 標籤輸入
- [ ] 測試 `<img onerror>` 輸入

### Session 安全
- [ ] Session ID 會隨機產生
- [ ] Session 在登出時正確銷毀
- [ ] HttpOnly cookie 設定
- [ ] 未登入無法訪問受保護頁面

### 密碼安全
- [ ] 密碼使用 bcrypt 加密
- [ ] 資料庫中無明文密碼
- [ ] 密碼長度限制（最少 6 字元）

### CSRF 防護
- [ ] 重要操作有二次確認
- [ ] 表單提交有驗證機制

---

## 📱 響應式設計測試

### 桌面版 (1920x1080)
- [ ] 所有頁面正常顯示
- [ ] 導航欄完整顯示
- [ ] 卡片佈局正確
- [ ] 表格顯示完整

### 平板版 (768x1024)
- [ ] 頁面自適應
- [ ] 導航欄調整
- [ ] 卡片重新排列
- [ ] 表格可橫向滾動

### 手機版 (375x667)
- [ ] 頁面完整顯示
- [ ] 導航欄轉換為漢堡選單
- [ ] 卡片單欄顯示
- [ ] 按鈕大小適合觸控
- [ ] 表格橫向滾動

---

## 🌐 瀏覽器相容性

### Chrome/Edge
- [ ] 所有功能正常
- [ ] CSS 顯示正確
- [ ] JavaScript 執行正常

### Firefox
- [ ] 所有功能正常
- [ ] CSS 顯示正確
- [ ] JavaScript 執行正常

### Safari
- [ ] 所有功能正常
- [ ] CSS 顯示正確
- [ ] JavaScript 執行正常

---

## ⚡ 效能檢查

### 載入速度
- [ ] 首頁載入時間 < 3 秒
- [ ] 圖片正常載入
- [ ] CSS/JS 正常載入
- [ ] 無 404 錯誤

### 資料庫效能
- [ ] 查詢速度快（< 100ms）
- [ ] 無 N+1 查詢問題
- [ ] 索引設定正確

### 前端優化
- [ ] 圖片延遲載入
- [ ] CSS/JS 壓縮（正式環境）
- [ ] 瀏覽器快取設定

---

## 📝 內容檢查

### 文字內容
- [ ] 無錯別字
- [ ] 語句通順
- [ ] 標點符號正確
- [ ] 專業術語正確

### 圖片資源
- [ ] Logo 圖片正確
- [ ] 打手頭像齊全
- [ ] 活動圖片齊全
- [ ] 圖片尺寸適當
- [ ] 圖片格式正確

### 連結檢查
- [ ] 所有內部連結有效
- [ ] 導航連結正確
- [ ] 按鈕連結正確
- [ ] 頁腳連結正確

---

## 🚀 部署前檢查

### 設定檔案
- [ ] config/database.php 設定正確
- [ ] config/payment.php 設定正確
- [ ] 金流 API 金鑰已填入

### 安全設定
- [ ] 管理員密碼已修改
- [ ] 敏感資訊已移除
- [ ] .htaccess 設定正確
- [ ] 錯誤訊息不洩漏資訊

### 檔案權限
- [ ] PHP 檔案可執行
- [ ] 圖片檔案可讀取
- [ ] data/ 目錄可寫入（如需要）

### Heroku 設定
- [ ] Procfile 存在
- [ ] composer.json 正確
- [ ] Git 儲存庫已初始化
- [ ] .gitignore 設定正確

---

## 📊 測試資料準備

### 測試會員
- [ ] 建立測試會員帳號
- [ ] 測試會員可登入
- [ ] 測試會員資料完整

### 測試訂單
- [ ] 建立測試訂單
- [ ] 各種狀態的訂單都有
- [ ] 測試資料合理

---

## 📖 文檔檢查

### 文檔完整性
- [ ] README.md 存在且完整
- [ ] DEPLOYMENT_GUIDE.md 存在且詳細
- [ ] PROJECT_STRUCTURE.md 存在
- [ ] QUICKSTART.md 存在
- [ ] PROJECT_SUMMARY.md 存在

### 文檔正確性
- [ ] 所有說明正確
- [ ] 範例程式碼可執行
- [ ] 截圖清晰
- [ ] 步驟完整

---

## ✅ 最終確認

### 功能完整性
- [ ] 所有前台頁面可訪問
- [ ] 所有會員功能正常
- [ ] 所有管理功能正常
- [ ] 付款流程完整

### 品質檢查
- [ ] 無明顯 Bug
- [ ] 用戶體驗良好
- [ ] 設計美觀
- [ ] 效能可接受

### 準備上線
- [ ] 所有測試項目通過
- [ ] 備份已建立
- [ ] 監控系統設定
- [ ] 客服聯絡方式正確

---

## 🎉 恭喜！

當所有項目都打勾完成後，您的專案就可以正式上線了！

**檢查完成日期**: __________
**檢查人員**: __________
**專案狀態**: [ ] 測試中  [ ] 準備上線  [ ] 已上線

---

**版本**: 1.0.0
**最後更新**: 2025-01-20
