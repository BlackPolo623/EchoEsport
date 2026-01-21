<?php
/**
 * 資料庫配置檔案
 * 支援本地開發環境、Railway 和 Heroku 生產環境
 */

class Database {
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // 檢查是否在 Railway 環境
            if (getenv('MYSQL_URL')) {
                // Railway MySQL (格式: mysql://user:password@host:port/database)
                $url = parse_url(getenv('MYSQL_URL'));
                $host = $url['host'];
                $username = $url['user'];
                $password = $url['pass'];
                $database = substr($url['path'], 1);
                $port = isset($url['port']) ? $url['port'] : 3306;
            } elseif (getenv('MYSQLDATABASE')) {
                // Railway MySQL (分開的環境變數)
                $host = getenv('MYSQLHOST');
                $username = getenv('MYSQLUSER');
                $password = getenv('MYSQLPASSWORD');
                $database = getenv('MYSQLDATABASE');
                $port = getenv('MYSQLPORT') ?: 3306;
            } elseif (getenv('JAWSDB_URL')) {
                // Heroku JawsDB MySQL (向下相容)
                $url = parse_url(getenv('JAWSDB_URL'));
                $host = $url['host'];
                $username = $url['user'];
                $password = $url['pass'];
                $database = substr($url['path'], 1);
                $port = isset($url['port']) ? $url['port'] : 3306;
            } elseif (getenv('CLEARDB_DATABASE_URL')) {
                // Heroku ClearDB MySQL (向下相容)
                $url = parse_url(getenv('CLEARDB_DATABASE_URL'));
                $host = $url['host'];
                $username = $url['user'];
                $password = $url['pass'];
                $database = substr($url['path'], 1);
                $port = isset($url['port']) ? $url['port'] : 3306;
            } else {
                // 本地開發環境
                $host = '1.169.239.100';
                $username = 'echoesport';
                $password = 'admin';
                $database = 'echoesport';
                $port = 3306;
            }

            $this->conn = new PDO(
                "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
                $username,
                $password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
