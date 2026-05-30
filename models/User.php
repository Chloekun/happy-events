<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $full_name;
    public $email;
    public $username;
    public $password;
    public $contact_number;
    public $address;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    private function isConnected() {
        return !empty($this->conn);
    }

    public function register() {
        // ensure DB connection
        if(!$this->isConnected()) {
            $_SESSION['error'] = 'Database connection error';
            return false;
        }

        // Check if user exists
        if($this->userExists()) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . "
                  SET full_name=:full_name, email=:email, username=:username,
                  password=:password, contact_number=:contact_number, address=:address";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":contact_number", $this->contact_number);
        $stmt->bindParam(":address", $this->address);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login() {
        if(!$this->isConnected()) {
            $_SESSION['error'] = 'Database connection error';
            // log
            if(!is_dir(__DIR__ . '/../storage')) mkdir(__DIR__ . '/../storage', 0755, true);
            file_put_contents(__DIR__ . '/../storage/debug.log', date('c') . " - User::login - DB connection missing\n", FILE_APPEND);
            return false;
        }

        try {
            $query = "SELECT * FROM " . $this->table_name . " 
                      WHERE (username = :username OR email = :username) 
                      AND status = 'active'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $this->username);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($this->password, $row['password'])) {
                    file_put_contents(__DIR__ . '/../storage/debug.log', date('c') . " - User::login success for user id=" . $row['id'] . "\n", FILE_APPEND);
                    return $row;
                } else {
                    file_put_contents(__DIR__ . '/../storage/debug.log', date('c') . " - User::login password mismatch for username=" . $this->username . "\n", FILE_APPEND);
                }
            } else {
                file_put_contents(__DIR__ . '/../storage/debug.log', date('c') . " - User::login no user found for username=" . $this->username . "\n", FILE_APPEND);
            }
        } catch(PDOException $ex) {
            file_put_contents(__DIR__ . '/../storage/debug.log', date('c') . " - User::login exception: " . $ex->getMessage() . "\n", FILE_APPEND);
            $_SESSION['error'] = 'Database error';
            return false;
        }
        return false;
    }

    private function userExists() {
        if(!$this->isConnected()) return false;

        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE username = :username OR email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function getUserById($id) {
        if(!$this->isConnected()) return false;

        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCustomers() {
        if(!$this->isConnected()) return [];

        $query = "SELECT id, full_name, email, contact_number
                  FROM " . $this->table_name . "
                  WHERE role = 'user' AND status = 'active'
                  ORDER BY full_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $data) {
        if(!$this->isConnected()) return false;

        $query = "UPDATE " . $this->table_name . " 
                  SET full_name = :full_name, contact_number = :contact_number, 
                      address = :address
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":contact_number", $data['contact_number']);
        $stmt->bindParam(":address", $data['address']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function changePassword($id, $new_password) {
        if(!$this->isConnected()) return false;

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function resetPasswordByLogin($login, $new_password) {
        if(!$this->isConnected()) return false;

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . "
                  SET password = :password
                  WHERE (username = :login OR email = :login)
                  AND status = 'active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":login", $login);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }
}
?>
