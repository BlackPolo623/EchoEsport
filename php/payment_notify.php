<?php
/**
 * 金流回調處理
 * 接收歐買尬金流的付款通知
 */

require_once '../config/database.php';
require_once '../config/payment.php';

// 記錄收到的回調資料
logPayment('Payment Notify Received', $_POST);

// 回應格式必須是純文字 "1|OK" 或 "0|ErrorMessage"
header('Content-Type: text/plain; charset=utf-8');

try {
    // 檢查必要參數
    if (!isset($_POST['MerchantTradeNo']) || !isset($_POST['CheckMacValue'])) {
        logPayment('Missing Required Parameters', $_POST);
        echo "0|Missing Required Parameters";
        exit;
    }

    // 驗證檢查碼
    if (!verifyCheckMacValue($_POST, HASH_KEY, HASH_IV)) {
        logPayment('CheckMacValue Verification Failed', $_POST);
        echo "0|CheckMacValue Verification Failed";
        exit;
    }

    // 取得付款資訊
    $merchant_trade_no = $_POST['MerchantTradeNo']; // 訂單編號
    $rtn_code = $_POST['RtnCode']; // 付款狀態碼（1 = 成功）
    $rtn_msg = $_POST['RtnMsg']; // 付款訊息
    $trade_no = isset($_POST['TradeNo']) ? $_POST['TradeNo'] : ''; // 歐買尬交易編號
    $trade_amt = isset($_POST['TradeAmt']) ? $_POST['TradeAmt'] : 0; // 交易金額
    $payment_date = isset($_POST['PaymentDate']) ? $_POST['PaymentDate'] : date('Y/m/d H:i:s'); // 付款時間
    $payment_type = isset($_POST['PaymentType']) ? $_POST['PaymentType'] : ''; // 付款方式
    $payment_type_charge_fee = isset($_POST['PaymentTypeChargeFee']) ? $_POST['PaymentTypeChargeFee'] : 0; // 手續費

    // 連接資料庫
    $database = new Database();
    $db = $database->getConnection();

    // 查詢訂單
    $query = "SELECT * FROM orders WHERE order_number = :order_number LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':order_number', $merchant_trade_no);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        logPayment('Order Not Found', ['order_number' => $merchant_trade_no]);
        echo "0|Order Not Found";
        exit;
    }

    $order = $stmt->fetch();

    // 檢查金額是否一致
    if ($order['amount'] != $trade_amt) {
        logPayment('Amount Mismatch', [
            'order_amount' => $order['amount'],
            'payment_amount' => $trade_amt
        ]);
        echo "0|Amount Mismatch";
        exit;
    }

    // 開始交易
    $db->beginTransaction();

    try {
        // 判斷付款狀態
        if ($rtn_code == '1') {
            // 付款成功
            $status = 'paid';
            $paid_at = date('Y-m-d H:i:s', strtotime($payment_date));

            // 更新訂單狀態
            $update_query = "UPDATE orders SET
                status = :status,
                paid_at = :paid_at,
                payment_info = :payment_info,
                updated_at = NOW()
                WHERE id = :id";

            $payment_info = json_encode([
                'trade_no' => $trade_no,
                'payment_type' => $payment_type,
                'payment_date' => $payment_date,
                'charge_fee' => $payment_type_charge_fee,
                'rtn_code' => $rtn_code,
                'rtn_msg' => $rtn_msg
            ], JSON_UNESCAPED_UNICODE);

            $update_stmt = $db->prepare($update_query);
            $update_stmt->bindParam(':status', $status);
            $update_stmt->bindParam(':paid_at', $paid_at);
            $update_stmt->bindParam(':payment_info', $payment_info);
            $update_stmt->bindParam(':id', $order['id']);
            $update_stmt->execute();

            // 記錄交易
            $trans_query = "INSERT INTO transactions (
                order_id,
                transaction_type,
                amount,
                status,
                payment_method,
                transaction_id,
                payment_info,
                created_at
            ) VALUES (
                :order_id,
                'payment',
                :amount,
                'completed',
                :payment_method,
                :transaction_id,
                :payment_info,
                NOW()
            )";

            $trans_stmt = $db->prepare($trans_query);
            $trans_stmt->bindParam(':order_id', $order['id']);
            $trans_stmt->bindParam(':amount', $trade_amt);
            $trans_stmt->bindParam(':payment_method', $order['payment_method']);
            $trans_stmt->bindParam(':transaction_id', $trade_no);
            $trans_stmt->bindParam(':payment_info', $payment_info);
            $trans_stmt->execute();

            // 記錄活動日誌
            if ($order['user_id']) {
                $log_query = "INSERT INTO activity_logs (
                    user_id,
                    action_type,
                    description,
                    ip_address,
                    user_agent,
                    created_at
                ) VALUES (
                    :user_id,
                    'payment_success',
                    :description,
                    :ip_address,
                    :user_agent,
                    NOW()
                )";

                $description = "訂單 {$merchant_trade_no} 付款成功，金額: NT$ {$trade_amt}";
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

                $log_stmt = $db->prepare($log_query);
                $log_stmt->bindParam(':user_id', $order['user_id']);
                $log_stmt->bindParam(':description', $description);
                $log_stmt->bindParam(':ip_address', $ip_address);
                $log_stmt->bindParam(':user_agent', $user_agent);
                $log_stmt->execute();
            }

            logPayment('Payment Success', [
                'order_number' => $merchant_trade_no,
                'trade_no' => $trade_no,
                'amount' => $trade_amt
            ]);

        } else {
            // 付款失敗
            $status = 'failed';

            $update_query = "UPDATE orders SET
                status = :status,
                payment_info = :payment_info,
                updated_at = NOW()
                WHERE id = :id";

            $payment_info = json_encode([
                'rtn_code' => $rtn_code,
                'rtn_msg' => $rtn_msg,
                'failed_at' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE);

            $update_stmt = $db->prepare($update_query);
            $update_stmt->bindParam(':status', $status);
            $update_stmt->bindParam(':payment_info', $payment_info);
            $update_stmt->bindParam(':id', $order['id']);
            $update_stmt->execute();

            logPayment('Payment Failed', [
                'order_number' => $merchant_trade_no,
                'rtn_code' => $rtn_code,
                'rtn_msg' => $rtn_msg
            ]);
        }

        // 提交交易
        $db->commit();

        // 回應成功
        echo "1|OK";

    } catch (Exception $e) {
        // 回滾交易
        $db->rollBack();
        logPayment('Database Transaction Failed', [
            'error' => $e->getMessage(),
            'order_number' => $merchant_trade_no
        ]);
        echo "0|Database Error";
    }

} catch (Exception $e) {
    logPayment('Payment Notify Error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    echo "0|System Error";
}
