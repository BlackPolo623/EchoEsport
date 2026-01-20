<?php
/**
 * 管理後台 API
 * 處理各種後台操作請求
 */

session_start();

// 檢查登入
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

require_once '../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$database = new Database();
$db = $database->getConnection();

$action = $_REQUEST['action'] ?? '';

try {
    switch ($action) {
        case 'get_order':
            // 取得訂單詳情
            $order_id = (int)$_GET['id'];

            $query = "SELECT o.*, u.username, u.email, u.phone
                      FROM orders o
                      LEFT JOIN users u ON o.user_id = u.id
                      WHERE o.id = :id
                      LIMIT 1";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $order_id);
            $stmt->execute();

            $order = $stmt->fetch();

            if ($order) {
                echo json_encode([
                    'success' => true,
                    'order' => $order
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => '訂單不存在'
                ]);
            }
            break;

        case 'update_order_status':
            // 更新訂單狀態
            $order_id = (int)$_POST['id'];
            $status = $_POST['status'];

            $allowed_statuses = ['pending', 'paid', 'processing', 'completed', 'cancelled'];
            if (!in_array($status, $allowed_statuses)) {
                echo json_encode([
                    'success' => false,
                    'message' => '無效的狀態'
                ]);
                exit;
            }

            $db->beginTransaction();

            try {
                // 更新訂單
                $query = "UPDATE orders SET
                          status = :status,
                          updated_at = NOW()
                          WHERE id = :id";

                $stmt = $db->prepare($query);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':id', $order_id);
                $stmt->execute();

                // 記錄操作日誌
                $log_query = "INSERT INTO activity_logs (
                    user_id,
                    action_type,
                    description,
                    ip_address,
                    user_agent,
                    created_at
                ) VALUES (
                    NULL,
                    'admin_update_order',
                    :description,
                    :ip_address,
                    :user_agent,
                    NOW()
                )";

                $description = "管理員 {$_SESSION['admin_username']} 更新訂單 #{$order_id} 狀態為 {$status}";
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

                $log_stmt = $db->prepare($log_query);
                $log_stmt->bindParam(':description', $description);
                $log_stmt->bindParam(':ip_address', $ip_address);
                $log_stmt->bindParam(':user_agent', $user_agent);
                $log_stmt->execute();

                $db->commit();

                echo json_encode([
                    'success' => true,
                    'message' => '訂單狀態已更新'
                ]);

            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;

        case 'get_member':
            // 取得會員詳情
            $member_id = (int)$_GET['id'];

            // 查詢會員
            $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $member_id);
            $stmt->execute();

            $member = $stmt->fetch();

            if (!$member) {
                echo json_encode([
                    'success' => false,
                    'message' => '會員不存在'
                ]);
                exit;
            }

            // 查詢會員訂單
            $orders_query = "SELECT * FROM orders
                            WHERE user_id = :user_id
                            ORDER BY created_at DESC
                            LIMIT 10";
            $orders_stmt = $db->prepare($orders_query);
            $orders_stmt->bindParam(':user_id', $member_id);
            $orders_stmt->execute();
            $orders = $orders_stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'member' => $member,
                'orders' => $orders
            ]);
            break;

        case 'update_member_status':
            // 更新會員狀態
            $member_id = (int)$_POST['id'];
            $status = $_POST['status'];

            if (!in_array($status, ['active', 'inactive'])) {
                echo json_encode([
                    'success' => false,
                    'message' => '無效的狀態'
                ]);
                exit;
            }

            $db->beginTransaction();

            try {
                // 更新會員
                $query = "UPDATE users SET
                          status = :status,
                          updated_at = NOW()
                          WHERE id = :id";

                $stmt = $db->prepare($query);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':id', $member_id);
                $stmt->execute();

                // 記錄操作日誌
                $log_query = "INSERT INTO activity_logs (
                    user_id,
                    action_type,
                    description,
                    ip_address,
                    user_agent,
                    created_at
                ) VALUES (
                    :user_id,
                    'admin_update_member',
                    :description,
                    :ip_address,
                    :user_agent,
                    NOW()
                )";

                $description = "管理員 {$_SESSION['admin_username']} 更新會員 #{$member_id} 狀態為 {$status}";
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

                $log_stmt = $db->prepare($log_query);
                $log_stmt->bindParam(':user_id', $member_id);
                $log_stmt->bindParam(':description', $description);
                $log_stmt->bindParam(':ip_address', $ip_address);
                $log_stmt->bindParam(':user_agent', $user_agent);
                $log_stmt->execute();

                $db->commit();

                echo json_encode([
                    'success' => true,
                    'message' => '會員狀態已更新'
                ]);

            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;

        case 'get_statistics':
            // 取得統計資料
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
            $query = "SELECT SUM(amount) as total FROM orders WHERE status IN ('paid', 'processing', 'completed')";
            $stmt = $db->query($query);
            $stats['total_revenue'] = $stmt->fetch()['total'] ?? 0;

            // 本月營業額
            $query = "SELECT SUM(amount) as total FROM orders
                      WHERE status IN ('paid', 'processing', 'completed')
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
            $query = "SELECT COUNT(*) as count FROM orders WHERE status = 'paid'";
            $stmt = $db->query($query);
            $stats['pending_orders'] = $stmt->fetch()['count'];

            // 各狀態訂單數
            $query = "SELECT status, COUNT(*) as count
                      FROM orders
                      GROUP BY status";
            $stmt = $db->query($query);
            $status_counts = [];
            while ($row = $stmt->fetch()) {
                $status_counts[$row['status']] = $row['count'];
            }
            $stats['status_counts'] = $status_counts;

            // 本週每日營業額
            $query = "SELECT DATE(created_at) as date, SUM(amount) as total
                      FROM orders
                      WHERE status IN ('paid', 'processing', 'completed')
                      AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                      GROUP BY DATE(created_at)
                      ORDER BY date";
            $stmt = $db->query($query);
            $daily_revenue = [];
            while ($row = $stmt->fetch()) {
                $daily_revenue[$row['date']] = $row['total'];
            }
            $stats['daily_revenue'] = $daily_revenue;

            echo json_encode([
                'success' => true,
                'statistics' => $stats
            ]);
            break;

        case 'search_orders':
            // 搜尋訂單
            $keyword = $_GET['keyword'] ?? '';
            $limit = (int)($_GET['limit'] ?? 10);

            $query = "SELECT o.*, u.username, u.email
                      FROM orders o
                      LEFT JOIN users u ON o.user_id = u.id
                      WHERE o.order_number LIKE :keyword
                      OR u.email LIKE :keyword
                      OR u.username LIKE :keyword
                      OR o.guest_email LIKE :keyword
                      ORDER BY o.created_at DESC
                      LIMIT :limit";

            $stmt = $db->prepare($query);
            $search_term = "%{$keyword}%";
            $stmt->bindParam(':keyword', $search_term);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $orders = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'orders' => $orders
            ]);
            break;

        case 'search_members':
            // 搜尋會員
            $keyword = $_GET['keyword'] ?? '';
            $limit = (int)($_GET['limit'] ?? 10);

            $query = "SELECT * FROM users
                      WHERE username LIKE :keyword
                      OR email LIKE :keyword
                      OR phone LIKE :keyword
                      ORDER BY created_at DESC
                      LIMIT :limit";

            $stmt = $db->prepare($query);
            $search_term = "%{$keyword}%";
            $stmt->bindParam(':keyword', $search_term);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $members = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'members' => $members
            ]);
            break;

        case 'export_orders':
            // 匯出訂單（CSV 格式）
            $status = $_GET['status'] ?? '';
            $start_date = $_GET['start_date'] ?? '';
            $end_date = $_GET['end_date'] ?? '';

            $where_conditions = [];
            $params = [];

            if ($status) {
                $where_conditions[] = "o.status = :status";
                $params[':status'] = $status;
            }

            if ($start_date) {
                $where_conditions[] = "DATE(o.created_at) >= :start_date";
                $params[':start_date'] = $start_date;
            }

            if ($end_date) {
                $where_conditions[] = "DATE(o.created_at) <= :end_date";
                $params[':end_date'] = $end_date;
            }

            $where_sql = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

            $query = "SELECT o.*, u.username, u.email
                      FROM orders o
                      LEFT JOIN users u ON o.user_id = u.id
                      {$where_sql}
                      ORDER BY o.created_at DESC";

            $stmt = $db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            $orders = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'orders' => $orders,
                'count' => count($orders)
            ]);
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => '無效的操作'
            ]);
            break;
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => '系統錯誤：' . $e->getMessage()
    ]);
}
