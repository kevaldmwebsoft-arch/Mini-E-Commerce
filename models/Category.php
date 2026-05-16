<?php

class Category {
    private $conn;
    private $table = "categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Generate slug from name
     */
    private function generateSlug($name) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }

    /**
     * Check if slug already exists
     */
    public function slugExists($slug, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE slug = :slug";
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":slug", $slug);
        if ($excludeId) {
            $stmt->bindParam(":id", $excludeId);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Create a new category
     */
    public function create($name, $description = '', $image = '', $status = 'active') {
        $slug = $this->generateSlug($name);
        
        // Ensure unique slug
        $counter = 1;
        $originalSlug = $slug;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $query = "INSERT INTO " . $this->table . " (name, slug, description, image, status) 
                  VALUES (:name, :slug, :description, :image, :status)";
        $stmt = $this->conn->prepare($query);
        
        $name = htmlspecialchars(strip_tags(trim($name)));
        $description = htmlspecialchars(strip_tags(trim($description)));
        $image = htmlspecialchars(strip_tags(trim($image)));
        $status = htmlspecialchars(strip_tags(trim($status)));
        
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":slug", $slug);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":image", $image);
        $stmt->bindParam(":status", $status);
        
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update category
     */
    public function update($id, $name, $description = '', $image = '', $status = 'active') {
        $slug = $this->generateSlug($name);
        


        $query = "UPDATE " . $this->table . " 
                  SET name = :name, slug = :slug, description = :description, image = :image, status = :status 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $name = htmlspecialchars(strip_tags(trim($name)));
        $description = htmlspecialchars(strip_tags(trim($description)));
        $image = htmlspecialchars(strip_tags(trim($image)));
        $status = htmlspecialchars(strip_tags(trim($status)));
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":slug", $slug);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":image", $image);
        $stmt->bindParam(":status", $status);
        
        return $stmt->execute();
    }

    /**
     * Delete category
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}

?>