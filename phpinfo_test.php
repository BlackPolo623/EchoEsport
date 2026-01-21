<?php
/**
 * PHP 環境診斷頁面
 */
header('Content-Type: text/plain; charset=utf-8');

echo "=== PHP Environment Diagnostics ===\n\n";

// PHP 版本
echo "PHP Version: " . phpversion() . "\n\n";

// 檢查 PDO
echo "PDO Available: " . (extension_loaded('PDO') ? 'YES ✓' : 'NO ✗') . "\n";
echo "PDO MySQL Available: " . (extension_loaded('pdo_mysql') ? 'YES ✓' : 'NO ✗') . "\n";
echo "MySQLi Available: " . (extension_loaded('mysqli') ? 'YES ✓' : 'NO ✗') . "\n\n";

// 列出所有已載入的擴展
echo "=== Loaded Extensions ===\n";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    echo "  - $ext\n";
}

// PDO 驅動程式
echo "\n=== PDO Drivers ===\n";
if (extension_loaded('PDO')) {
    $drivers = PDO::getAvailableDrivers();
    if (empty($drivers)) {
        echo "  No PDO drivers available!\n";
    } else {
        foreach ($drivers as $driver) {
            echo "  - $driver\n";
        }
    }
} else {
    echo "  PDO extension not loaded!\n";
}

// 環境變數
echo "\n=== MySQL Environment Variables ===\n";
$vars = ['MYSQL_URL', 'MYSQLDATABASE', 'MYSQLHOST', 'MYSQLPORT', 'MYSQLUSER', 'MYSQLPASSWORD'];
foreach ($vars as $var) {
    $value = getenv($var);
    if ($var === 'MYSQLPASSWORD' || $var === 'MYSQL_URL') {
        echo "$var: " . ($value ? 'SET (hidden)' : 'NOT SET') . "\n";
    } else {
        echo "$var: " . ($value ?: 'NOT SET') . "\n";
    }
}

echo "\n=== End of Diagnostics ===\n";
