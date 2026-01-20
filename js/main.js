// ===== EchoEsport 主要 JavaScript =====

// 頁面載入完成後執行
document.addEventListener('DOMContentLoaded', function() {
    // 初始化載入畫面
    initLoadingScreen();

    // 初始化導航欄
    initNavbar();

    // 初始化表單驗證
    initFormValidation();
});

// ===== 載入畫面控制 =====
function initLoadingScreen() {
    const loadingScreen = document.querySelector('.loading-screen');
    const mainContent = document.querySelector('.main-content');

    if (!loadingScreen) return;

    // 顯示主內容的函數
    function showMainContent() {
        loadingScreen.classList.add('hidden');
        if (mainContent) {
            mainContent.classList.add('visible');
        }

        // 完全移除載入畫面以避免影響頁面交互
        setTimeout(function() {
            loadingScreen.style.display = 'none';
        }, 500);
    }

    // 確保所有資源載入完成後再隱藏
    window.addEventListener('load', function() {
        setTimeout(showMainContent, 500);
    });

    // 如果頁面載入時間過長（超過3秒），強制隱藏載入畫面
    setTimeout(function() {
        if (!loadingScreen.classList.contains('hidden')) {
            console.log('Loading timeout - forcing display of main content');
            showMainContent();
        }
    }, 3000); // 改為 3 秒
}

// ===== 導航欄滾動效果 =====
function initNavbar() {
    const navbar = document.querySelector('.navbar');

    if (!navbar) return;

    let lastScrollTop = 0;
    let scrollTimeout;

    window.addEventListener('scroll', function() {
        // 使用節流來優化性能
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // 滾動超過 100px 時添加背景
            if (scrollTop > 100) {
                navbar.style.background = 'rgba(10, 14, 26, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
                navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.5)';
            } else {
                navbar.style.background = 'linear-gradient(180deg, rgba(10, 14, 26, 0.9) 0%, rgba(10, 14, 26, 0.7) 100%)';
                navbar.style.backdropFilter = 'blur(5px)';
                navbar.style.boxShadow = 'none';
            }

            lastScrollTop = scrollTop;
        }, 10);
    });

    // 用戶選單下拉效果
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        const userBtn = userMenu.querySelector('.nav-btn');
        const userDropdown = userMenu.querySelector('.user-dropdown');

        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            // 點擊外部關閉下拉選單
            document.addEventListener('click', function(e) {
                if (!userMenu.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }
    }

    // 行動版選單切換（如果需要）
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
        });
    }
}

// ===== 表單驗證 =====
function initFormValidation() {
    // Email 格式驗證
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            validateEmail(this);
        });
    });

    // 密碼強度檢查
    const passwordInputs = document.querySelectorAll('input[type="password"][name="password"]');
    passwordInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            checkPasswordStrength(this);
        });
    });

    // 確認密碼匹配檢查
    const confirmPasswordInputs = document.querySelectorAll('input[name="confirm_password"], input[name="confirm_new_password"]');
    confirmPasswordInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const passwordInput = document.querySelector('input[name="password"], input[name="new_password"]');
            if (passwordInput) {
                checkPasswordMatch(passwordInput, this);
            }
        });
    });

    // 電話號碼格式驗證
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            validatePhone(this);
        });
    });
}

// Email 驗證
function validateEmail(input) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isValid = emailPattern.test(input.value);

    if (input.value && !isValid) {
        input.classList.add('error');
        showFieldError(input, 'Email 格式不正確');
        return false;
    } else {
        input.classList.remove('error');
        removeFieldError(input);
        return true;
    }
}

// 密碼強度檢查
function checkPasswordStrength(input) {
    const password = input.value;
    let strength = 0;
    let message = '';

    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    // 移除舊的強度提示
    const existingHint = input.parentElement.querySelector('.password-strength');
    if (existingHint) existingHint.remove();

    if (password.length > 0) {
        const hint = document.createElement('div');
        hint.className = 'password-strength';
        hint.style.marginTop = '5px';
        hint.style.fontSize = '0.85rem';

        if (strength <= 2) {
            message = '密碼強度：弱';
            hint.style.color = '#f44336';
        } else if (strength <= 3) {
            message = '密碼強度：中';
            hint.style.color = '#ff9800';
        } else {
            message = '密碼強度：強';
            hint.style.color = '#4caf50';
        }

        hint.textContent = message;
        input.parentElement.appendChild(hint);
    }

    return strength;
}

// 確認密碼匹配
function checkPasswordMatch(passwordInput, confirmInput) {
    if (confirmInput.value && passwordInput.value !== confirmInput.value) {
        confirmInput.classList.add('error');
        showFieldError(confirmInput, '密碼不符合');
        return false;
    } else {
        confirmInput.classList.remove('error');
        removeFieldError(confirmInput);
        return true;
    }
}

