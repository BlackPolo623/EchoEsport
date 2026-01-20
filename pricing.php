<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>價目表 - 迴響電競</title>
    <meta name="description" content="迴響電競透明公開的價格，專業優質的服務，台灣服與大陸服價格一覽">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pricing-toggle.css">
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
                    <li><a href="pricing.php" class="nav-link active">價目表</a></li>
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
                <h1 class="page-title">服務價目表</h1>
                <p class="page-subtitle">透明公開的價格，專業優質的服務</p>

                <!-- iOS 風格伺服器切換開關 -->
                <div class="server-toggle-wrapper">
                    <span class="server-label active" id="labelTW">台灣服</span>
                    <div class="server-toggle">
                        <input type="checkbox" id="serverCheckbox" />
                        <label for="serverCheckbox" class="toggle-switch">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <span class="server-label" id="labelCN">大陸服</span>
                </div>
            </div>
        </section>

        <!-- ========== 台灣服內容 ========== -->
        <div id="taiwanContent" class="server-content active">
            <!-- 價格方案區域 - 台灣服 -->
            <section class="pricing-section">
                <div class="container">
                    <h2 class="section-title">陪玩服務 <span class="server-badge tw">台灣服</span></h2>
                    <div class="pricing-grid">
                        <div class="pricing-card">
                            <div class="pricing-header">
                                <h3>娛樂陪玩</h3>
                                <div class="pricing-badge">熱門</div>
                            </div>
                            <div class="pricing-price">
                                <span class="price-amount">$300</span>
                                <span class="price-unit">/小時</span>
                            </div>
                            <ul class="pricing-features">
                                <li>✓ 提供多種互動風格，可依需求調整陪玩節奏</li>
                                <li>✓ 全程語音溝通，確保良好遊戲氛圍</li>
                                <li>✓ 休閒、輕鬆陪玩支援</li>
                                <li>✓ 可提供部分遊戲經驗分享與基礎教學</li>
                                <li>✗ 不提供固定專屬綁定服務</li>
                                <li>✗ 盈利與數據依平台與實際遊玩為準</li>
                            </ul>
                            <button class="pricing-btn" onclick="location.href='order.php'">立即預約</button>
                        </div>

                        <div class="pricing-card featured">
                            <div class="pricing-header">
                                <h3>技術陪玩</h3>
                                <div class="pricing-badge recommend">推薦</div>
                            </div>
                            <div class="pricing-price">
                                <span class="price-amount">$350</span>
                                <span class="price-unit">/小時</span>
                            </div>
                            <ul class="pricing-features">
                                <li>✓ 迴響電競頂級打手</li>
                                <li>✓ 語音陪玩互動</li>
                                <li>✓ 專業技術指導</li>
                                <li>✓ 遊戲心態調整</li>
                                <li>✓ 專屬打手指定</li>
                                <li>✗ 哈夫幣盈利保證</li>
                            </ul>
                            <button class="pricing-btn featured-btn" onclick="location.href='order.php'">立即預約</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 體驗單區域 - 台灣服 -->
            <section class="boosting-section">
                <div class="container">
                    <h2 class="section-title">體驗單 <span class="server-badge tw">台灣服</span></h2>
                    <div class="boosting-table-wrapper">
                        <table class="boosting-table">
                            <thead>
                                <tr>
                                    <th>累計保底金額</th>
                                    <th>價格</th>
                                    <th>安排時間</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>台服累計388萬</td>
                                    <td>$300</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="boosting-notes">
                        <h4>台灣服務說明</h4>
                        <ul>
                            <li>全程不使用外掛、不做任何違規操作</li>
                            <li>代練期間不更改帳號資料、好友或聊天設定</li>
                            <li>若需暫離或停單會提前告知</li>
                            <li>老闆登入帳號需事先通知，避免影響進度</li>
                            <li>接急單、趕進度可另議</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 護航單區域 - 台灣服 -->
            <section class="boosting-section">
                <div class="container">
                    <h2 class="section-title">護航單 <span class="server-badge tw">台灣服</span></h2>
                    <div class="boosting-table-wrapper">
                        <table class="boosting-table">
                            <thead>
                                <tr>
                                    <th>護航保底金額</th>
                                    <th>價格</th>
                                    <th>安排時間</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>台服累計438萬</td>
                                    <td>$400</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr>
                                    <td>台服累計688萬</td>
                                    <td>$600</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr>
                                    <td>台服累計1088萬</td>
                                    <td>$900</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="boosting-notes">
                        <h4>台灣服務說明</h4>
                        <ul>
                            <li>全程不使用外掛、不做任何違規操作</li>
                            <li>代練期間不更改帳號資料、好友或聊天設定</li>
                            <li>若需暫離或停單會提前告知</li>
                            <li>老闆登入帳號需事先通知，避免影響進度</li>
                            <li>接急單、趕進度可另議</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 賭約單區域 - 台灣服 -->
            <section class="boosting-section">
                <div class="container">
                    <h2 class="section-title">賭約單 <span class="server-badge tw">台灣服</span></h2>
                    <div class="boosting-table-wrapper">
                        <table class="boosting-table">
                            <thead>
                                <tr>
                                    <th>賭約保底金額</th>
                                    <th>價格</th>
                                    <th>安排時間</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>台服單局528萬</td>
                                    <td>$600</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr>
                                    <td>台服單局648萬</td>
                                    <td>$1,000</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr>
                                    <td>台服單局768萬</td>
                                    <td>$1,500</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="boosting-notes">
                        <h4>台灣服務說明</h4>
                        <ul>
                            <li>全程不使用外掛、不做任何違規操作</li>
                            <li>代練期間不更改帳號資料、好友或聊天設定</li>
                            <li>若需暫離或停單會提前告知</li>
                            <li>老闆登入帳號需事先通知，避免影響進度</li>
                            <li>接急單、趕進度可另議</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>

        <!-- ========== 大陸服內容 ========== -->
        <div id="chinaContent" class="server-content">
            <!-- 價格方案區域 - 大陸服 -->
            <section class="pricing-section">
                <div class="container">
                    <h2 class="section-title">陪玩服務 <span class="server-badge cn">大陸服</span></h2>
                    <div class="pricing-grid">
                        <div class="pricing-card">
                            <div class="pricing-header">
                                <h3>娛樂陪玩</h3>
                                <div class="pricing-badge">熱門</div>
                            </div>
                            <div class="pricing-price">
                                <span class="price-amount">$350</span>
                                <span class="price-unit">/小時</span>
                            </div>
                            <ul class="pricing-features">
                                <li>✓ 提供多種互動風格，可依需求調整陪玩節奏</li>
                                <li>✓ 全程語音溝通，確保良好遊戲氛圍</li>
                                <li>✓ 休閒、輕鬆陪玩支援</li>
                                <li>✓ 可提供部分遊戲經驗分享與基礎教學</li>
                                <li>✗ 不提供固定專屬綁定服務</li>
                                <li>✗ 盈利與數據依平台與實際遊玩為準</li>
                            </ul>
                            <button class="pricing-btn" onclick="location.href='order.php'">立即預約</button>
                        </div>

                        <div class="pricing-card featured">
                            <div class="pricing-header">
                                <h3>技術陪玩</h3>
                                <div class="pricing-badge recommend">推薦</div>
                            </div>
                            <div class="pricing-price">
                                <span class="price-amount">$400</span>
                                <span class="price-unit">/小時</span>
                            </div>
                            <ul class="pricing-features">
                                <li>✓ 迴響電競頂級打手</li>
                                <li>✓ 語音陪玩互動</li>
                                <li>✓ 專業技術指導</li>
                                <li>✓ 遊戲心態調整</li>
                                <li>✓ 專屬打手指定</li>
                                <li>✗ 哈夫幣盈利保證</li>
                            </ul>
                            <button class="pricing-btn featured-btn" onclick="location.href='order.php'">立即預約</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 體驗單區域 - 大陸服 -->
            <section class="boosting-section">
                <div class="container">
                    <h2 class="section-title">體驗單 <span class="server-badge cn">大陸服</span></h2>
                    <div class="boosting-table-wrapper">
                        <table class="boosting-table">
                            <thead>
                                <tr>
                                    <th>累計保底金額</th>
                                    <th>價格</th>
                                    <th>安排時間</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>陸服累計428萬</td>
                                    <td>$588</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="boosting-notes">
                        <h4>大陸服務說明</h4>
                        <ul>
                            <li>全程不使用外掛、不做任何違規操作</li>
                            <li>代練期間不更改帳號資料、好友或聊天設定</li>
                            <li>若需暫離或停單會提前告知</li>
                            <li>老闆登入帳號需事先通知，避免影響進度</li>
                            <li>接急單、趕進度可另議</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 護航單區域 - 大陸服 -->
            <section class="boosting-section">
                <div class="container">
                    <h2 class="section-title">護航單 <span class="server-badge cn">大陸服</span></h2>
                    <div class="boosting-table-wrapper">
                        <table class="boosting-table">
                            <thead>
                                <tr>
                                    <th>護航保底金額</th>
                                    <th>價格</th>
                                    <th>安排時間</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>陸服累計700萬</td>
                                    <td>$1,088</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr>
                                    <td>陸服累計1300萬</td>
                                    <td>$1,800</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr class="highlight-row">
                                    <td>陸服累計2500萬</td>
                                    <td>$3,000</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="boosting-notes">
                        <h4>大陸服務說明</h4>
                        <ul>
                            <li>全程不使用外掛、不做任何違規操作</li>
                            <li>代練期間不更改帳號資料、好友或聊天設定</li>
                            <li>若需暫離或停單會提前告知</li>
                            <li>老闆登入帳號需事先通知，避免影響進度</li>
                            <li>接急單、趕進度可另議</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 賭約單區域 - 大陸服 -->
            <section class="boosting-section">
                <div class="container">
                    <h2 class="section-title">賭約單 <span class="server-badge cn">大陸服</span></h2>
                    <div class="boosting-table-wrapper">
                        <table class="boosting-table">
                            <thead>
                                <tr>
                                    <th>賭約保底金額</th>
                                    <th>價格</th>
                                    <th>安排時間</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>陸服單局788萬</td>
                                    <td>$1,500</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr>
                                    <td>陸服單局900萬</td>
                                    <td>$1,850</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr class="highlight-row">
                                    <td>陸服單局1000萬</td>
                                    <td>$2,500</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                                <tr class="highlight-row">
                                    <td>陸服單局1100萬</td>
                                    <td>$3,250</td>
                                    <td>24小時內</td>
                                    <td><button class="table-btn" onclick="location.href='order.php'">選擇</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="boosting-notes">
                        <h4>大陸服務說明</h4>
                        <ul>
                            <li>全程不使用外掛、不做任何違規操作</li>
                            <li>代練期間不更改帳號資料、好友或聊天設定</li>
                            <li>若需暫離或停單會提前告知</li>
                            <li>老闆登入帳號需事先通知，避免影響進度</li>
                            <li>接急單、趕進度可另議</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>

        <!-- 常見問題區域 -->
        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">常見問題</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>如何預約服務？</h4>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>您可以透過網站上的「立即下單」按鈕進行預約。我們的客服團隊會在最短時間內與您聯繫，安排適合的打手。</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>付款方式有哪些？</h4>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>我們支援多種付款方式，包括ATM轉帳、無卡轉帳、街口、LINEPAY等。</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>服務時間是？</h4>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>我們提供24小時全天候服務。您可以根據自己的時間安排預約打手，我們會盡力配合您的需求。</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>代練期間帳號安全嗎？</h4>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>我們非常重視帳號安全。所有打手都經過嚴格篩選和背景調查，且代練期間會簽訂保密協議。如有任何帳號安全問題，我們將負全責。</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>如果不滿意服務怎麼辦？</h4>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>我們提供100%滿意保證。如果您對服務不滿意，可以聯繫客服更換打手(陪玩)，如出現連續炸單情況可選擇更換打手或全額退款以確保服務品質。客戶滿意是我們的首要目標。</p>
                        </div>
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
    <script src="js/pricing-toggle.js"></script>
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
