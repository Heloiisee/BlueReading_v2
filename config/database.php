<?php
/**
 * 
 * @author Heloïse
 * @package config
 * @version 1.0.0
 * Configuration de la base de données
 * 
 */
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
ini_set('error_log', 'C:/wamp64/logs/php_error.log');

class Database {
    private $conn;

    public function getConnection() {
        $this->conn = null;
        $config = require 'db_config.php';
        try {
            $this->conn = new PDO("pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}",  $config['user'], $config['password']);
            $this->conn->exec("set client_encoding to 'UTF8'");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>

<?php
// config.php
define('BASE_URL', '/BlueReading_v2/');
?>
