<?php
session_start();

// 如果已登入，直接導向會員中心
if (isset($_SESSION['user_id'])) {
    header('Location: member/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員註冊 - 迴響電競</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 50px;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
        }

        .auth-box {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 15px;
            padding: 50px;
            max-width: 550px;
            width: 100%;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
        }

        .auth-title {
            font-size: 2rem;
            margin-bottom: 10px;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-subtitle {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 40px;
            font-size: 0.95rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-group label .required {
            color: var(--error-color);
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

        .form-control.error {
            border-color: var(--error-color);
        }

        .form-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px var(--accent-glow);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .auth-footer {
            text-align: center;
            margin-top: 30px;
            color: var(--text-secondary);
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
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

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .auth-box {
                padding: 30px 20px;
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
            <img src="images/Logo.png" alt="Logo" class="logo-image" onerror="this.style.display='none'">
        </div>
    </div>

    <!-- 主內容 -->
    <div class="main-content">
        <!-- 導航欄 -->
        <nav class="navbar">
            <div class="nav-container">
                <a href="index.php" class="logo">
                    <img src="images/Logo.png" alt="迴響電競" class="logo-img" onerror="this.style.display='none'">
                    <span>迴響電競</span>
                </a>

                <ul class="nav-menu">
                    <li><a href="index.php" class="nav-link">首頁</a></li>
                    <li><a href="boosters.php" class="nav-link">打手介紹</a></li>
                    <li><a href="pricing.php" class="nav-link">價目表</a></li>
                    <li><a href="events.php" class="nav-link">活動優惠</a></li>
                </ul>

                <div class="nav-buttons">
                    <a href="login.php" class="nav-btn">登入</a>
                    <a href="register.php" class="nav-btn">註冊</a>
                    <a href="order.php" class="nav-btn highlight">立即下單</a>
                </div>
            </div>
        </nav>

        <!-- 註冊表單 -->
        <div class="auth-container">
            <div class="auth-box">
                <h1 class="auth-title">會員註冊</h1>
                <p class="auth-subtitle">加入迴響電競，享受專業服務</p>

                <div id="alert" class="alert"></div>

                <form id="registerForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">帳號 <span class="required">*</span></label>
                            <input type="text" id="username" name="username" class="form-control" required>
                            <small class="form-text">4-20 個字元，僅限英文、數字、底線</small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">密碼 <span class="required">*</span></label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <small class="form-text">至少 6 個字元</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">確認密碼 <span class="required">*</span></label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">姓名 <span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">電話</label>
                            <input type="tel" id="phone" name="phone" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="discord_id">Discord ID</label>
                        <input type="text" id="discord_id" name="discord_id" class="form-control">
                        <small class="form-text">例如: username#1234</small>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">註冊</button>
                </form>

                <div class="auth-footer">
                    已經有帳號了？<a href="login.php">立即登入</a>
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
                            <li><a href="index.php">首頁</a></li>
                            <li><a href="boosters.php">打手介紹</a></li>
                            <li><a href="pricing.php">價目表</a></li>
                            <li><a href="events.php">活動優惠</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h4>會員服務</h4>
                        <ul>
                            <li><a href="login.php">會員登入</a></li>
                            <li><a href="register.php">會員註冊</a></li>
                            <li><a href="member/dashboard.php">會員中心</a></li>
                            <li><a href="order.php">立即下單</a></li>
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

    <script src="js/main.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const alert = document.getElementById('alert');

            // 驗證密碼
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                alert.className = 'alert alert-error';
                alert.textContent = '密碼與確認密碼不符';
                alert.style.display = 'block';
                return;
            }

            if (password.length < 6) {
                alert.className = 'alert alert-error';
                alert.textContent = '密碼長度至少需要 6 個字元';
                alert.style.display = 'block';
                return;
            }

            // 驗證帳號格式
            const username = document.getElementById('username').value;
            if (!/^[a-zA-Z0-9_]{4,20}$/.test(username)) {
                alert.className = 'alert alert-error';
                alert.textContent = '帳號格式不正確（4-20 個字元，僅限英文、數字、底線）';
                alert.style.display = 'block';
                return;
            }

            // 停用按鈕
            submitBtn.disabled = true;
            submitBtn.textContent = '註冊中...';

            // 隱藏警告
            alert.style.display = 'none';

            const formData = new FormData(this);
            formData.append('action', 'register');

            fetch('php/api/auth.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = '註冊成功！正在導向登入頁...';
                    alert.style.display = 'block';

                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 1500);
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = data.message || '註冊失敗，請檢查輸入資料';
                    alert.style.display = 'block';

                    submitBtn.disabled = false;
                    submitBtn.textContent = '註冊';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert.className = 'alert alert-error';
                alert.textContent = '連線錯誤，請稍後再試';
                alert.style.display = 'block';

                submitBtn.disabled = false;
                submitBtn.textContent = '註冊';
            });
        });
    </script>
</body>
</html>
