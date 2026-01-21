<?php
/**
 * 金流配置範例檔案
 * Payment Configuration Example
 *
 * 使用說明：
 * 1. 複製此檔案並重新命名為 payment.config.php
 * 2. 根據你的金流供應商提供的資料填寫相關設定
 * 3. 確保 payment.config.php 不要提交到版本控制系統
 */

return [
    /**
     * 環境設定
     * 'test' - 測試環境（使用測試金流）
     * 'production' - 正式環境（使用正式金流，會實際扣款）
     */
    'environment' => 'test',

    /**
     * 測試環境設定
     * 測試環境不會實際扣款，用於開發和測試
     */
    'test' => [
        'merchant_id' => '3002607',               // 測試商店代號
        'hash_key' => 'pwFHCqoQZGmho4w6',         // 測試 HashKey
        'hash_iv' => 'EkRm7iFT261dpevs',          // 測試 HashIV
        'api_url' => 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5',
        'query_url' => 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V5',
    ],

    /**
     * 正式環境設定
     * ⚠️ 警告：正式環境會實際扣款！請確保已完成測試並取得正式金鑰
     */
    'production' => [
        'merchant_id' => '',                      // 您的正式商店代號
        'hash_key' => '',                         // 您的正式 HashKey
        'hash_iv' => '',                          // 您的正式 HashIV
        'api_url' => 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5',
        'query_url' => 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V5',
    ],

    /**
     * 網站 URL 設定
     * 自動偵測或手動設定網站網址
     */
    'site_url' => [
        'auto_detect' => true,                    // 自動偵測網站網址
        'manual_url' => '',                       // 手動設定（當 auto_detect 為 false 時使用）
    ],

    /**
     * 回調 URL 路徑
     * 相對於網站根目錄的路徑
     */
    'callback_urls' => [
        'return_url' => '/php/payment_result.php',    // 付款完成返回頁面
        'notify_url' => '/php/payment_notify.php',    // 付款結果通知（Server to Server）
        'atm_info_url' => '/php/atm_info.php',        // ATM 虛擬帳號取號通知
    ],

    /**
     * 支援的付款方式
     * 可以開啟或關閉特定付款方式
     */
    'payment_methods' => [
        'credit_card' => [
            'enabled' => true,
            'name' => '信用卡',
            'code' => 'Credit',
            'icon' => '💳',
        ],
        'atm' => [
            'enabled' => true,
            'name' => 'ATM 轉帳',
            'code' => 'ATM',
            'icon' => '🏦',
            'expire_days' => 3,                   // ATM 繳費期限（天數）
        ],
        'cvs' => [
            'enabled' => true,
            'name' => '超商代碼繳費',
            'code' => 'CVS',
            'icon' => '🏪',
            'expire_minutes' => 10080,            // 超商繳費期限（分鐘，7天）
        ],
        'barcode' => [
            'enabled' => false,
            'name' => '超商條碼繳費',
            'code' => 'BARCODE',
            'icon' => '📊',
        ],
    ],

    /**
     * 訂單設定
     */
    'order' => [
        'prefix' => 'ECHO',                      // 訂單編號前綴
        'min_amount' => 1,                       // 最小交易金額（元）
        'max_amount' => 20000,                   // 最大交易金額（元）
    ],

    /**
     * 日誌設定
     */
    'logging' => [
        'enabled' => true,                       // 是否啟用日誌
        'log_dir' => 'logs/payment',            // 日誌目錄
        'log_level' => 'info',                  // 日誌等級：debug, info, warning, error
        'keep_days' => 90,                      // 日誌保留天數
    ],

    /**
     * 安全設定
     */
    'security' => [
        'ip_whitelist' => [
            '211.20.145.26',                    // ECPay 正式環境 IP（範例）
            // 可以新增更多允許的 IP
        ],
        'verify_ssl' => true,                   // 驗證 SSL 憑證
        'timeout' => 30,                        // API 請求超時時間（秒）
    ],

    /**
     * 除錯模式
     * ⚠️ 正式環境請設為 false
     */
    'debug' => [
        'enabled' => false,                     // 是否啟用除錯模式
        'display_errors' => false,              // 是否顯示錯誤訊息
        'log_api_requests' => true,             // 是否記錄 API 請求
        'log_api_responses' => true,            // 是否記錄 API 回應
    ],

    /**
     * 通知設定
     */
    'notifications' => [
        'email' => [
            'enabled' => true,
            'admin_email' => 'admin@echoesport.com',  // 管理員通知信箱
            'notify_on_payment' => true,              // 付款成功時通知
            'notify_on_refund' => true,               // 退款時通知
        ],
    ],
];
