<?php
/**
 * 歐買尬金流設定檔
 * EchoEsport Payment Configuration
 */

// 金流環境設定
define('PAYMENT_ENV', 'test'); // test 或 production

// 測試環境設定
$test_config = [
    'MerchantID' => '3002607',
    'HashKey' => 'pwFHCqoQZGmho4w6',
    'HashIV' => 'EkRm7iFT261dpevs',
    'API_URL' => 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5',
    'QUERY_URL' => 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V5'
];

// 正式環境設定
$production_config = [
    'MerchantID' => 'YOUR_MERCHANT_ID',
    'HashKey' => 'YOUR_HASH_KEY',
    'HashIV' => 'YOUR_HASH_IV',
    'API_URL' => 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5',
    'QUERY_URL' => 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V5'
];

// 根據環境選擇設定
$config = (PAYMENT_ENV === 'production') ? $production_config : $test_config;

// 金流設定常數
define('MERCHANT_ID', $config['MerchantID']);
define('HASH_KEY', $config['HashKey']);
define('HASH_IV', $config['HashIV']);
define('PAYMENT_API_URL', $config['API_URL']);
define('PAYMENT_QUERY_URL', $config['QUERY_URL']);

// 回調 URL（需根據實際域名調整）
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
    . "://{$_SERVER['HTTP_HOST']}";
$base_path = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'])));
$site_url = rtrim($base_url . $base_path, '/');

define('RETURN_URL', $site_url . '/php/payment_result.php'); // 付款完成返回頁面
define('NOTIFY_URL', $site_url . '/php/payment_notify.php'); // 付款結果通知
define('ATM_INFO_URL', $site_url . '/php/atm_info.php'); // ATM 虛擬帳號取號通知

// 付款方式代碼
define('PAYMENT_CREDIT', 'Credit'); // 信用卡
define('PAYMENT_ATM', 'ATM'); // ATM 轉帳
define('PAYMENT_CVS', 'CVS'); // 超商代碼繳費
define('PAYMENT_BARCODE', 'BARCODE'); // 超商條碼繳費

/**
 * 產生檢查碼
 */
function generateCheckMacValue($params, $hash_key, $hash_iv) {
    // 1. 依照 A-Z 排序參數
    ksort($params);

    // 2. 串接參數
    $check_str = "HashKey={$hash_key}";
    foreach ($params as $key => $value) {
        $check_str .= "&{$key}={$value}";
    }
    $check_str .= "&HashIV={$hash_iv}";

    // 3. URL Encode
    $check_str = urlencode($check_str);

    // 4. 轉小寫
    $check_str = strtolower($check_str);

    // 5. 替換特殊字元
    $check_str = str_replace('%2d', '-', $check_str);
    $check_str = str_replace('%5f', '_', $check_str);
    $check_str = str_replace('%2e', '.', $check_str);
    $check_str = str_replace('%21', '!', $check_str);
    $check_str = str_replace('%2a', '*', $check_str);
    $check_str = str_replace('%28', '(', $check_str);
    $check_str = str_replace('%29', ')', $check_str);

    // 6. SHA256 加密
    $check_mac_value = hash('sha256', $check_str);

    // 7. 轉大寫
    return strtoupper($check_mac_value);
}

/**
 * 驗證檢查碼
 */
function verifyCheckMacValue($params, $hash_key, $hash_iv) {
    $check_mac_value = isset($params['CheckMacValue']) ? $params['CheckMacValue'] : '';
    unset($params['CheckMacValue']);

    $generated = generateCheckMacValue($params, $hash_key, $hash_iv);

    return $check_mac_value === $generated;
}

/**
 * 取得付款方式名稱
 */
function getPaymentMethodName($method) {
    $names = [
        'credit_card' => '信用卡',
        'atm' => 'ATM 轉帳',
        'cvs' => '超商代碼繳費',
        'barcode' => '超商條碼繳費'
    ];
    return isset($names[$method]) ? $names[$method] : $method;
}

/**
 * 取得付款方式代碼（轉換為歐買尬格式）
 */
function getEcpayPaymentCode($method) {
    $codes = [
        'credit_card' => PAYMENT_CREDIT,
        'atm' => PAYMENT_ATM,
        'cvs' => PAYMENT_CVS,
        'barcode' => PAYMENT_BARCODE
    ];
    return isset($codes[$method]) ? $codes[$method] : PAYMENT_CREDIT;
}

/**
 * 記錄付款日誌
 */
function logPayment($message, $data = []) {
    $log_dir = dirname(__DIR__) . '/logs/payment';
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0777, true);
    }

    $log_file = $log_dir . '/payment_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $log_entry = "[{$timestamp}] {$message}\n{$log_data}\n" . str_repeat('-', 80) . "\n";

    file_put_contents($log_file, $log_entry, FILE_APPEND);
}
