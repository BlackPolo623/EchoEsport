<?php
/**
 * 訂單管理頁面
 */

session_start();

// 檢查登入
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// 分頁設定
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// 篩選條件
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 建立查詢
$where_conditions = [];
$params = [];

if ($status_filter) {
    $where_conditions[] = "o.status = :status";
    $params[':status'] = $status_filter;
}

if ($search) {
    $where_conditions[] = "(o.order_number LIKE :search OR u.email LIKE :search OR u.username LIKE :search OR o.guest_email LIKE :search)";
    $params[':search'] = "%{$search}%";
}

$where_sql = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// 查詢訂單
$query = "SELECT o.*, u.username, u.email
          FROM orders o
          LEFT JOIN users u ON o.user_id = u.id
          {$where_sql}
          ORDER BY o.created_at DESC
          LIMIT {$per_page} OFFSET {$offset}";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$orders = $stmt->fetchAll();

// 計算總數
$count_query = "SELECT COUNT(*) as total
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                {$where_sql}";
$count_stmt = $db->prepare($count_query);
foreach ($params as $key => $value) {
    $count_stmt->bindValue($key, $value);
}
$count_stmt->execute();
$total_orders = $count_stmt->fetch()['total'];
$total_pages = ceil($total_orders / $per_page);

// 查看訂單詳情
$order_detail = null;
if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];
    $detail_query = "SELECT o.*, u.username, u.email, u.phone
                     FROM orders o
                     LEFT JOIN users u ON o.user_id = u.id
                     WHERE o.id = :id
                     LIMIT 1";
    $detail_stmt = $db->prepare($detail_query);
    $detail_stmt->bindParam(':id', $order_id);
    $detail_stmt->execute();
    $order_detail = $detail_stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂單管理 - 迴響電競</title>
    <link rel="stylesheet" href="../css/admin-dark.css">
    <style>
        /* 訂單管理頁面使用 admin-dark.css 暗色主題 */
        .admin-user-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">迴響電競 - 管理後台</div>
            <ul class="admin-menu">
                <li><a href="index.php">儀表板</a></li>
                <li><a href="orders.php" class="active">訂單管理</a></li>
                <li><a href="members.php">會員管理</a></li>
            </ul>
            <div class="admin-user-section">
                <span class="admin-user"><span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="index.php?action=logout" class="logout-btn">登出</a></span>
            </div>
        </nav>
    </header>

    <div class="admin-content">
        <div class="page-header">
            <h1 class="page-title">訂單管理</h1>
        </div>

        <!-- 篩選 -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label>訂單狀態</label>
                    <select name="status" class="filter-select">
                        <option value="">全部狀態</option>
                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>待付款</option>
                        <option value="paid" <?php echo $status_filter === 'paid' ? 'selected' : ''; ?>>已付款</option>
                        <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>處理中</option>
                        <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>已完成</option>
                        <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>已取消</option>
                        <option value="failed" <?php echo $status_filter === 'failed' ? 'selected' : ''; ?>>付款失敗</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>搜尋訂單</label>
                    <input type="text" name="search" class="filter-input" placeholder="訂單編號或 Email"
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="filter-btn">搜尋</button>
                <a href="orders.php" class="filter-btn clear-btn">清除</a>
            </form>
        </div>

        <!-- 訂單列表 -->
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>訂單編號</th>
                        <th>客戶</th>
                        <th>服務類型</th>
                        <th>伺服器</th>
                        <th>金額</th>
                        <th>付款方式</th>
                        <th>狀態</th>
                        <th>建立時間</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
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
                                <td><?php echo htmlspecialchars($order['server']); ?></td>
                                <td>NT$ <?php echo number_format($order['amount']); ?></td>
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
                                    <a href="#" class="action-btn" onclick="showOrderDetail(<?php echo $order['id']; ?>); return false;">查看</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center; color: #999; padding: 40px;">
                                沒有找到符合條件的訂單
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- 分頁 -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"
                           class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 訂單詳情彈窗 -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">訂單詳情</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="orderDetailContent">
                載入中...
            </div>
        </div>
    </div>

    <script>
        function showOrderDetail(orderId) {
            const modal = document.getElementById('orderModal');
            const content = document.getElementById('orderDetailContent');

            modal.classList.add('show');
            content.innerHTML = '載入中...';

            fetch('api.php?action=get_order&id=' + orderId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        content.innerHTML = renderOrderDetail(data.order);
                    } else {
                        content.innerHTML = '<p style="color: #e74c3c;">載入失敗</p>';
                    }
                })
                .catch(error => {
                    content.innerHTML = '<p style="color: #e74c3c;">載入錯誤</p>';
                });
        }

        function renderOrderDetail(order) {
            const statusNames = {
                'pending': '待付款',
                'paid': '已付款',
                'processing': '處理中',
                'completed': '已完成',
                'cancelled': '已取消',
                'failed': '付款失敗'
            };

            const paymentNames = {
                'credit_card': '信用卡',
                'atm': 'ATM 轉帳',
                'cvs': '超商付款'
            };

            return `
                <div class="detail-section">
                    <h3 class="detail-title">基本資訊</h3>
                    <div class="detail-row">
                        <span class="detail-label">訂單編號</span>
                        <span class="detail-value">${order.order_number}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">客戶名稱</span>
                        <span class="detail-value">${order.username || order.guest_email || '訪客'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">${order.email || order.guest_email || '-'}</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3 class="detail-title">服務內容</h3>
                    <div class="detail-row">
                        <span class="detail-label">服務類型</span>
                        <span class="detail-value">${order.service_type}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">伺服器</span>
                        <span class="detail-value">${order.server}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">訂單金額</span>
                        <span class="detail-value">NT$ ${parseInt(order.amount).toLocaleString()}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">付款方式</span>
                        <span class="detail-value">${paymentNames[order.payment_method] || order.payment_method}</span>
                    </div>
                    ${order.notes ? `
                    <div class="detail-row">
                        <span class="detail-label">備註</span>
                        <span class="detail-value">${order.notes}</span>
                    </div>
                    ` : ''}
                </div>

                <div class="detail-section">
                    <h3 class="detail-title">狀態管理</h3>
                    <div class="detail-row">
                        <span class="detail-label">訂單狀態</span>
                        <span class="detail-value">
                            <select id="orderStatus" class="status-select">
                                <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>待付款</option>
                                <option value="paid" ${order.status === 'paid' ? 'selected' : ''}>已付款</option>
                                <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>處理中</option>
                                <option value="completed" ${order.status === 'completed' ? 'selected' : ''}>已完成</option>
                                <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>已取消</option>
                            </select>
                            <button class="update-btn" onclick="updateOrderStatus(${order.id})">更新狀態</button>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">建立時間</span>
                        <span class="detail-value">${order.created_at}</span>
                    </div>
                    ${order.paid_at ? `
                    <div class="detail-row">
                        <span class="detail-label">付款時間</span>
                        <span class="detail-value">${order.paid_at}</span>
                    </div>
                    ` : ''}
                </div>
            `;
        }

        function updateOrderStatus(orderId) {
            const status = document.getElementById('orderStatus').value;

            if (confirm('確定要更新訂單狀態嗎？')) {
                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=update_order_status&id=${orderId}&status=${status}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('狀態更新成功');
                        location.reload();
                    } else {
                        alert('狀態更新失敗：' + data.message);
                    }
                })
                .catch(error => {
                    alert('更新錯誤');
                });
            }
        }

        function closeModal() {
            document.getElementById('orderModal').classList.remove('show');
        }

        // 點擊彈窗外部關閉
        document.getElementById('orderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
