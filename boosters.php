<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>打手介紹 - 迴響電競</title>
    <meta name="description" content="迴響電競專業打手團隊介紹，頂尖射擊遊戲專家，護你征戰戰場">
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
                    <li><a href="boosters.php" class="nav-link active">打手介紹</a></li>
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

        <!-- 頁面標題 -->
        <section class="page-header">
            <div class="page-header-content">
                <h1 class="page-title">打手介紹</h1>
                <p class="page-subtitle">頂尖射擊遊戲專家，護你征戰戰場</p>
            </div>
        </section>

        <!-- 打手介紹區域 -->
        <section class="boosters-section">
            <div class="container">
                <h2 class="section-title">專業打手團隊</h2>
                <div class="boosters-grid">
                    <div class="booster-card">
                        <div class="booster-image">
                            <img src="images/booster1.jpg" alt="打手" onerror="this.src='https://via.placeholder.com/300x400/70cce1/ffffff?text=暗影狙擊'">
                            <div class="booster-rank">頂尖</div>
                        </div>
                        <div class="booster-info">
                            <h3 class="booster-name">暗影狙擊</h3>
                            <p class="booster-role">狙擊手 / 偵察兵</p>
                            <div class="booster-stats">
                                <div class="stat">
                                    <span class="stat-label">KD</span>
                                    <span class="stat-value">3.8</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">場次</span>
                                    <span class="stat-value">2500</span>
                                </div>
                            </div>
                            <p class="booster-desc">前職業選手，精準度極高，擅長遠距離狙殺與戰場偵察</p>
                            <button class="booster-btn" onclick="location.href='order.php'">預約打手</button>
                        </div>
                    </div>

                    <div class="booster-card">
                        <div class="booster-image">
                            <img src="images/booster2.jpg" alt="打手" onerror="this.src='https://via.placeholder.com/300x400/70cce1/ffffff?text=鋼鐵突擊'">
                            <div class="booster-rank">頂尖</div>
                        </div>
                        <div class="booster-info">
                            <h3 class="booster-name">鋼鐵突擊</h3>
                            <p class="booster-role">突擊手 / 前鋒</p>
                            <div class="booster-stats">
                                <div class="stat">
                                    <span class="stat-label">KD</span>
                                    <span class="stat-value">3.5</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">場次</span>
                                    <span class="stat-value">3200</span>
                                </div>
                            </div>
                            <p class="booster-desc">近戰王者，擅長突破防線，壓制力極強，是團隊的先鋒</p>
                            <button class="booster-btn" onclick="location.href='order.php'">預約打手</button>
                        </div>
                    </div>

                    <div class="booster-card">
                        <div class="booster-image">
                            <img src="images/booster3.jpg" alt="打手" onerror="this.src='https://via.placeholder.com/300x400/70cce1/ffffff?text=戰術指揮'">
                            <div class="booster-rank">大師</div>
                        </div>
                        <div class="booster-info">
                            <h3 class="booster-name">戰術指揮</h3>
                            <p class="booster-role">指揮官 / 支援手</p>
                            <div class="booster-stats">
                                <div class="stat">
                                    <span class="stat-label">KD</span>
                                    <span class="stat-value">2.9</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">場次</span>
                                    <span class="stat-value">2800</span>
                                </div>
                            </div>
                            <p class="booster-desc">戰術大師，精通地圖控制與團隊配合，帶領團隊走向勝利</p>
                            <button class="booster-btn" onclick="location.href='order.php'">預約打手</button>
                        </div>
                    </div>

                    <div class="booster-card">
                        <div class="booster-image">
                            <img src="images/booster4.jpg" alt="打手" onerror="this.src='https://via.placeholder.com/300x400/70cce1/ffffff?text=疾風刺客'">
                            <div class="booster-rank">頂尖</div>
                        </div>
                        <div class="booster-info">
                            <h3 class="booster-name">疾風刺客</h3>
                            <p class="booster-role">偵察兵 / 游擊手</p>
                            <div class="booster-stats">
                                <div class="stat">
                                    <span class="stat-label">KD</span>
                                    <span class="stat-value">4.1</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">場次</span>
                                    <span class="stat-value">2100</span>
                                </div>
                            </div>
                            <p class="booster-desc">移動迅速，擅長側翼攻擊與後排切入，敵方的夢魘</p>
                            <button class="booster-btn" onclick="location.href='order.php'">預約打手</button>
                        </div>
                    </div>

                    <div class="booster-card">
                        <div class="booster-image">
                            <img src="images/booster5.jpg" alt="打手" onerror="this.src='https://via.placeholder.com/300x400/70cce1/ffffff?text=火力壓制'">
                            <div class="booster-rank">大師</div>
                        </div>
                        <div class="booster-info">
                            <h3 class="booster-name">火力壓制</h3>
                            <p class="booster-role">機槍手 / 防守專家</p>
                            <div class="booster-stats">
                                <div class="stat">
                                    <span class="stat-label">KD</span>
                                    <span class="stat-value">3.2</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">場次</span>
                                    <span class="stat-value">2600</span>
                                </div>
                            </div>
                            <p class="booster-desc">重火力專家，擅長陣地防守與火力壓制，牢不可破的防線</p>
                            <button class="booster-btn" onclick="location.href='order.php'">預約打手</button>
                        </div>
                    </div>

                    <div class="booster-card">
                        <div class="booster-image">
                            <img src="images/booster6.jpg" alt="打手" onerror="this.src='https://via.placeholder.com/300x400/70cce1/ffffff?text=爆破專家'">
                            <div class="booster-rank">大師</div>
                        </div>
                        <div class="booster-info">
                            <h3 class="booster-name">爆破專家</h3>
                            <p class="booster-role">工程師 / 戰術專家</p>
                            <div class="booster-stats">
                                <div class="stat">
                                    <span class="stat-label">KD</span>
                                    <span class="stat-value">2.7</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">場次</span>
                                    <span class="stat-value">1900</span>
                                </div>
                            </div>
                            <p class="booster-desc">精通各種裝備與道具，擅長破解敵方防線與設置陷阱</p>
                            <button class="booster-btn" onclick="location.href='order.php'">預約打手</button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 打手說明 -->
        <section class="boosting-section" style="padding-top: 0;">
            <div class="container">
                <div class="boosting-notes">
                    <h4>打手服務說明</h4>
                    <ul>
                        <li>所有打手均經過嚴格考核，實戰經驗豐富</li>
                        <li>可指定特定打手陪玩，依打手等級收費不同</li>
                        <li>提供語音教學與戰術指導服務</li>
                        <li>支援三角洲、CS2、APEX、PUBG等主流射擊遊戲</li>
                        <li>KD比率為近期100場平均數據</li>
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
