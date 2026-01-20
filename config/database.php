<?php
/**
 * 資料庫配置檔案
 * 支援本地開發環境和 Heroku 生產環境
 */

class Database {
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // 檢查是否在 Heroku 環境
            if (getenv('JAWSDB_URL')) {
                // Heroku JawsDB MySQL
                $url = parse_url(getenv('JAWSDB_URL'));
                $host = $url['host'];
                $username = $url['user'];
                $password = $url['pass'];
                $database = substr($url['path'], 1);
                $port = isset($url['port']) ? $url['port'] : 3306;
            } elseif (getenv('CLEARDB_DATABASE_URL')) {
                // Heroku ClearDB MySQL
                $url = parse_url(getenv('CLEARDB_DATABASE_URL'));
                $host = $url['host'];
                $username = $url['user'];
                $password = $url['pass'];
                $database = substr($url['path'], 1);
                $port = isset($url['port']) ? $url['port'] : 3306;
            } else {
                // 本地開發環境
                $host = 'localhost';
                $username = 'root';
                $password = '';
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
