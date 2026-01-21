<?php
session_start();

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// 連接資料庫
require_once '../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$user_id = $_SESSION['user_id'];

// 分頁設置
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

// 狀態篩選
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

try {
    // 構建查詢
    $where = "user_id = ?";
    $params = [$user_id];

    if ($status_filter !== 'all') {
        $where .= " AND status = ?";
        $params[] = $status_filter;
    }

    // 獲取總數
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE $where");
    $stmt->execute($params);
    $total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_orders / $per_page);

    // 獲取訂單列表
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE $where ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $params[] = $per_page;
    $params[] = $offset;
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $orders = [];
    $total_pages = 0;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的訂單 - 迴響電競</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .orders-container {
            min-height: 100vh;
            padding: 100px 20px 50px;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
        }

        .orders-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .filter-bar {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(112, 204, 225, 0.3);
            border-radius: 8px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .filter-btn:hover {
            border-color: var(--primary-color);
            background: rgba(112, 204, 225, 0.1);
        }

        .filter-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .orders-box {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
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
            display: inline-block;
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

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .page-btn {
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(112, 204, 225, 0.3);
            border-radius: 8px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .page-btn:hover {
            border-color: var(--primary-color);
            background: rgba(112, 204, 225, 0.1);
        }

        .page-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-secondary);
        }

        .empty-state-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .btn-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .orders-table {
                font-size: 0.85rem;
            }

            .orders-table th,
            .orders-table td {
                padding: 10px 5px;
            }

            .orders-box {
                padding: 20px 10px;
                overflow-x: auto;
            }

            .filter-bar {
                flex-direction: column;
            }

            .filter-btn {
                width: 100%;
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

        <!-- 訂單列表 -->
        <div class="orders-container">
            <div class="orders-content">
                <div class="page-header">
                    <h1 class="page-title">我的訂單</h1>
                </div>

                <!-- 狀態篩選 -->
                <div class="filter-bar">
                    <a href="?status=all" class="filter-btn <?php echo $status_filter === 'all' ? 'active' : ''; ?>">
                        全部訂單
                    </a>
                    <a href="?status=pending" class="filter-btn <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">
                        待付款
                    </a>
                    <a href="?status=processing" class="filter-btn <?php echo $status_filter === 'processing' ? 'active' : ''; ?>">
                        處理中
                    </a>
                    <a href="?status=completed" class="filter-btn <?php echo $status_filter === 'completed' ? 'active' : ''; ?>">
                        已完成
                    </a>
                    <a href="?status=cancelled" class="filter-btn <?php echo $status_filter === 'cancelled' ? 'active' : ''; ?>">
                        已取消
                    </a>
                </div>

                <!-- 訂單表格 -->
                <div class="orders-box">
                    <?php if (empty($orders)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📦</div>
                            <h3>尚無訂單記錄</h3>
                            <p>開始您的第一筆訂單，體驗我們的專業服務</p>
                            <br>
                            <a href="../order.php" class="btn-link" style="font-size: 1.1rem;">立即下單 →</a>
                        </div>
                    <?php else: ?>
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>訂單編號</th>
                                    <th>服務類型</th>
                                    <th>伺服器</th>
                                    <th>金額</th>
                                    <th>付款方式</th>
                                    <th>狀態</th>
                                    <th>訂單日期</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($order['service_type']); ?></td>
                                    <td><?php echo htmlspecialchars($order['server']); ?></td>
                                    <td><strong>NT$ <?php echo number_format($order['amount']); ?></strong></td>
                                    <td>
                                        <?php
                                        $payment_names = [
                                            'credit_card' => '信用卡',
                                            'atm' => 'ATM',
                                            'cvs' => '超商'
                                        ];
                                        echo $payment_names[$order['payment_method']] ?? $order['payment_method'];
                                        ?>
                                    </td>
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

                        <!-- 分頁 -->
                        <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>&status=<?php echo $status_filter; ?>" class="page-btn">上一頁</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="page-btn active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>" class="page-btn"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>&status=<?php echo $status_filter; ?>" class="page-btn">下一頁</a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
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
