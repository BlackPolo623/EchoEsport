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

try {
    // 獲取用戶資料
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header('Location: ../login.php');
        exit;
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("資料庫錯誤");
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個人資料 - 迴響電競</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .profile-container {
            min-height: 100vh;
            padding: 100px 20px 50px;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
        }

        .profile-content {
            max-width: 900px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .profile-box {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 15px;
            padding: 40px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(112, 204, 225, 0.2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(112, 204, 225, 0.3);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .form-control:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .info-text {
            display: block;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(112, 204, 225, 0.15);
            border-radius: 8px;
            color: var(--text-secondary);
        }

        .btn-primary {
            padding: 14px 30px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px var(--accent-glow);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-error {
            background: rgba(244, 67, 54, 0.2);
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .form-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .profile-box {
                padding: 25px 20px;
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

        <!-- 個人資料 -->
        <div class="profile-container">
            <div class="profile-content">
                <div class="page-header">
                    <h1 class="page-title">個人資料</h1>
                </div>

                <!-- 基本資料 -->
                <div class="profile-box">
                    <h3 class="section-title">基本資料</h3>

                    <div id="profileAlert" class="alert"></div>

                    <form id="profileForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label>帳號</label>
                                <span class="info-text"><?php echo htmlspecialchars($user['username']); ?></span>
                            </div>

                            <div class="form-group">
                                <label>註冊日期</label>
                                <span class="info-text"><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="full_name">姓名</label>
                            <input type="text" id="full_name" name="full_name" class="form-control"
                                   value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">電話</label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="discord_id">Discord ID</label>
                                <input type="text" id="discord_id" name="discord_id" class="form-control"
                                       value="<?php echo htmlspecialchars($user['discord_id'] ?? ''); ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn-primary" id="profileSubmitBtn">
                            更新資料
                        </button>
                    </form>
                </div>

                <!-- 修改密碼 -->
                <div class="profile-box">
                    <h3 class="section-title">修改密碼</h3>

                    <div id="passwordAlert" class="alert"></div>

                    <form id="passwordForm">
                        <div class="form-group">
                            <label for="current_password">目前密碼</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password">新密碼</label>
                            <input type="password" id="new_password" name="new_password"
                                   class="form-control" required>
                            <small class="form-text">至少 6 個字元</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_new_password">確認新密碼</label>
                            <input type="password" id="confirm_new_password" name="confirm_new_password"
                                   class="form-control" required>
                        </div>

                        <button type="submit" class="btn-primary" id="passwordSubmitBtn">
                            更新密碼
                        </button>
                    </form>
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
        // 更新個人資料
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('profileSubmitBtn');
            const alert = document.getElementById('profileAlert');

            submitBtn.disabled = true;
            submitBtn.textContent = '更新中...';
            alert.style.display = 'none';

            const formData = new FormData(this);
            formData.append('action', 'update_profile');

            fetch('../php/api/profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = '資料更新成功';
                    alert.style.display = 'block';
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = data.message || '更新失敗，請稍後再試';
                    alert.style.display = 'block';
                }

                submitBtn.disabled = false;
                submitBtn.textContent = '更新資料';
            })
            .catch(error => {
                console.error('Error:', error);
                alert.className = 'alert alert-error';
                alert.textContent = '連線錯誤，請稍後再試';
                alert.style.display = 'block';

                submitBtn.disabled = false;
                submitBtn.textContent = '更新資料';
            });
        });

        // 修改密碼
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('passwordSubmitBtn');
            const alert = document.getElementById('passwordAlert');

            // 驗證密碼
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_new_password').value;

            if (newPassword !== confirmPassword) {
                alert.className = 'alert alert-error';
                alert.textContent = '新密碼與確認密碼不符';
                alert.style.display = 'block';
                return;
            }

            if (newPassword.length < 6) {
                alert.className = 'alert alert-error';
                alert.textContent = '密碼長度至少需要 6 個字元';
                alert.style.display = 'block';
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = '更新中...';
            alert.style.display = 'none';

            const formData = new FormData(this);
            formData.append('action', 'change_password');

            fetch('../php/api/profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = '密碼更新成功';
                    alert.style.display = 'block';

                    // 清空表單
                    document.getElementById('passwordForm').reset();
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = data.message || '密碼更新失敗';
                    alert.style.display = 'block';
                }

                submitBtn.disabled = false;
                submitBtn.textContent = '更新密碼';
            })
            .catch(error => {
                console.error('Error:', error);
                alert.className = 'alert alert-error';
                alert.textContent = '連線錯誤，請稍後再試';
                alert.style.display = 'block';

                submitBtn.disabled = false;
                submitBtn.textContent = '更新密碼';
            });
        });

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
