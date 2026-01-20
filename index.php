<?php
require_once 'config/session.php';
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>迴響電競 - 專業電競陪玩與代練服務</title>
    <meta name="description" content="迴響電競提供專業的電競陪玩、代練服務，包含三角洲行動、絕地求生等熱門遊戲">
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
                    <li><a href="index.php" class="nav-link active">首頁</a></li>
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

        <!-- 英雄區域 -->
        <section class="hero-section">
            <div class="video-background">
                <video autoplay muted loop playsinline>
                    <source src="video/hero-video.mp4" type="video/mp4">
                </video>
            </div>
            <div class="video-overlay"></div>

            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="glitch">迴響電競</span>
                </h1>
                <p class="hero-subtitle">專業陪玩 · 極致體驗 · 品質保證</p>

                <div class="hero-buttons">
                    <a href="order.php" class="hero-btn primary">立即下單</a>
                    <a href="pricing.php" class="hero-btn secondary">查看價格</a>
                </div>
            </div>
        </section>

        <!-- 特色服務 -->
        <section class="features-section">
            <div class="container">
                <h2 class="section-title">我們的服務</h2>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🎮</div>
                        <h3>專業陪玩</h3>
                        <p>頂尖高手陪伴，提供最優質的遊戲體驗，讓您在遊戲中盡情享受樂趣</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">🏆</div>
                        <h3>代練上分</h3>
                        <p>專業代練團隊，快速高效完成段位提升，保證帳號安全</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">👑</div>
                        <h3>VIP 服務</h3>
                        <p>尊享 VIP 專屬服務，優先排隊，專屬客服，最佳體驗</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">📚</div>
                        <h3>技術指導</h3>
                        <p>專業高手一對一指導，提升個人技術水平，快速成長</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 為什麼選擇我們 -->
        <section class="features-section" style="background: var(--darker-bg);">
            <div class="container">
                <h2 class="section-title">為什麼選擇我們</h2>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3>快速響應</h3>
                        <p>24小時客服在線，快速回應您的需求</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">🛡️</div>
                        <h3>安全保障</h3>
                        <p>嚴格的資安保護，保證您的帳號安全</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3>價格透明</h3>
                        <p>明碼標價，無隱藏費用，讓您消費安心</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">✅</div>
                        <h3>品質保證</h3>
                        <p>專業團隊，提供最優質的服務體驗</p>
                    </div>
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