// 電話號碼驗證
function validatePhone(input) {
    if (!input.value) return true; // 電話號碼為選填

    // 台灣電話號碼格式（簡單驗證）
    const phonePattern = /^[0-9+\-\s()]{8,}$/;
    const isValid = phonePattern.test(input.value);

    if (!isValid) {
        input.classList.add('error');
        showFieldError(input, '電話號碼格式不正確');
        return false;
    } else {
        input.classList.remove('error');
        removeFieldError(input);
        return true;
    }
}

// 顯示欄位錯誤訊息
function showFieldError(input, message) {
    removeFieldError(input); // 先移除舊的錯誤訊息

    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.color = '#f44336';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;

    input.parentElement.appendChild(errorDiv);
}

// 移除欄位錯誤訊息
function removeFieldError(input) {
    const errorDiv = input.parentElement.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// ===== 平滑滾動 =====
function smoothScrollTo(targetPosition, duration = 800) {
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    let startTime = null;

    function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        const timeElapsed = currentTime - startTime;
        const run = ease(timeElapsed, startPosition, distance, duration);
        window.scrollTo(0, run);
        if (timeElapsed < duration) requestAnimationFrame(animation);
    }

    function ease(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    }

    requestAnimationFrame(animation);
}

// 錨點平滑滾動
document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#') return;

        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            const targetPosition = target.offsetTop - 80; // 減去導航欄高度
            smoothScrollTo(targetPosition);
        }
    });
});

// ===== 工具函數 =====

// 格式化數字（加入千分位逗號）
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// 取得 URL 參數
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

// 複製到剪貼簿
function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            showNotification('已複製到剪貼簿', 'success');
        }).catch(function(err) {
            console.error('複製失敗:', err);
        });
    } else {
        // 備用方法
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showNotification('已複製到剪貼簿', 'success');
        } catch (err) {
            console.error('複製失敗:', err);
        }
        document.body.removeChild(textarea);
    }
}

// 顯示通知訊息
function showNotification(message, type = 'info', duration = 3000) {
    // 移除現有通知
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // 建立新通知
    const notification = document.createElement('div');
    notification.className = 'notification notification-' + type;
    notification.textContent = message;

    // 樣式
    Object.assign(notification.style, {
        position: 'fixed',
        top: '100px',
        right: '20px',
        padding: '15px 25px',
        borderRadius: '8px',
        boxShadow: '0 5px 20px rgba(0, 0, 0, 0.3)',
        zIndex: '9999',
        animation: 'slideInRight 0.3s ease',
        fontWeight: '500'
    });

    // 根據類型設置顏色
    if (type === 'success') {
        notification.style.background = 'rgba(76, 175, 80, 0.9)';
        notification.style.color = 'white';
    } else if (type === 'error') {
        notification.style.background = 'rgba(244, 67, 54, 0.9)';
        notification.style.color = 'white';
    } else {
        notification.style.background = 'rgba(112, 204, 225, 0.9)';
        notification.style.color = 'white';
    }

    document.body.appendChild(notification);

    // 自動移除
    setTimeout(function() {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(function() {
            notification.remove();
        }, 300);
    }, duration);
}

// 添加動畫 keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .user-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 10px;
        background: var(--card-bg);
        border: 1px solid rgba(112, 204, 225, 0.2);
        border-radius: 8px;
        padding: 10px 0;
        min-width: 180px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        z-index: 1000;
    }

    .user-dropdown.show {
        display: block;
    }

    .user-dropdown a {
        display: block;
        padding: 10px 20px;
        color: var(--text-primary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .user-dropdown a:hover {
        background: rgba(112, 204, 225, 0.1);
        color: var(--primary-color);
    }

    .user-menu {
        position: relative;
    }
`;
document.head.appendChild(style);

// ===== 防止表單重複提交 =====
document.addEventListener('submit', function(e) {
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');

    if (submitBtn && !submitBtn.disabled) {
        // 短暫延遲後停用按鈕，避免阻止正常提交
        setTimeout(function() {
            submitBtn.disabled = true;
        }, 100);

        // 5秒後重新啟用（以防提交失敗）
        setTimeout(function() {
            submitBtn.disabled = false;
        }, 5000);
    }
});

// 匯出函數供全域使用
window.EchoEsport = {
    formatNumber: formatNumber,
    getUrlParameter: getUrlParameter,
    copyToClipboard: copyToClipboard,
    showNotification: showNotification,
    smoothScrollTo: smoothScrollTo
};
