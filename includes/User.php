<?php
// User Model
class User {
    private $conn;
    private $table = 'users';
    
    public $id;
    public $username;
    public $email;
    public $password;
    public $role;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Login user
    public function login() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE username = :username 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        
        $row = $stmt->fetch();
        
        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            return true;
        }
        
        return false;
    }
    
    // Create user
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (username, email, password, role)
                  VALUES (:username, :email, :password, :role)";
        
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $this->role);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Check if user exists
    public function userExists() {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE username = :username OR email = :email 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>
