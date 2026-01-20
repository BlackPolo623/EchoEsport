<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>立即下單 - 迴響電競</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .order-container {
            min-height: 100vh;
            padding: 100px 20px 50px;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
        }

        .order-box {
            max-width: 800px;
            margin: 0 auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(112, 204, 225, 0.2);
            border-radius: 15px;
            padding: 50px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
        }

        .order-title {
            font-size: 2rem;
            margin-bottom: 10px;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .order-subtitle {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 40px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid rgba(112, 204, 225, 0.2);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 1.3rem;
            color: var(--primary-color);
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

        select.form-control {
            cursor: pointer;
        }

        .service-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }

        .service-option {
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(112, 204, 225, 0.3);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .service-option:hover {
            border-color: var(--primary-color);
            background: rgba(112, 204, 225, 0.1);
        }

        .service-option.active {
            border-color: var(--primary-color);
            background: rgba(112, 204, 225, 0.2);
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .service-option input[type="radio"] {
            display: none;
        }

        .service-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .service-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .payment-option {
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(112, 204, 225, 0.3);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .payment-option:hover {
            border-color: var(--primary-color);
            background: rgba(112, 204, 225, 0.1);
        }

        .payment-option.active {
            border-color: var(--primary-color);
            background: rgba(112, 204, 225, 0.2);
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .order-summary {
            background: rgba(112, 204, 225, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        .summary-row.total {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-color);
            padding-top: 10px;
            border-top: 1px solid rgba(112, 204, 225, 0.3);
        }

        .submit-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px var(--accent-glow);
        }

        .submit-btn:disabled {
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

        @media (max-width: 768px) {
            .order-box {
                padding: 30px 20px;
            }

            .service-options,
            .payment-methods {
                grid-template-columns: 1fr;
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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-menu">
                            <button class="nav-btn">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </button>
                            <div class="user-dropdown">
                                <a href="member/dashboard.php">會員中心</a>
                                <a href="member/orders.php">我的訂單</a>
                                <a href="member/profile.php">個人資料</a>
                                <a href="#" onclick="logout(); return false;">登出</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="nav-btn">登入</a>
                        <a href="register.php" class="nav-btn">註冊</a>
                    <?php endif; ?>
                    <a href="order.php" class="nav-btn highlight">立即下單</a>
                </div>
            </div>
        </nav>

        <!-- 下單表單 -->
        <div class="order-container">
            <div class="order-box">
                <h1 class="order-title">立即下單</h1>
                <p class="order-subtitle">選擇您需要的服務，我們將為您提供專業的電競體驗</p>

                <div id="alert" class="alert"></div>

                <form id="orderForm">
                    <!-- 服務類型 -->
                    <div class="form-section">
                        <h3 class="section-title">選擇服務類型</h3>
                        <div class="service-options">
                            <label class="service-option">
                                <input type="radio" name="service_type" value="陪玩" required>
                                <div class="service-icon">🎮</div>
                                <div class="service-name">專業陪玩</div>
                            </label>
                            <label class="service-option">
                                <input type="radio" name="service_type" value="代練" required>
                                <div class="service-icon">🏆</div>
                                <div class="service-name">代練上分</div>
                            </label>
                            <label class="service-option">
                                <input type="radio" name="service_type" value="VIP" required>
                                <div class="service-icon">👑</div>
                                <div class="service-name">VIP 服務</div>
                            </label>
                            <label class="service-option">
                                <input type="radio" name="service_type" value="指導" required>
                                <div class="service-icon">📚</div>
                                <div class="service-name">技術指導</div>
                            </label>
                        </div>
                    </div>

                    <!-- 伺服器選擇 -->
                    <div class="form-section">
                        <h3 class="section-title">選擇伺服器</h3>
                        <div class="form-group">
                            <select name="server" class="form-control" required>
                                <option value="">請選擇伺服器</option>
                                <option value="台灣服">台灣服</option>
                                <option value="大陸服">大陸服</option>
                            </select>
                        </div>
                    </div>

                    <!-- 金額 -->
                    <div class="form-section">
                        <h3 class="section-title">服務金額</h3>
                        <div class="form-group">
                            <label for="amount">金額（新台幣）<span class="required">*</span></label>
                            <input type="number" id="amount" name="amount" class="form-control"
                                   min="1" step="1" placeholder="請輸入金額" required>
                        </div>
                    </div>

                    <!-- 付款方式 -->
                    <div class="form-section">
                        <h3 class="section-title">選擇付款方式</h3>
                        <div class="payment-methods">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="credit_card" required>
                                <div>💳 信用卡</div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="atm" required>
                                <div>🏦 ATM 轉帳</div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cvs" required>
                                <div>🏪 超商付款</div>
                            </label>
                        </div>
                    </div>

                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <!-- 訪客資訊 -->
                    <div class="form-section">
                        <h3 class="section-title">聯絡資訊</h3>
                        <div class="form-group">
                            <label for="guest_email">Email <span class="required">*</span></label>
                            <input type="email" id="guest_email" name="guest_email" class="form-control"
                                   placeholder="用於接收訂單通知" required>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- 備註 -->
                    <div class="form-section">
                        <h3 class="section-title">訂單備註</h3>
                        <div class="form-group">
                            <textarea name="notes" class="form-control" rows="4"
                                      placeholder="有任何特殊需求請在此說明"></textarea>
                        </div>
                    </div>

                    <!-- 訂單摘要 -->
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>服務類型：</span>
                            <span id="summary-service">未選擇</span>
                        </div>
                        <div class="summary-row">
                            <span>伺服器：</span>
                            <span id="summary-server">未選擇</span>
                        </div>
                        <div class="summary-row">
                            <span>付款方式：</span>
                            <span id="summary-payment">未選擇</span>
                        </div>
                        <div class="summary-row total">
                            <span>總金額：</span>
                            <span>NT$ <span id="summary-amount">0</span></span>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">確認下單</button>
                </form>
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
        // 服務選項點擊效果
        document.querySelectorAll('.service-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.service-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                updateSummary();
            });
        });

        // 付款方式點擊效果
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                updateSummary();
            });
        });

        // 更新訂單摘要
        function updateSummary() {
            const service = document.querySelector('input[name="service_type"]:checked');
            const server = document.querySelector('select[name="server"]').value;
            const payment = document.querySelector('input[name="payment_method"]:checked');
            const amount = document.getElementById('amount').value;

            document.getElementById('summary-service').textContent = service ? service.value : '未選擇';
            document.getElementById('summary-server').textContent = server || '未選擇';
            document.getElementById('summary-payment').textContent = payment ? getPaymentName(payment.value) : '未選擇';
            document.getElementById('summary-amount').textContent = amount || '0';
        }

        function getPaymentName(value) {
            const names = {
                'credit_card': '信用卡',
                'atm': 'ATM 轉帳',
                'cvs': '超商付款'
            };
            return names[value] || value;
        }

        // 監聽輸入變化
        document.querySelector('select[name="server"]').addEventListener('change', updateSummary);
        document.getElementById('amount').addEventListener('input', updateSummary);

        // 表單提交
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const alert = document.getElementById('alert');

            // 停用按鈕
            submitBtn.disabled = true;
            submitBtn.textContent = '處理中...';

            // 隱藏警告
            alert.style.display = 'none';

            const formData = new FormData(this);
            formData.append('action', 'create_order');

            fetch('php/api/order.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = '訂單建立成功！正在導向付款頁面...';
                    alert.style.display = 'block';

                    // 導向 EchoPay 付款頁面
                    if (data.payment_url) {
                        setTimeout(() => {
                            window.location.href = data.payment_url;
                        }, 1500);
                    } else {
                        setTimeout(() => {
                            window.location.href = 'member/orders.php';
                        }, 1500);
                    }
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = data.message || '訂單建立失敗，請稍後再試';
                    alert.style.display = 'block';

                    submitBtn.disabled = false;
                    submitBtn.textContent = '確認下單';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert.className = 'alert alert-error';
                alert.textContent = '連線錯誤，請稍後再試';
                alert.style.display = 'block';

                submitBtn.disabled = false;
                submitBtn.textContent = '確認下單';
            });
        });

        function logout() {
            if (confirm('確定要登出嗎？')) {
                fetch('php/api/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=logout'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'index.php';
                    }
                });
            }
        }
    </script>
</body>
</html>
