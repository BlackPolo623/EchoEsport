-- EchoEsport 資料庫結構 (Heroku 版本)
-- 適用於 Heroku JawsDB MySQL
-- 注意：移除了 CREATE DATABASE 和 USE 語句

-- 會員表
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    discord_id VARCHAR(100),
    line_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active TINYINT(1) DEFAULT 1,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 管理員表
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 訂單表
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NULL,
    guest_email VARCHAR(100) NULL,
    service_type ENUM('companion_play', 'boost', 'vip', 'training') NOT NULL,
    server_type ENUM('taiwan', 'china') DEFAULT 'taiwan',
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('credit', 'atm', 'cvs') NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    order_status ENUM('new', 'processing', 'completed', 'cancelled') DEFAULT 'new',
    trade_no VARCHAR(100) NULL,
    bank_code VARCHAR(10) NULL,
    virtual_account VARCHAR(50) NULL,
    payment_date TIMESTAMP NULL,
    expire_date VARCHAR(20) NULL,
    description TEXT,
    customer_notes TEXT,
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order_number (order_number),
    INDEX idx_user_id (user_id),
    INDEX idx_payment_status (payment_status),
    INDEX idx_order_status (order_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 交易記錄表
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    transaction_id VARCHAR(100) NOT NULL UNIQUE,
    trade_no VARCHAR(100),
    amount DECIMAL(10, 2) NOT NULL,
    payment_type VARCHAR(50) NOT NULL,
    payment_date TIMESTAMP NULL,
    status VARCHAR(20) NOT NULL,
    rtn_code VARCHAR(10),
    rtn_msg TEXT,
    raw_data TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id),
    INDEX idx_transaction_id (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 活動記錄表
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('user', 'admin', 'system') NOT NULL,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_type (user_type),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 網站設定表
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type VARCHAR(20) DEFAULT 'string',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 插入預設管理員帳號 (密碼: admin123)
INSERT INTO admins (username, password, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@echoesport.com', 'super_admin');

-- 插入預設網站設定
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', '迴響電競', 'string', '網站名稱'),
('site_email', 'contact@echoesport.com', 'string', '聯絡信箱'),
('maintenance_mode', '0', 'boolean', '維護模式'),
('registration_enabled', '1', 'boolean', '允許註冊'),
('funpoint_merchant_id', '1020977', 'string', '歐買尬商店代號'),
('funpoint_hash_key', 'cUHKRU04BaDCprxJ', 'string', '歐買尬 HashKey'),
('funpoint_hash_iv', 'tpYEKUQ8D57JyDo0', 'string', '歐買尬 HashIV');
