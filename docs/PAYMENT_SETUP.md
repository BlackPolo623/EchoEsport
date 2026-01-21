# 金流設置說明文檔

> **EchoEsport 金流整合指南**
> 本文檔說明如何設置和配置金流服務（ECPay 歐付寶）

---

## 📋 目錄

1. [快速開始](#快速開始)
2. [環境需求](#環境需求)
3. [配置步驟](#配置步驟)
4. [測試流程](#測試流程)
5. [上線checklist](#上線-checklist)
6. [常見問題](#常見問題)
7. [API 參考](#api-參考)

---

## 🚀 快速開始

### 1. 複製配置檔案

```bash
cp config/payment.config.example.php config/payment.config.php
```

### 2. 編輯配置檔案

打開 `config/payment.config.php`，填入你的金流資訊：

```php
return [
    'environment' => 'test',  // 測試環境
    'test' => [
        'merchant_id' => '你的測試商店代號',
        'hash_key' => '你的測試 HashKey',
        'hash_iv' => '你的測試 HashIV',
    ],
];
```

### 3. 測試付款流程

訪問 `https://你的網站/order.php` 進行測試下單。

---

## 💻 環境需求

### 伺服器需求

- **PHP**: 7.4 或更高版本
- **MySQL**: 5.7 或更高版本
- **PHP 擴展**:
  - `pdo_mysql` - 資料庫連接
  - `mbstring` - 多位元組字串處理
  - `openssl` - SSL/TLS 支援
  - `curl` - HTTP 請求

### 金流服務商帳號

- **ECPay 歐付寶**（建議）
  - 註冊網址：https://www.ecpay.com.tw/
  - 需要完成商店審核才能使用正式環境

---

## ⚙️ 配置步驟

### 步驟 1：申請 ECPay 帳號

1. 前往 [ECPay 官網](https://www.ecpay.com.tw/) 註冊帳號
2. 完成商店資料填寫
3. 等待審核通過（通常需要 3-5 個工作日）

### 步驟 2：取得 API 金鑰

#### 測試環境

1. 登入 ECPay 測試後台：https://vendor-stage.ecpay.com.tw/
2. 點選「系統開發管理」→「系統介接設定」
3. 記錄以下資訊：
   - **特店編號（MerchantID）**
   - **HashKey**
   - **HashIV**

#### 正式環境

1. 登入 ECPay 正式後台：https://vendor.ecpay.com.tw/
2. 點選「系統開發管理」→「系統介接設定」
3. 記錄正式環境的金鑰資訊

### 步驟 3：配置回調 URL

在 ECPay 後台設定以下回調網址：

| 類型 | 網址 | 說明 |
|------|------|------|
| 返回網址 | `https://你的網站/php/payment_result.php` | 付款完成後跳轉 |
| 通知網址 | `https://你的網站/php/payment_notify.php` | Server to Server 通知 |
| ATM 通知 | `https://你的網站/php/atm_info.php` | ATM 取號通知 |

⚠️ **重要**：確保這些網址可以從外部訪問（不在本地或內網）

### 步驟 4：設定網站配置

編輯 `config/payment.config.php`：

```php
return [
    // 環境設定
    'environment' => 'test',  // 測試中使用 'test'，正式上線改為 'production'

    // 測試環境
    'test' => [
        'merchant_id' => '3002607',           // 你的測試商店代號
        'hash_key' => 'pwFHCqoQZGmho4w6',    // 你的測試 HashKey
        'hash_iv' => 'EkRm7iFT261dpevs',     // 你的測試 HashIV
        'api_url' => 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5',
        'query_url' => 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V5',
    ],

    // 正式環境（上線前填寫）
    'production' => [
        'merchant_id' => '',                  // 你的正式商店代號
        'hash_key' => '',                     // 你的正式 HashKey
        'hash_iv' => '',                      // 你的正式 HashIV
        'api_url' => 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5',
        'query_url' => 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V5',
    ],
];
```

### 步驟 5：資料庫設置

確保已執行資料庫初始化腳本：

```bash
mysql -u username -p database_name < database.sql
```

檢查是否包含以下表格：
- `orders` - 訂單表
- `transactions` - 交易記錄表
- `activity_logs` - 活動日誌表

---

## 🧪 測試流程

### 測試環境信用卡資訊

ECPay 測試環境可以使用以下測試卡號：

| 卡號 | 有效期限 | CVV | 說明 |
|------|---------|-----|------|
| 4311-9522-2222-2222 | 任意未來日期 | 222 | 測試成功 |
| 4000-2211-1111-1111 | 任意未來日期 | 111 | 測試失敗 |

### 測試 ATM 轉帳

1. 選擇 ATM 付款方式
2. 系統會產生虛擬帳號
3. 在 ECPay 後台手動標記為已付款

### 測試超商代碼

1. 選擇超商代碼付款
2. 取得繳費代碼
3. 在 ECPay 後台手動標記為已付款

### 完整測試檢查清單

- [ ] 信用卡付款 - 成功
- [ ] 信用卡付款 - 失敗
- [ ] ATM 轉帳 - 取號成功
- [ ] ATM 轉帳 - 付款完成
- [ ] 超商代碼 - 取號成功
- [ ] 超商代碼 - 付款完成
- [ ] 訂單狀態更新正確
- [ ] 付款通知接收正常
- [ ] 付款完成頁面顯示正確
- [ ] 後台訂單資料完整

---

## ✅ 上線 Checklist

### 上線前確認

- [ ] **完成所有測試項目**
- [ ] **取得 ECPay 正式環境金鑰**
- [ ] **更新 `payment.config.php` 正式環境設定**
- [ ] **將 `environment` 改為 `'production'`**
- [ ] **確認回調 URL 使用正式域名（HTTPS）**
- [ ] **關閉除錯模式**（`debug.enabled = false`）
- [ ] **設定 SSL 憑證**（金流必須使用 HTTPS）
- [ ] **備份資料庫**
- [ ] **檢查日誌權限**（確保可寫入 `logs/payment/`）
- [ ] **檢查防火牆設定**（允許 ECPay IP 訪問）

### 安全檢查

- [ ] `config/payment.config.php` 已加入 `.gitignore`
- [ ] 金鑰不要寫在前端程式碼中
- [ ] 所有付款頁面使用 HTTPS
- [ ] 驗證所有回調請求來源
- [ ] 檢查 CheckMacValue 驗證邏輯
- [ ] 設定適當的檔案權限（644 for PHP, 755 for directories）

### 上線步驟

1. **備份當前系統**
   ```bash
   # 備份資料庫
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

   # 備份檔案
   tar -czf backup_files_$(date +%Y%m%d).tar.gz .
   ```

2. **更新配置檔案**
   ```php
   // config/payment.config.php
   'environment' => 'production',
   'production' => [
       'merchant_id' => '你的正式商店代號',
       'hash_key' => '你的正式 HashKey',
       'hash_iv' => '你的正式 HashIV',
   ],
   ```

3. **測試正式環境**
   - 使用小額測試（建議 NT$1）
   - 確認付款流程正常
   - 檢查訂單狀態更新

4. **監控系統**
   - 檢查日誌檔案：`logs/payment/payment_YYYY-MM-DD.log`
   - 監控訂單狀態
   - 關注客戶回報

---

## ❓ 常見問題

### Q1: 付款後訂單狀態沒有更新？

**可能原因：**
- 回調 URL 設定錯誤
- 防火牆阻擋 ECPay 伺服器
- CheckMacValue 驗證失敗

**解決方法：**
1. 檢查 `logs/payment/` 日誌
2. 確認回調 URL 可從外部訪問
3. 檢查 HashKey 和 HashIV 是否正確

### Q2: CheckMacValue 驗證失敗？

**可能原因：**
- HashKey 或 HashIV 錯誤
- 參數編碼問題
- 參數順序錯誤

**解決方法：**
```php
// 查看日誌中的詳細錯誤訊息
tail -f logs/payment/payment_$(date +%Y-%m-%d).log
```

### Q3: 測試環境正常，正式環境失敗？

**檢查項目：**
- 確認使用正式環境金鑰
- 確認 API URL 是正式環境
- 檢查 SSL 憑證是否有效
- 確認網站使用 HTTPS

### Q4: 如何測試付款通知？

使用 ECPay 提供的測試工具：
1. 登入 ECPay 測試後台
2. 進入「測試工具」→「付款通知測試」
3. 輸入訂單編號進行測試

### Q5: 如何查看付款日誌？

```bash
# 查看今天的日誌
cat logs/payment/payment_$(date +%Y-%m-%d).log

# 即時監控日誌
tail -f logs/payment/payment_$(date +%Y-%m-%d).log

# 搜尋特定訂單
grep "ECHO20250121" logs/payment/payment_*.log
```

---

## 📚 API 參考

### generateCheckMacValue()

產生 ECPay 檢查碼

```php
function generateCheckMacValue($params, $hash_key, $hash_iv)
```

**參數：**
- `$params` (array): 要驗證的參數陣列
- `$hash_key` (string): HashKey
- `$hash_iv` (string): HashIV

**返回值：**
- `string`: 大寫的 SHA256 檢查碼

**範例：**
```php
$params = [
    'MerchantID' => '3002607',
    'MerchantTradeNo' => 'ECHO20250121001',
    'MerchantTradeDate' => '2025/01/21 15:30:45',
    'TotalAmount' => '1000',
];

$check_mac = generateCheckMacValue($params, HASH_KEY, HASH_IV);
```

### verifyCheckMacValue()

驗證 ECPay 回傳的檢查碼

```php
function verifyCheckMacValue($params, $hash_key, $hash_iv)
```

**參數：**
- `$params` (array): ECPay 回傳的完整參數（包含 CheckMacValue）
- `$hash_key` (string): HashKey
- `$hash_iv` (string): HashIV

**返回值：**
- `bool`: 驗證成功返回 `true`，失敗返回 `false`

**範例：**
```php
// 接收 ECPay 回調
$post_data = $_POST;

if (verifyCheckMacValue($post_data, HASH_KEY, HASH_IV)) {
    // 驗證成功，處理訂單
    updateOrderStatus($post_data);
} else {
    // 驗證失敗，記錄日誌
    logPayment('CheckMacValue 驗證失敗', $post_data);
}
```

### logPayment()

記錄付款日誌

```php
function logPayment($message, $data = [])
```

**參數：**
- `$message` (string): 日誌訊息
- `$data` (array): 要記錄的資料（選填）

**範例：**
```php
logPayment('付款成功', [
    'order_number' => 'ECHO20250121001',
    'amount' => 1000,
    'payment_method' => 'credit_card',
]);
```

---

## 🔒 安全最佳實踐

### 1. 保護敏感資訊

❌ **不要這樣做：**
```php
// 直接寫在程式碼中
$merchant_id = '3002607';
$hash_key = 'pwFHCqoQZGmho4w6';
```

✅ **應該這樣做：**
```php
// 使用配置檔案，並加入 .gitignore
$config = require 'config/payment.config.php';
$merchant_id = $config['test']['merchant_id'];
```

### 2. 驗證所有請求

```php
// 驗證 CheckMacValue
if (!verifyCheckMacValue($_POST, HASH_KEY, HASH_IV)) {
    logPayment('安全警告：CheckMacValue 驗證失敗', $_POST);
    http_response_code(400);
    exit('驗證失敗');
}
```

### 3. 使用 HTTPS

```php
// 檢查是否使用 HTTPS
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}
```

### 4. IP 白名單（選用）

```php
$allowed_ips = ['211.20.145.26']; // ECPay IP
$client_ip = $_SERVER['REMOTE_ADDR'];

if (!in_array($client_ip, $allowed_ips)) {
    logPayment('安全警告：未授權的 IP 訪問', ['ip' => $client_ip]);
    http_response_code(403);
    exit('訪問被拒絕');
}
```

---

## 📞 技術支援

### 官方文件
- ECPay 技術文件：https://www.ecpay.com.tw/Service/API_Dwnld
- ECPay 測試環境：https://vendor-stage.ecpay.com.tw/

### 聯絡資訊
- ECPay 客服電話：02-2655-5066
- ECPay 客服信箱：techsupport@ecpay.com.tw

### 開發團隊
- Email: dev@echoesport.com
- Discord: EchoEsport#0001

---

## 📝 更新日誌

### v1.0.0 (2025-01-21)
- 初始版本
- 支援 ECPay 金流整合
- 支援信用卡、ATM、超商代碼付款
- 完整的配置和文檔

---

**© 2025 EchoEsport. All rights reserved.**
