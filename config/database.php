<?php
// config/database.php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $conn;
    private $error;
    
    public function __construct() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        
        // Create a new PDO instance
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Error de conexión: ' . $this->error;
        }
    }
    
    // Prepare statement with query
    public function query($sql) {
        return $this->conn->prepare($sql);
    }
    
    // Execute the prepared statement
    public function execute($stmt, $data = []) {
        return $stmt->execute($data);
    }
    
    // Get result set as array of objects
    public function resultSet($stmt) {
        $this->execute($stmt);
        return $stmt->fetchAll();
    }
    
    // Get single record as object
    public function single($stmt) {
        $this->execute($stmt);
        return $stmt->fetch();
    }
    
    // Get row count
    public function rowCount($stmt) {
        return $stmt->rowCount();
    }
    
    // Last inserted ID
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
    
    // Transactions
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }
    
    public function endTransaction() {
        return $this->conn->commit();
    }
    
    public function cancelTransaction() {
        return $this->conn->rollBack();
    }
}
?>