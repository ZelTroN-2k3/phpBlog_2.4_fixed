<?php
// Post Model
class Post {
    private $conn;
    private $table = 'posts';
    
    public $id;
    public $title;
    public $content;
    public $author_id;
    public $category_id;
    public $featured_image;
    public $status;
    public $created_at;
    public $updated_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Get all posts
    public function read($limit = null, $offset = 0) {
        $limitClause = $limit ? "LIMIT :limit OFFSET :offset" : "";
        
        $query = "SELECT p.*, u.username as author_name, c.name as category_name 
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.author_id = u.id
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.status = 'published'
                  ORDER BY p.created_at DESC
                  $limitClause";
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Get single post
    public function readOne() {
        $query = "SELECT p.*, u.username as author_name, c.name as category_name 
                  FROM " . $this->table . " p
                  LEFT JOIN users u ON p.author_id = u.id
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch();
        
        if ($row) {
            $this->title = $row['title'];
            $this->content = $row['content'];
            $this->author_id = $row['author_id'];
            $this->category_id = $row['category_id'];
            $this->featured_image = $row['featured_image'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            return true;
        }
        
        return false;
    }
    
    // Create post
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (title, content, author_id, category_id, featured_image, status)
                  VALUES (:title, :content, :author_id, :category_id, :featured_image, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':featured_image', $this->featured_image);
        $stmt->bindParam(':status', $this->status);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Update post
    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET title = :title,
                      content = :content,
                      category_id = :category_id,
                      featured_image = :featured_image,
                      status = :status
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':featured_image', $this->featured_image);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Delete post
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Get post count
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE status = 'published'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
}
?>
