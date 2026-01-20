<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>近期活動 - 迴響電競</title>
    <meta name="description" content="迴響電競超值優惠與精彩活動，折扣活動、大放送活動等你來參加">
    <link rel="stylesheet" href="css/style.css">
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
                    <li><a href="events.php" class="nav-link active">活動優惠</a></li>
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

        <!-- 頁面標題 -->
        <section class="page-header">
            <div class="page-header-content">
                <h1 class="page-title">近期活動</h1>
                <p class="page-subtitle">超值優惠與精彩活動等你來參加</p>
            </div>
        </section>

        <!-- 折扣活動區域 -->
        <section class="events-section">
            <div class="container">
                <h2 class="section-title">折扣活動</h2>
                <div class="events-grid">
                    <div class="event-card">
                        <div class="event-badge">進行中</div>
                        <div class="event-image">
                            <img src="images/discount1.jpg" alt="新手專屬優惠" onerror="this.src='https://via.placeholder.com/400x250/70cce1/ffffff?text=新手專屬優惠'">
                        </div>
                        <div class="event-content">
                            <h3>新手專屬優惠</h3>
                            <p class="event-date">長期活動</p>
                            <p class="event-desc">首次預約享有7折優惠，立即體驗專業護航服務！</p>
                            <button class="event-btn" onclick="location.href='order.php'">立即領取</button>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-badge">進行中</div>
                        <div class="event-image">
                            <img src="images/discount2.jpg" alt="週末限時折扣" onerror="this.src='https://via.placeholder.com/400x250/70cce1/ffffff?text=週末限時折扣'">
                        </div>
                        <div class="event-content">
                            <h3>週末限時折扣</h3>
                            <p class="event-date">每週六日</p>
                            <p class="event-desc">週末時段預約陪玩享85折優惠，把握機會！</p>
                            <button class="event-btn" onclick="location.href='order.php'">查看詳情</button>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-badge">熱門</div>
                        <div class="event-image">
                            <img src="images/discount3.jpg" alt="會員積分回饋" onerror="this.src='https://via.placeholder.com/400x250/70cce1/ffffff?text=會員積分回饋'">
                        </div>
                        <div class="event-content">
                            <h3>會員積分回饋</h3>
                            <p class="event-date">長期活動</p>
                            <p class="event-desc">消費累積積分，兌換專屬好禮和服務折扣！</p>
                            <button class="event-btn" onclick="location.href='member/dashboard.php'">了解更多</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 大放送活動區域 -->
        <section class="events-section" style="background: linear-gradient(180deg, var(--darker-bg) 0%, var(--dark-bg) 100%);">
            <div class="container">
                <h2 class="section-title">大放送活動</h2>
                <div class="events-grid">
                    <div class="event-card">
                        <div class="event-badge" style="background: #ff4757;">大放送</div>
                        <div class="event-image">
                            <img src="images/giveaway1.jpg" alt="推薦好友送好禮" onerror="this.src='https://via.placeholder.com/400x250/70cce1/ffffff?text=推薦好友送好禮'">
                        </div>
                        <div class="event-content">
                            <h3>推薦好友送好禮</h3>
                            <p class="event-date">長期活動</p>
                            <p class="event-desc">成功推薦好友註冊，雙方各得500點積分！推薦越多送越多！</p>
                            <button class="event-btn" onclick="alert('請分享您的專屬推薦連結給好友')">立即推薦</button>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-badge" style="background: #ff4757;">大放送</div>
                        <div class="event-image">
                            <img src="images/giveaway2.jpg" alt="打手招募計畫" onerror="this.src='https://via.placeholder.com/400x250/70cce1/ffffff?text=打手招募計畫'">
                        </div>
                        <div class="event-content">
                            <h3>打手招募計畫</h3>
                            <p class="event-date">2025/01/20 開始</p>
                            <p class="event-desc">尋找頂尖電競選手加入我們的團隊，優渥待遇等你來！通過考試送3000元獎金！</p>
                            <button class="event-btn" onclick="alert('招募資訊請聯繫客服')">立即報名</button>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-badge" style="background: #ff4757;">大放送</div>
                        <div class="event-image">
                            <img src="images/giveaway3.jpg" alt="免費體驗活動" onerror="this.src='https://via.placeholder.com/400x250/70cce1/ffffff?text=免費體驗活動'">
                        </div>
                        <div class="event-content">
                            <h3>免費體驗活動</h3>
                            <p class="event-date">每月抽選</p>
                            <p class="event-desc">每月抽出10名幸運會員，免費體驗2小時頂級護航服務！</p>
                            <button class="event-btn" onclick="alert('抽獎功能開發中')">參加抽獎</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 活動須知 -->
        <section class="boosting-section">
            <div class="container">
                <div class="boosting-notes">
                    <h4>活動須知</h4>
                    <ul>
                        <li>所有活動優惠不可與其他優惠同時使用</li>
                        <li>活動內容與時間以官方公告為準，保留修改權利</li>
                        <li>獲獎者需於7日內領取獎品，逾期視同放棄</li>
                        <li>迴響電競保留活動最終解釋權</li>
                        <li>如有任何疑問，請聯繫客服人員</li>
                    </ul>
                </div>
            </div>
        </section>

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
