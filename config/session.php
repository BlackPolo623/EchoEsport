<?php
/**
 * Session 配置 - Heroku 相容
 */

// 設定 session 儲存路徑
if (getenv('DYNO')) {
    // Heroku 環境
    $sessionPath = sys_get_temp_dir();
    if (!is_writable($sessionPath)) {
        $sessionPath = '/tmp';
    }
    session_save_path($sessionPath);
}

// 設定 session 參數
ini_set('session.gc_maxlifetime', 7200); // 2 小時
ini_set('session.cookie_lifetime', 0); // 瀏覽器關閉時清除
ini_set('session.cookie_httponly', 1); // 防止 XSS
ini_set('session.use_strict_mode', 1); // 嚴格模式

// 啟動 session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
