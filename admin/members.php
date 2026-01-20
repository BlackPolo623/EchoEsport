<?php
/**
 * 會員管理頁面
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

// 搜尋條件
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// 建立查詢
$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(username LIKE :search OR email LIKE :search OR phone LIKE :search)";
    $params[':search'] = "%{$search}%";
}

if ($status_filter === 'active') {
    $where_conditions[] = "status = 'active'";
} elseif ($status_filter === 'inactive') {
    $where_conditions[] = "status = 'inactive'";
}

$where_sql = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// 查詢會員
$query = "SELECT * FROM users
          {$where_sql}
          ORDER BY created_at DESC
          LIMIT {$per_page} OFFSET {$offset}";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$members = $stmt->fetchAll();

// 計算總數
$count_query = "SELECT COUNT(*) as total FROM users {$where_sql}";
$count_stmt = $db->prepare($count_query);
foreach ($params as $key => $value) {
    $count_stmt->bindValue($key, $value);
}
$count_stmt->execute();
$total_members = $count_stmt->fetch()['total'];
$total_pages = ceil($total_members / $per_page);
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員管理 - 迴響電競</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background: #f5f5f5; color: #333; }

        .admin-header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 0;
            margin-bottom: 30px;
        }

        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .admin-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .admin-menu {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .admin-menu a {
            color: #666;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .admin-menu a:hover,
        .admin-menu a.active {
            background: #f0f0f0;
            color: #667eea;
        }

        .admin-user { color: #666; }
        .logout-btn { color: #e74c3c; text-decoration: none; margin-left: 20px; }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 50px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2rem;
            color: #333;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            flex: 1;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 0.9rem;
        }

        .filter-input,
        .filter-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .filter-btn {
            padding: 10px 25px;
            background: #667eea;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            white-space: nowrap;
        }

        .filter-btn:hover {
            background: #5568d3;
        }

        .clear-btn {
            background: #999;
        }

        .clear-btn:hover {
            background: #777;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: #f5f5f5;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .table th {
            font-weight: 600;
            color: #666;
        }

        .table tr:hover {
            background: #f9f9f9;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 10px;
            cursor: pointer;
        }

        .action-btn:hover {
            text-decoration: underline;
        }

        .action-btn.danger {
            color: #e74c3c;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            padding: 20px;
        }

        .page-link {
            padding: 8px 15px;
            background: white;
            border: 1px solid #ddd;
            color: #666;
            text-decoration: none;
            border-radius: 5px;
        }

        .page-link:hover,
        .page-link.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .modal-title {
            font-size: 1.5rem;
            color: #333;
        }

        .close-btn {
            font-size: 1.5rem;
            color: #999;
            cursor: pointer;
            background: none;
            border: none;
        }

        .detail-section {
            margin-bottom: 20px;
        }

        .detail-title {
            font-size: 1.1rem;
            color: #667eea;
            margin-bottom: 15px;
        }

        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            width: 150px;
            color: #666;
        }

        .detail-value {
            flex: 1;
            color: #333;
            font-weight: 500;
        }

        .order-item {
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">迴響電競 - 管理後台</div>
            <ul class="admin-menu">
                <li><a href="index.php">儀表板</a></li>
                <li><a href="orders.php">訂單管理</a></li>
                <li><a href="members.php" class="active">會員管理</a></li>
            </ul>
            <div class="admin-user">
                <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="index.php?action=logout" class="logout-btn">登出</a>
            </div>
        </nav>
    </header>

    <div class="admin-container">
        <div class="page-header">
            <h1 class="page-title">會員管理</h1>
        </div>

        <!-- 搜尋 -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label>會員狀態</label>
                    <select name="status" class="filter-select">
                        <option value="">全部狀態</option>
                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>啟用</option>
                        <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>停用</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>搜尋會員</label>
                    <input type="text" name="search" class="filter-input" placeholder="帳號、Email 或電話"
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="filter-btn">搜尋</button>
                <a href="members.php" class="filter-btn clear-btn">清除</a>
            </form>
        </div>

        <!-- 會員列表 -->
        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>帳號</th>
                        <th>Email</th>
                        <th>電話</th>
                        <th>狀態</th>
                        <th>註冊時間</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?php echo $member['id']; ?></td>
                                <td><?php echo htmlspecialchars($member['username']); ?></td>
                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                                <td><?php echo htmlspecialchars($member['phone'] ?? '-'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $member['status']; ?>">
                                        <?php echo $member['status'] === 'active' ? '啟用' : '停用'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y/m/d H:i', strtotime($member['created_at'])); ?></td>
                                <td>
                                    <a href="#" class="action-btn" onclick="showMemberDetail(<?php echo $member['id']; ?>); return false;">查看</a>
                                    <a href="#" class="action-btn <?php echo $member['status'] === 'active' ? 'danger' : ''; ?>"
                                       onclick="toggleMemberStatus(<?php echo $member['id']; ?>, '<?php echo $member['status']; ?>'); return false;">
                                        <?php echo $member['status'] === 'active' ? '停用' : '啟用'; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #999; padding: 40px;">
                                沒有找到符合條件的會員
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

    <!-- 會員詳情彈窗 -->
    <div id="memberModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">會員詳情</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="memberDetailContent">
                載入中...
            </div>
        </div>
    </div>

    <script>
        function showMemberDetail(memberId) {
            const modal = document.getElementById('memberModal');
            const content = document.getElementById('memberDetailContent');

            modal.classList.add('show');
            content.innerHTML = '載入中...';

            fetch('api.php?action=get_member&id=' + memberId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        content.innerHTML = renderMemberDetail(data.member, data.orders);
                    } else {
                        content.innerHTML = '<p style="color: #e74c3c;">載入失敗</p>';
                    }
                })
                .catch(error => {
                    content.innerHTML = '<p style="color: #e74c3c;">載入錯誤</p>';
                });
        }

        function renderMemberDetail(member, orders) {
            let ordersHtml = '';
            if (orders && orders.length > 0) {
                ordersHtml = orders.map(order => `
                    <div class="order-item">
                        <strong>${order.order_number}</strong> -
                        ${order.service_type} -
                        NT$ ${parseInt(order.amount).toLocaleString()} -
                        <span style="color: #667eea;">${getStatusName(order.status)}</span>
                    </div>
                `).join('');
            } else {
                ordersHtml = '<p style="color: #999;">尚無訂單記錄</p>';
            }

            return `
                <div class="detail-section">
                    <h3 class="detail-title">基本資訊</h3>
                    <div class="detail-row">
                        <span class="detail-label">會員 ID</span>
                        <span class="detail-value">${member.id}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">帳號</span>
                        <span class="detail-value">${member.username}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">${member.email}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">電話</span>
                        <span class="detail-value">${member.phone || '-'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">狀態</span>
                        <span class="detail-value">${member.status === 'active' ? '啟用' : '停用'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">註冊時間</span>
                        <span class="detail-value">${member.created_at}</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3 class="detail-title">訂單記錄 (${orders ? orders.length : 0} 筆)</h3>
                    ${ordersHtml}
                </div>
            `;
        }

        function getStatusName(status) {
            const names = {
                'pending': '待付款',
                'paid': '已付款',
                'processing': '處理中',
                'completed': '已完成',
                'cancelled': '已取消',
                'failed': '付款失敗'
            };
            return names[status] || status;
        }

        function toggleMemberStatus(memberId, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? '啟用' : '停用';

            if (confirm(`確定要${action}此會員嗎？`)) {
                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=update_member_status&id=${memberId}&status=${newStatus}`
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
            document.getElementById('memberModal').classList.remove('show');
        }

        document.getElementById('memberModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
