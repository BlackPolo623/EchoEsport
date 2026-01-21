<?php
echo "<h1>Database Connection Test</h1>";

echo "<h2>Environment Variables:</h2>";
echo "<pre>";
echo "MYSQL_URL: " . (getenv('MYSQL_URL') ? 'EXISTS' : 'NOT SET') . "\n";
echo "MYSQLDATABASE: " . (getenv('MYSQLDATABASE') ? getenv('MYSQLDATABASE') : 'NOT SET') . "\n";
echo "MYSQLHOST: " . (getenv('MYSQLHOST') ? getenv('MYSQLHOST') : 'NOT SET') . "\n";
echo "MYSQLPORT: " . (getenv('MYSQLPORT') ? getenv('MYSQLPORT') : 'NOT SET') . "\n";
echo "MYSQLUSER: " . (getenv('MYSQLUSER') ? getenv('MYSQLUSER') : 'NOT SET') . "\n";
echo "MYSQLPASSWORD: " . (getenv('MYSQLPASSWORD') ? 'EXISTS' : 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>Database Connection Test:</h2>";
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    if ($db) {
        echo "<p style='color: green;'>✅ Database connection successful!</p>";

        // Test query
        $query = "SHOW TABLES";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo "<h3>Tables in database:</h3>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";

        // Test admin table
        $query = "SELECT COUNT(*) as count FROM admins";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        echo "<p>Admins count: " . $result['count'] . "</p>";

    } else {
        echo "<p style='color: red;'>❌ Database connection failed!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
