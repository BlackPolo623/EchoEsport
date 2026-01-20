<?php
session_start();

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// 連接資料庫
require_once '../config/database.php';

$user_id = $_SESSION['user_id'];

// 獲取統計數據
$stats = [
    'total_orders' => 0,
    'total_spent' => 0,
    'pending_orders' => 0
];

try {
    // 總訂單數
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stats['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 總消費
    $stmt = $pdo->prepare("SELECT SUM(amount) as total FROM orders WHERE user_id = ? AND status != 'cancelled'");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['total_spent'] = $result['total'] ?? 0;

    // 待處理訂單
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ? AND status IN ('pending', 'processing')");
    $stmt->execute([$user_id]);
    $stats['pending_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 最近訂單
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$user_id]);
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $recent_orders = [];
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員中心 - 迴響電競</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .dashboard-container {
            min-height: 100vh;
            padding: 100px 20px 50px;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
        }

        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            margin-bottom: 40px;
        }

        .dashboard-title {
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dashboard-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 15px;
            padding: 30px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px var(--accent-glow);
            border-color: var(--primary-color);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .section-box {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th,
        .orders-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(112, 204, 225, 0.1);
        }

        .orders-table th {
            color: var(--primary-color);
            font-weight: 600;
        }

        .orders-table tr:hover {
            background: rgba(112, 204, 225, 0.05);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-pending {
            background: rgba(255, 152, 0, 0.2);
            color: var(--warning-color);
        }

        .status-processing {
            background: rgba(33, 150, 243, 0.2);
            color: #2196f3;
        }

        .status-completed {
            background: rgba(76, 175, 80, 0.2);
            color: var(--success-color);
        }

        .status-cancelled {
            background: rgba(244, 67, 54, 0.2);
            color: var(--error-color);
        }

        .btn-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--text-secondary);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .orders-table {
                font-size: 0.9rem;
            }

            .orders-table th,
            .orders-table td {
                padding: 10px 5px;
            }
        }
    </style>
</head>
<body>
    <!-- 載入畫面 -->
    <div class="loading-screen">
        <div class="spinner">
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <img src="../images/Logo.png" alt="Logo" class="logo-image" onerror="this.style.display='none'">
        </div>
    </div>

    <!-- 主內容 -->
    <div class="main-content">
        <!-- 導航欄 -->
        <nav class="navbar">
            <div class="nav-container">
                <a href="../index.php" class="logo">
                    <img src="../images/Logo.png" alt="迴響電競" class="logo-img" onerror="this.style.display='none'">
                    <span>迴響電競</span>
                </a>

                <ul class="nav-menu">
                    <li><a href="../index.php" class="nav-link">首頁</a></li>
                    <li><a href="../boosters.php" class="nav-link">打手介紹</a></li>
                    <li><a href="../pricing.php" class="nav-link">價目表</a></li>
                    <li><a href="../events.php" class="nav-link">活動優惠</a></li>
                </ul>

                <div class="nav-buttons">
                    <div class="user-menu">
                        <button class="nav-btn">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </button>
                        <div class="user-dropdown">
                            <a href="dashboard.php">會員中心</a>
                            <a href="orders.php">我的訂單</a>
                            <a href="profile.php">個人資料</a>
                            <a href="#" onclick="logout(); return false;">登出</a>
                        </div>
                    </div>
                    <a href="../order.php" class="nav-btn highlight">立即下單</a>
                </div>
            </div>
        </nav>

        <!-- 儀表板內容 -->
        <div class="dashboard-container">
            <div class="dashboard-content">
                <div class="dashboard-header">
                    <h1 class="dashboard-title">會員中心</h1>
                    <p class="dashboard-subtitle">歡迎回來，<?php echo htmlspecialchars($_SESSION['username']); ?></p>
                </div>

                <!-- 統計卡片 -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📦</div>
                        <div class="stat-label">總訂單數</div>
                        <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">💰</div>
                        <div class="stat-label">總消費金額</div>
                        <div class="stat-value">NT$ <?php echo number_format($stats['total_spent']); ?></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-label">待處理訂單</div>
                        <div class="stat-value"><?php echo $stats['pending_orders']; ?></div>
                    </div>
                </div>

                <!-- 最近訂單 -->
                <div class="section-box">
                    <div class="section-title">
                        <span>最近訂單</span>
                        <a href="orders.php" class="btn-link">查看全部 →</a>
                    </div>

                    <?php if (empty($recent_orders)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📋</div>
                            <p>尚無訂單記錄</p>
                            <br>
                            <a href="../order.php" class="btn-link">立即下單</a>
                        </div>
                    <?php else: ?>
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>訂單編號</th>
                                    <th>服務類型</th>
                                    <th>金額</th>
                                    <th>狀態</th>
                                    <th>日期</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['order_number']); ?></td>
                                    <td><?php echo htmlspecialchars($order['service_type']); ?></td>
                                    <td>NT$ <?php echo number_format($order['amount']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $order['status']; ?>">
                                            <?php
                                            $status_names = [
                                                'pending' => '待付款',
                                                'processing' => '處理中',
                                                'completed' => '已完成',
                                                'cancelled' => '已取消'
                                            ];
                                            echo $status_names[$order['status']] ?? $order['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 頁腳 -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>迴響電競</h3>
                        <p>專業的電競陪玩與代練服務平台</p>
                    </div>

                    <div class="footer-section">
                        <h4>快速連結</h4>
                        <ul>
                            <li><a href="../index.php">首頁</a></li>
                            <li><a href="../boosters.php">打手介紹</a></li>
                            <li><a href="../pricing.php">價目表</a></li>
                            <li><a href="../events.php">活動優惠</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h4>會員服務</h4>
                        <ul>
                            <li><a href="../login.php">會員登入</a></li>
                            <li><a href="../register.php">會員註冊</a></li>
                            <li><a href="dashboard.php">會員中心</a></li>
                            <li><a href="../order.php">立即下單</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h4>聯絡我們</h4>
                        <ul>
                            <li>Email: contact@echoesport.com</li>
                            <li>Discord: EchoEsport#0001</li>
                            <li>Line: @echoesport</li>
                        </ul>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; 2025 迴響電競 Echo Esports. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="../js/main.js"></script>
    <script>
        function logout() {
            if (confirm('確定要登出嗎？')) {
                fetch('../php/api/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=logout'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '../index.php';
                    }
                });
            }
        }
    </script>
</body>
</html>
