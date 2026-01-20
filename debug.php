<?php
// 顯示所有錯誤
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Info</h1>";

// 檢查資料庫連線
echo "<h2>1. Checking Database Connection</h2>";
try {
    require_once 'config/database.php';
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        echo "✅ Database connected successfully!<br>";

        // 測試查詢
        $stmt = $conn->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo "<h3>Tables found:</h3>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    } else {
        echo "❌ Database connection failed!<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// 檢查環境變數
echo "<h2>2. Environment Variables</h2>";
if (getenv('JAWSDB_URL')) {
    echo "✅ JAWSDB_URL exists<br>";
    $url = parse_url(getenv('JAWSDB_URL'));
    echo "Host: " . $url['host'] . "<br>";
    echo "Database: " . substr($url['path'], 1) . "<br>";
} else {
    echo "❌ JAWSDB_URL not found<br>";
}

// 檢查 PHP 版本
echo "<h2>3. PHP Info</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PDO: " . (extension_loaded('pdo') ? '✅ Loaded' : '❌ Not loaded') . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Loaded' : '❌ Not loaded') . "<br>";

// 檢查檔案結構
echo "<h2>4. File Structure</h2>";
echo "config/database.php: " . (file_exists('config/database.php') ? '✅ Exists' : '❌ Not found') . "<br>";
echo "index.php: " . (file_exists('index.php') ? '✅ Exists' : '❌ Not found') . "<br>";
echo "css/style.css: " . (file_exists('css/style.css') ? '✅ Exists' : '❌ Not found') . "<br>";
