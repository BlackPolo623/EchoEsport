<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/database.php';

$response = ['success' => false, 'message' => ''];

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    $response['message'] = '請先登入';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_profile':
            updateProfile($pdo, $user_id);
            break;

        case 'change_password':
            changePassword($pdo, $user_id);
            break;

        default:
            $response['message'] = '無效的操作';
    }

} catch (Exception $e) {
    error_log("Profile API Error: " . $e->getMessage());
    $response['message'] = '系統錯誤，請稍後再試';
}

echo json_encode($response);

// 更新個人資料
function updateProfile($pdo, $user_id) {
    global $response;

    // 驗證必要欄位
    $required_fields = ['email', 'full_name'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $response['message'] = '請填寫所有必要欄位';
            return;
        }
    }

    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone'] ?? '');
    $discord_id = trim($_POST['discord_id'] ?? '');

    // 驗證 Email 格式
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Email 格式不正確';
        return;
    }

    // 檢查 Email 是否被其他用戶使用
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        $response['message'] = '此 Email 已被使用';
        return;
    }

    // 更新資料
    $stmt = $pdo->prepare("
        UPDATE users
        SET email = ?, full_name = ?, phone = ?, discord_id = ?, updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $email,
        $full_name,
        $phone,
        $discord_id,
        $user_id
    ]);

    $response['success'] = true;
    $response['message'] = '資料更新成功';
}

// 修改密碼
function changePassword($pdo, $user_id) {
    global $response;

    // 驗證必要欄位
    $required_fields = ['current_password', 'new_password', 'confirm_new_password'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $response['message'] = '請填寫所有欄位';
            return;
        }
    }

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_new_password'];

    // 驗證新密碼
    if ($new_password !== $confirm_password) {
        $response['message'] = '新密碼與確認密碼不符';
        return;
    }

    if (strlen($new_password) < 6) {
        $response['message'] = '密碼長度至少需要 6 個字元';
        return;
    }

    // 獲取當前密碼
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $response['message'] = '用戶不存在';
        return;
    }

    // 驗證當前密碼
    if (!password_verify($current_password, $user['password'])) {
        $response['message'] = '目前密碼不正確';
        return;
    }

    // 更新密碼
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        UPDATE users
        SET password = ?, updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $hashed_password,
        $user_id
    ]);

    $response['success'] = true;
    $response['message'] = '密碼更新成功';
}
