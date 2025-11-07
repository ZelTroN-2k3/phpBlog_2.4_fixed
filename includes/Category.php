<?php
// Category Model
class Category {
    private $conn;
    private $table = 'categories';
    
    public $id;
    public $name;
    public $slug;
    public $description;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Get all categories
    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Create category
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (name, slug, description)
                  VALUES (:name, :slug, :description)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':description', $this->description);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
