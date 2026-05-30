<?php
require_once 'config/database.php';

class Notification {
    private $conn;
    private $table_name = 'notifications';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createNotification($user_id, $booking_id, $title, $message, $type = 'general') {
        $query = "INSERT INTO " . $this->table_name . "
                  SET user_id = :user_id,
                      booking_id = :booking_id,
                      title = :title,
                      message = :message,
                      notification_type = :notification_type,
                      created_at = NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':booking_id', $booking_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':notification_type', $type);
        return $stmt->execute();
    }

    public function getUserNotifications($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserNotificationById($notification_id, $user_id) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE id = :id AND user_id = :user_id
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $notification_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markAsRead($notification_id, $user_id) {
        $query = "UPDATE " . $this->table_name . "
                  SET is_read = 1
                  WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $notification_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public function markAllAsRead($user_id) {
        $query = "UPDATE " . $this->table_name . "
                  SET is_read = 1
                  WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public function getUnreadCount($user_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                  WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
