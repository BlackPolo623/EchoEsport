<?php
/**
 * 會員認證 API
 * 處理註冊、登入、登出功能
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

$database = new Database();
$conn = $database->getConnection();

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'register':
        register($conn);
        break;
    case 'login':
        login($conn);
        break;
    case 'logout':
        logout();
        break;
    case 'check_session':
        checkSession();
        break;
    default:
        echo json_encode(['success' => false, 'message' => '無效的操作']);
}

function register($conn) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $discord_id = trim($_POST['discord_id'] ?? '');

    // 驗證必填欄位
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => '請填寫所有必填欄位']);
        return;
    }

    // 驗證密碼長度
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => '密碼長度至少 6 個字元']);
        return;
    }

    // 驗證密碼確認
    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => '密碼確認不符']);
        return;
    }

    // 驗證 email 格式
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => '請輸入有效的 Email 地址']);
        return;
    }

    // 檢查帳號是否已存在
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => '帳號或 Email 已存在']);
        return;
    }

    // 加密密碼
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 新增會員
    try {
        $stmt = $conn->prepare("
            INSERT INTO users (username, email, password, full_name, phone, discord_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $email, $hashed_password, $full_name, $phone, $discord_id]);

        // 記錄活動
        logActivity($conn, 'user', $conn->lastInsertId(), 'register', "會員註冊: $username");

        echo json_encode([
            'success' => true,
            'message' => '註冊成功！請登入'
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => '註冊失敗：' . $e->getMessage()]);
    }
}

function login($conn) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => '請輸入帳號和密碼']);
        return;
    }

    // 查詢會員
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => '帳號或密碼錯誤']);
        return;
    }

    // 檢查帳號是否啟用
    if ($user['is_active'] != 1) {
        echo json_encode(['success' => false, 'message' => '帳號已被停用，請聯繫客服']);
        return;
    }

    // 驗證密碼
    if (!password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => '帳號或密碼錯誤']);
        return;
    }

    // 設定 Session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];

    // 更新最後登入時間
    $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $stmt->execute([$user['id']]);

    // 記錄活動
    logActivity($conn, 'user', $user['id'], 'login', "會員登入: {$user['username']}");

    echo json_encode([
        'success' => true,
        'message' => '登入成功',
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name']
        ]
    ]);
}

function logout() {
    session_destroy();
    echo json_encode(['success' => true, 'message' => '已登出']);
}

function checkSession() {
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => true,
            'logged_in' => true,
            'user' => [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'full_name' => $_SESSION['full_name'] ?? ''
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'logged_in' => false
        ]);
    }
}

function logActivity($conn, $user_type, $user_id, $action, $description) {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $stmt = $conn->prepare("
            INSERT INTO activity_logs (user_type, user_id, action, description, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$user_type, $user_id, $action, $description, $ip, $user_agent]);
    } catch (Exception $e) {
        // 靜默失敗，不影響主要流程
    }
}
