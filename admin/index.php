<?php
/**
 * 管理後台首頁
 * 登入頁面 + 儀表板
 */

session_start();
require_once '../config/database.php';

// 處理登入
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 簡易驗證（實際應用應使用資料庫）
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_login_time'] = time();

        // 記錄登入日誌
        try {
            $database = new Database();
            $db = $database->getConnection();

            $log_query = "INSERT INTO activity_logs (
                user_id,
                action_type,
                description,
                ip_address,
                user_agent,
                created_at
            ) VALUES (
                NULL,
                'admin_login',
                :description,
                :ip_address,
                :user_agent,
                NOW()
            )";

            $description = "管理員 {$username} 登入後台";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

            $stmt = $db->prepare($log_query);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':ip_address', $ip_address);
            $stmt->bindParam(':user_agent', $user_agent);
            $stmt->execute();
        } catch (Exception $e) {
            // 忽略日誌錯誤
        }

        header('Location: index.php');
        exit;
    } else {
        $error = '帳號或密碼錯誤';
    }
}

// 處理登出
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// 檢查是否已登入
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];

// 如果已登入，顯示儀表板
if ($is_logged_in) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        // 統計資料
        $stats = [];

        // 總訂單數
        $query = "SELECT COUNT(*) as count FROM orders";
        $stmt = $db->query($query);
        $stats['total_orders'] = $stmt->fetch()['count'];

        // 今日訂單數
        $query = "SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = CURDATE()";
        $stmt = $db->query($query);
        $stats['today_orders'] = $stmt->fetch()['count'];

        // 總營業額
        $query = "SELECT SUM(amount) as total FROM orders WHERE payment_status = 'paid' AND order_status IN ('processing', 'completed')";
        $stmt = $db->query($query);
        $stats['total_revenue'] = $stmt->fetch()['total'] ?? 0;

        // 本月營業額
        $query = "SELECT SUM(amount) as total FROM orders
                  WHERE payment_status = 'paid' AND order_status IN ('processing', 'completed')
                  AND YEAR(created_at) = YEAR(CURDATE())
                  AND MONTH(created_at) = MONTH(CURDATE())";
        $stmt = $db->query($query);
        $stats['month_revenue'] = $stmt->fetch()['total'] ?? 0;

        // 會員數
        $query = "SELECT COUNT(*) as count FROM users";
        $stmt = $db->query($query);
        $stats['total_members'] = $stmt->fetch()['count'];

        // 今日新會員
        $query = "SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()";
        $stmt = $db->query($query);
        $stats['today_members'] = $stmt->fetch()['count'];

        // 待處理訂單
        $query = "SELECT COUNT(*) as count FROM orders WHERE payment_status = 'paid' AND order_status = 'new'";
        $stmt = $db->query($query);
        $stats['pending_orders'] = $stmt->fetch()['count'];

        // 最近訂單
        $query = "SELECT o.*, u.username, u.email
                  FROM orders o
                  LEFT JOIN users u ON o.user_id = u.id
                  ORDER BY o.created_at DESC
                  LIMIT 10";
        $stmt = $db->query($query);
        $recent_orders = $stmt->fetchAll();

    } catch (Exception $e) {
        $error = '資料庫錯誤：' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_logged_in ? '管理後台' : '管理員登入'; ?> - 迴響電競</title>
    <link rel="stylesheet" href="../css/admin-dark.css">
    <style>
        /* 自定義額外樣式（暗色主題已在 admin-dark.css 中定義） */
        .stat-label {
            display: block;
            margin-bottom: 8px;
        }

        .stat-subtext {
            margin-top: 8px;
            font-size: 13px;
        }

        .section-card {
            background: var(--admin-bg-card);
            border: 1px solid var(--admin-border);
            border-radius: var(--admin-radius-lg);
            padding: 24px;
            margin-bottom: 32px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--admin-border-light);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: var(--admin-bg-lighter);
        }

        .table th,
        .table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--admin-border-light);
        }

        .table th {
            font-weight: 600;
            color: var(--admin-text);
            font-size: 13px;
            text-transform: uppercase;
        }

        .table td {
            color: var(--admin-text-secondary);
        }

        .table tbody tr:hover {
            background: var(--admin-bg-hover);
        }

        .btn-link {
            color: var(--admin-primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-link:hover {
            text-decoration: underline;
            color: var(--admin-primary-light);
        }

        .admin-user-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }
    </style>
</head>
<body>
<?php if (!$is_logged_in): ?>
    <!-- 登入頁面 -->
    <div class="login-container">
        <div class="login-box">
            <h1 class="login-title">管理後台</h1>
            <p class="login-subtitle">迴響電競 - 後台管理系統</p>

            <?php if (isset($error)): ?>
                <div class="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <label>帳號</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label>密碼</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="login-btn">登入</button>
            </form>

            <p style="text-align: center; margin-top: 20px; color: #999; font-size: 0.85rem;">
                預設帳號: admin / admin123
            </p>
        </div>
    </div>
<?php else: ?>
    <!-- 儀表板 -->
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">迴響電競 - 管理後台</div>
            <ul class="admin-menu">
                <li><a href="index.php" class="active">儀表板</a></li>
                <li><a href="orders.php">訂單管理</a></li>
                <li><a href="members.php">會員管理</a></li>
            </ul>
            <div class="admin-user-section">
                <span class="admin-user"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="?action=logout" class="logout-btn">登出</a>
            </div>
        </nav>
    </header>

    <div class="admin-content">
        <h1 class="dashboard-title">儀表板</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- 統計卡片 -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">總訂單數</div>
                    <div class="stat-icon">📦</div>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_orders']); ?></div>
                <div class="stat-change">今日新增 <?php echo $stats['today_orders']; ?> 筆</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">總營業額</div>
                    <div class="stat-icon">💰</div>
                </div>
                <div class="stat-value">NT$ <?php echo number_format($stats['total_revenue']); ?></div>
                <div class="stat-change">本月 NT$ <?php echo number_format($stats['month_revenue']); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">會員數</div>
                    <div class="stat-icon">👥</div>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_members']); ?></div>
                <div class="stat-change">今日新增 <?php echo $stats['today_members']; ?> 人</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">待處理訂單</div>
                    <div class="stat-icon">⚠️</div>
                </div>
                <div class="stat-value"><?php echo number_format($stats['pending_orders']); ?></div>
                <div class="stat-change">需要立即處理</div>
            </div>
        </div>

        <!-- 最近訂單 -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">最近訂單</h2>
                <a href="orders.php" class="btn-link">查看全部 →</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>訂單編號</th>
                        <th>客戶</th>
                        <th>服務類型</th>
                        <th>金額</th>
                        <th>狀態</th>
                        <th>建立時間</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_orders)): ?>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td>
                                    <?php
                                    if ($order['username']) {
                                        echo htmlspecialchars($order['username']);
                                    } else {
                                        echo htmlspecialchars($order['guest_email'] ?? '訪客');
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($order['service_type']); ?></td>
                                <td>NT$ <?php echo number_format($order['amount']); ?></td>
                                <td>
                                    <?php
                                    $status_class = 'status-' . $order['status'];
                                    $status_names = [
                                        'pending' => '待付款',
                                        'paid' => '已付款',
                                        'processing' => '處理中',
                                        'completed' => '已完成',
                                        'cancelled' => '已取消',
                                        'failed' => '付款失敗'
                                    ];
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo $status_names[$order['status']] ?? $order['status']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y/m/d H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <a href="orders.php?id=<?php echo $order['id']; ?>" class="btn-link">查看</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #999;">暫無訂單</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
</body>
</html>
