<?php
/**
 * 付款結果頁面
 * 顯示付款成功/失敗訊息
 */

session_start();
require_once '../config/database.php';
require_once '../config/payment.php';

// 記錄返回資料
logPayment('Payment Result Page', $_POST);

// 驗證檢查碼
$is_valid = false;
$order_number = '';
$rtn_code = 0;
$rtn_msg = '';

if (isset($_POST['CheckMacValue'])) {
    $is_valid = verifyCheckMacValue($_POST, HASH_KEY, HASH_IV);
    $order_number = isset($_POST['MerchantTradeNo']) ? $_POST['MerchantTradeNo'] : '';
    $rtn_code = isset($_POST['RtnCode']) ? $_POST['RtnCode'] : 0;
    $rtn_msg = isset($_POST['RtnMsg']) ? $_POST['RtnMsg'] : '';
}

// 查詢訂單資訊
$order = null;
if ($order_number) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        $query = "SELECT * FROM orders WHERE order_number = :order_number LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':order_number', $order_number);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $order = $stmt->fetch();
        }
    } catch (Exception $e) {
        logPayment('Query Order Error', ['error' => $e->getMessage()]);
    }
}

$is_success = $is_valid && $rtn_code == '1';
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>付款結果 - 迴響電競</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .result-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
        }

        .result-box {
            max-width: 600px;
            width: 100%;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(112, 204, 225, 0.2);
            border-radius: 15px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
        }

        .result-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .result-icon.success {
            color: var(--success-color);
        }

        .result-icon.error {
            color: var(--error-color);
        }

        .result-title {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--text-primary);
        }

        .result-message {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .order-info {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(112, 204, 225, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--text-secondary);
        }

        .info-value {
            color: var(--text-primary);
            font-weight: 500;
        }

        .countdown {
            color: var(--primary-color);
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .result-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .result-btn {
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

        .result-btn.primary {
            background: var(--primary-color);
            color: var(--dark-bg);
        }

        .result-btn.primary:hover {
            background: var(--primary-light);
            box-shadow: 0 5px 20px var(--accent-glow);
            transform: translateY(-2px);
        }

        .result-btn.secondary {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .result-btn.secondary:hover {
            background: var(--primary-color);
            color: var(--dark-bg);
        }
    </style>
</head>
<body>
    <div class="result-container">
        <div class="result-box">
            <?php if ($is_success): ?>
                <div class="result-icon success">✓</div>
                <h1 class="result-title">付款成功！</h1>
                <p class="result-message">
                    感謝您的付款，我們已收到您的款項。<br>
                    訂單將會盡快為您處理。
                </p>
            <?php else: ?>
                <div class="result-icon error">✗</div>
                <h1 class="result-title">付款失敗</h1>
                <p class="result-message">
                    很抱歉，付款過程中發生錯誤。<br>
                    <?php echo $rtn_msg ? htmlspecialchars($rtn_msg) : '請稍後再試或聯繫客服。'; ?>
                </p>
            <?php endif; ?>

            <?php if ($order): ?>
                <div class="order-info">
                    <div class="info-row">
                        <span class="info-label">訂單編號：</span>
                        <span class="info-value"><?php echo htmlspecialchars($order['order_number']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">服務類型：</span>
                        <span class="info-value"><?php echo htmlspecialchars($order['service_type']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">訂單金額：</span>
                        <span class="info-value">NT$ <?php echo number_format($order['amount']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">訂單狀態：</span>
                        <span class="info-value">
                            <?php
                            $status_names = [
                                'pending' => '待付款',
                                'paid' => '已付款',
                                'processing' => '處理中',
                                'completed' => '已完成',
                                'cancelled' => '已取消',
                                'failed' => '付款失敗'
                            ];
                            echo $status_names[$order['status']] ?? $order['status'];
                            ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <p class="countdown" id="countdown">
                <span id="seconds">5</span> 秒後自動跳轉...
            </p>

            <div class="result-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="../member/dashboard.php" class="result-btn primary">前往會員中心</a>
                    <a href="../member/orders.php" class="result-btn secondary">查看訂單</a>
                <?php else: ?>
                    <a href="../index.php" class="result-btn primary">返回首頁</a>
                    <a href="../order.php" class="result-btn secondary">重新下單</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // 倒數計時自動跳轉
        let seconds = 5;
        const countdownElement = document.getElementById('seconds');

        const countdown = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(countdown);
                <?php if (isset($_SESSION['user_id'])): ?>
                    window.location.href = '../member/dashboard.php';
                <?php else: ?>
                    window.location.href = '../index.php';
                <?php endif; ?>
            }
        }, 1000);
    </script>
</body>
</html>
