<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/database.php';

$response = ['success' => false, 'message' => ''];

try {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create_order':
            createOrder($pdo);
            break;

        default:
            $response['message'] = '無效的操作';
    }

} catch (Exception $e) {
    error_log("Order API Error: " . $e->getMessage());
    $response['message'] = '系統錯誤，請稍後再試';
}

echo json_encode($response);

// 建立訂單
function createOrder($pdo) {
    global $response;

    // 驗證必要欄位
    $required_fields = ['service_type', 'server', 'amount', 'payment_method'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $response['message'] = '請填寫所有必要欄位';
            return;
        }
    }

    $service_type = $_POST['service_type'];
    $server = $_POST['server'];
    $amount = (int)$_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $notes = $_POST['notes'] ?? '';

    // 驗證金額
    if ($amount <= 0) {
        $response['message'] = '金額必須大於 0';
        return;
    }

    // 確定用戶
    $user_id = null;
    $guest_email = null;

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // 訪客訂單
        if (empty($_POST['guest_email'])) {
            $response['message'] = '請提供 Email 地址';
            return;
        }
        $guest_email = $_POST['guest_email'];

        // 驗證 Email 格式
        if (!filter_var($guest_email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Email 格式不正確';
            return;
        }
    }

    // 生成訂單編號
    $order_number = 'ECH' . date('YmdHis') . rand(1000, 9999);

    // 插入訂單
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            order_number, user_id, guest_email, service_type, server,
            amount, payment_method, notes, status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");

    $stmt->execute([
        $order_number,
        $user_id,
        $guest_email,
        $service_type,
        $server,
        $amount,
        $payment_method,
        $notes
    ]);

    $order_id = $pdo->lastInsertId();

    // 整合 EchoPay 付款
    $payment_url = null;

    // 如果需要整合 EchoPay，在這裡建立付款連結
    // 這裡需要根據 EchoPaywebsite 的 API 來實作
    // 暫時先返回訂單頁面的連結

    $response['success'] = true;
    $response['message'] = '訂單建立成功';
    $response['order_id'] = $order_id;
    $response['order_number'] = $order_number;

    // 如果有付款連結，返回付款頁面
    if ($payment_url) {
        $response['payment_url'] = $payment_url;
    } else {
        // 否則導向訂單列表
        if (isset($_SESSION['user_id'])) {
            $response['redirect_url'] = '../member/orders.php';
        } else {
            // 訪客用戶顯示訂單編號
            $response['message'] = '訂單建立成功！訂單編號：' . $order_number;
        }
    }
}
