// ===== 伺服器切換功能 =====
(function() {
    'use strict';

    // DOM 元素
    const serverCheckbox = document.getElementById('serverCheckbox');
    const labelTW = document.getElementById('labelTW');
    const labelCN = document.getElementById('labelCN');
    const taiwanContent = document.getElementById('taiwanContent');
    const chinaContent = document.getElementById('chinaContent');

    // 當前伺服器狀態（false = 台灣服, true = 大陸服）
    let currentServer = 'taiwan';

    // 從 localStorage 讀取用戶上次選擇的伺服器
    function loadServerPreference() {
        const savedServer = localStorage.getItem('preferredServer');
        if (savedServer === 'china') {
            serverCheckbox.checked = true;
            switchToServer('china', false);
        } else {
            serverCheckbox.checked = false;
            switchToServer('taiwan', false);
        }
    }

    // 保存伺服器選擇到 localStorage
    function saveServerPreference(server) {
        localStorage.setItem('preferredServer', server);
    }

    // 切換伺服器
    function switchToServer(server, animated = true) {
        // 如果已經是當前伺服器，不做任何操作
        if (currentServer === server) return;

        currentServer = server;

        if (server === 'china') {
            // 切換到大陸服
            if (animated) {
                // 淡出台灣服內容
                taiwanContent.classList.add('switching-out');

                setTimeout(() => {
                    // 移除台灣服的 active 和 switching-out
                    taiwanContent.classList.remove('active', 'switching-out');

                    // 確保移除所有可能的類
                    setTimeout(() => {
                        chinaContent.classList.add('active');

                        // 滾動到頁面頂部（平滑滾動）
                        smoothScrollToTop();
                    }, 50);
                }, 400);
            } else {
                taiwanContent.classList.remove('active', 'switching-out');
                chinaContent.classList.add('active');
            }

            // 更新標籤狀態
            labelTW.classList.remove('active');
            labelCN.classList.add('active');

            // 保存偏好
            saveServerPreference('china');

        } else {
            // 切換到台灣服
            if (animated) {
                // 淡出大陸服內容
                chinaContent.classList.add('switching-out');

                setTimeout(() => {
                    // 移除大陸服的 active 和 switching-out
                    chinaContent.classList.remove('active', 'switching-out');

                    // 確保移除所有可能的類
                    setTimeout(() => {
                        taiwanContent.classList.add('active');

                        // 滾動到頁面頂部（平滑滾動）
                        smoothScrollToTop();
                    }, 50);
                }, 400);
            } else {
                chinaContent.classList.remove('active', 'switching-out');
                taiwanContent.classList.add('active');
            }

            // 更新標籤狀態
            labelCN.classList.remove('active');
            labelTW.classList.add('active');

            // 保存偏好
            saveServerPreference('taiwan');
        }

        // 觸發自定義事件（可用於分析或其他功能）
        const event = new CustomEvent('serverChanged', {
            detail: { server: server }
        });
        document.dispatchEvent(event);

        // 顯示提示訊息（可選）
        if (animated) {
            showSwitchNotification(server);
        }
    }

    // 平滑滾動到頁面頂部
    function smoothScrollToTop() {
        const navbar = document.querySelector('.navbar');
        const targetPosition = navbar ? navbar.offsetHeight : 0;

        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    }

    // 顯示切換通知
    function showSwitchNotification(server) {
        // 移除之前的通知
        const existingNotification = document.querySelector('.server-switch-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // 創建新通知
        const notification = document.createElement('div');
        notification.className = 'server-switch-notification';
        notification.textContent = server === 'china' ? '已切換至大陸服' : '已切換至台灣服';

        // 添加樣式
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: ${server === 'china' ?
                'linear-gradient(135deg, #dc2626, #ef4444)' :
                'linear-gradient(135deg, rgb(112, 204, 225), rgb(176, 184, 200))'};
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            animation: slideInRight 0.5s ease, fadeOut 0.5s ease 2.5s forwards;
            pointer-events: none;
        `;

        document.body.appendChild(notification);

        // 3秒後自動移除
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Checkbox 變更事件
    if (serverCheckbox) {
        serverCheckbox.addEventListener('change', function() {
            if (this.checked) {
                switchToServer('china', true);
            } else {
                switchToServer('taiwan', true);
            }
        });
    }

    // 點擊標籤也可以切換
    if (labelTW) {
        labelTW.addEventListener('click', function() {
            if (currentServer !== 'taiwan') {
                serverCheckbox.checked = false;
                switchToServer('taiwan', true);
            }
        });
    }

    if (labelCN) {
        labelCN.addEventListener('click', function() {
            if (currentServer !== 'china') {
                serverCheckbox.checked = true;
                switchToServer('china', true);
            }
        });
    }

    // 鍵盤快捷鍵（可選）- 按 Tab 切換伺服器
    document.addEventListener('keydown', function(e) {
        // 只在沒有輸入框獲得焦點時才響應
        if (e.key === 'Tab' && !e.target.matches('input, textarea')) {
            e.preventDefault();
            serverCheckbox.checked = !serverCheckbox.checked;
            switchToServer(serverCheckbox.checked ? 'china' : 'taiwan', true);
        }
    });

    // 添加動畫樣式到 head
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .server-switch-notification {
            letter-spacing: 0.5px;
        }
    `;
    document.head.appendChild(style);

    // 頁面載入時讀取偏好設置
    document.addEventListener('DOMContentLoaded', function() {
        loadServerPreference();

        // 在控制台輸出當前伺服器
        console.log('%c伺服器切換系統已啟動', 'color: rgb(112, 204, 225); font-weight: bold; font-size: 14px;');
        console.log('當前伺服器:', currentServer === 'taiwan' ? '台灣服' : '大陸服');
    });

    // 監聽自定義事件（用於調試或擴展功能）
    document.addEventListener('serverChanged', function(e) {
        console.log('伺服器已切換至:', e.detail.server === 'taiwan' ? '台灣服' : '大陸服');

        // 這裡可以添加 Google Analytics 追蹤或其他分析代碼
        // 例如: gtag('event', 'server_switch', { server: e.detail.server });
    });

    // 防止頁面刷新時閃爍
    window.addEventListener('load', function() {
        // 確保正確的內容顯示
        if (currentServer === 'taiwan') {
            taiwanContent.classList.add('active');
            chinaContent.classList.remove('active');
        } else {
            taiwanContent.classList.remove('active');
            chinaContent.classList.add('active');
        }
    });

    // URL 參數支援（可選）- 例如 ?server=china
    const urlParams = new URLSearchParams(window.location.search);
    const serverParam = urlParams.get('server');
    if (serverParam === 'china' || serverParam === 'taiwan') {
        serverCheckbox.checked = serverParam === 'china';
        switchToServer(serverParam, false);
    }

})();

// ===== 輔助函數：取得當前伺服器 =====
function getCurrentServer() {
    return localStorage.getItem('preferredServer') || 'taiwan';
}

// ===== 輔助函數：程式化切換伺服器 =====
function setServer(server) {
    const serverCheckbox = document.getElementById('serverCheckbox');
    if (serverCheckbox) {
        serverCheckbox.checked = server === 'china';
        const event = new Event('change');
        serverCheckbox.dispatchEvent(event);
    }
}

// 將函數暴露到全局，方便其他腳本調用
window.serverToggle = {
    getCurrentServer: getCurrentServer,
    setServer: setServer
};
