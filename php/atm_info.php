<?php
/**
 * ATM 虛擬帳號資訊頁面
 * 接收 ATM 取號通知，顯示繳費資訊
 */

session_start();
require_once '../config/database.php';
require_once '../config/payment.php';

// 記錄 ATM 取號資料
logPayment('ATM Info Received', $_POST);

// 驗證檢查碼
$is_valid = false;
$order_number = '';
$bank_code = '';
$v_account = '';
$expire_date = '';
$payment_no = '';

if (isset($_POST['CheckMacValue'])) {
    $is_valid = verifyCheckMacValue($_POST, HASH_KEY, HASH_IV);

    if ($is_valid) {
        $order_number = isset($_POST['MerchantTradeNo']) ? $_POST['MerchantTradeNo'] : '';
        $bank_code = isset($_POST['BankCode']) ? $_POST['BankCode'] : '';
        $v_account = isset($_POST['vAccount']) ? $_POST['vAccount'] : '';
        $expire_date = isset($_POST['ExpireDate']) ? $_POST['ExpireDate'] : '';
        $payment_no = isset($_POST['PaymentNo']) ? $_POST['PaymentNo'] : '';

        // 更新訂單資訊
        try {
            $database = new Database();
            $db = $database->getConnection();

            // 更新訂單的付款資訊
            $payment_info = json_encode([
                'bank_code' => $bank_code,
                'v_account' => $v_account,
                'expire_date' => $expire_date,
                'payment_no' => $payment_no,
                'received_at' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE);

            $update_query = "UPDATE orders SET
                payment_info = :payment_info,
                updated_at = NOW()
                WHERE order_number = :order_number";

            $stmt = $db->prepare($update_query);
            $stmt->bindParam(':payment_info', $payment_info);
            $stmt->bindParam(':order_number', $order_number);
            $stmt->execute();

            logPayment('ATM Info Updated', [
                'order_number' => $order_number,
                'bank_code' => $bank_code,
                'v_account' => $v_account
            ]);

        } catch (Exception $e) {
            logPayment('ATM Info Update Error', ['error' => $e->getMessage()]);
        }
    }
}

// 銀行代碼對應表
$banks = [
    '004' => '台灣銀行',
    '005' => '土地銀行',
    '006' => '合作金庫',
    '007' => '第一銀行',
    '008' => '華南銀行',
    '009' => '彰化銀行',
    '011' => '上海商銀',
    '012' => '台北富邦',
    '013' => '國泰世華',
    '017' => '兆豐銀行',
    '050' => '台灣企銀',
    '822' => '中國信託'
];

$bank_name = isset($banks[$bank_code]) ? $banks[$bank_code] : "銀行代碼 {$bank_code}";
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM 繳費資訊 - 迴響電競</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .atm-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
        }

        .atm-box {
            max-width: 600px;
            width: 100%;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(112, 204, 225, 0.2);
            border-radius: 15px;
            padding: 50px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
        }

        .atm-icon {
            font-size: 80px;
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .atm-title {
            font-size: 2rem;
            margin-bottom: 15px;
            text-align: center;
            color: var(--primary-color);
        }

        .atm-subtitle {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 30px;
        }

        .atm-info {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .info-item {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(112, 204, 225, 0.1);
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: bold;
            letter-spacing: 2px;
            word-break: break-all;
        }

        .copy-btn {
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: var(--primary-color);
            color: var(--dark-bg);
        }

        .warning-box {
            background: rgba(255, 152, 0, 0.1);
            border: 1px solid var(--warning-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .warning-title {
            color: var(--warning-color);
            font-weight: bold;
            margin-bottom: 10px;
        }

        .warning-list {
            color: var(--text-secondary);
            padding-left: 20px;
        }

        .warning-list li {
            margin-bottom: 8px;
        }

        .atm-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .atm-btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .atm-btn.primary {
            background: var(--primary-color);
            color: var(--dark-bg);
        }

        .atm-btn.primary:hover {
            background: var(--primary-light);
            box-shadow: 0 5px 20px var(--accent-glow);
            transform: translateY(-2px);
        }

        .atm-btn.secondary {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .atm-btn.secondary:hover {
            background: var(--primary-color);
            color: var(--dark-bg);
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--success-color);
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            display: none;
            z-index: 10000;
        }
    </style>
</head>
<body>
    <div class="toast" id="toast">已複製到剪貼簿</div>

    <div class="atm-container">
        <div class="atm-box">
            <?php if ($is_valid && $v_account): ?>
                <div class="atm-icon">🏦</div>
                <h1 class="atm-title">ATM 轉帳繳費</h1>
                <p class="atm-subtitle">訂單編號：<?php echo htmlspecialchars($order_number); ?></p>

                <div class="atm-info">
                    <div class="info-item">
                        <div class="info-label">繳費銀行</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($bank_name); ?>
                            <button class="copy-btn" onclick="copyToClipboard('<?php echo $bank_code; ?>')">
                                複製代碼
                            </button>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">銀行代碼</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($bank_code); ?>
                            <button class="copy-btn" onclick="copyToClipboard('<?php echo $bank_code; ?>')">
                                複製
                            </button>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">虛擬帳號</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($v_account); ?>
                            <button class="copy-btn" onclick="copyToClipboard('<?php echo $v_account; ?>')">
                                複製
                            </button>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">繳費期限</div>
                        <div class="info-value" style="font-size: 1.2rem;">
                            <?php echo htmlspecialchars(date('Y/m/d H:i', strtotime($expire_date))); ?>
                        </div>
                    </div>
                </div>

                <div class="warning-box">
                    <div class="warning-title">⚠️ 重要提醒</div>
                    <ul class="warning-list">
                        <li>請於繳費期限前完成轉帳，逾期將自動取消訂單</li>
                        <li>轉帳完成後約需 30 分鐘至 1 小時確認入帳</li>
                        <li>請確認轉帳金額與訂單金額一致</li>
                        <li>虛擬帳號僅限本次訂單使用，請勿重複使用</li>
                    </ul>
                </div>

                <div class="atm-buttons">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="../member/dashboard.php" class="atm-btn primary">前往會員中心</a>
                        <a href="../member/orders.php" class="atm-btn secondary">查看訂單</a>
                    <?php else: ?>
                        <a href="../index.php" class="atm-btn primary">返回首頁</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="atm-icon">❌</div>
                <h1 class="atm-title">資訊錯誤</h1>
                <p class="atm-subtitle">無法取得 ATM 繳費資訊，請聯繫客服。</p>
                <div class="atm-buttons">
                    <a href="../index.php" class="atm-btn primary">返回首頁</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            // 顯示提示
            const toast = document.getElementById('toast');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 2000);
        }
    </script>
</body>
</html>
