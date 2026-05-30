<?php
require_once 'includes/functions.php';

class HomeController {
    
    public function index() {
        require_once 'views/client/home.php';
    }
    
    public function about() {
        // About page logic
        require_once 'views/client/about.php';
    }
    
    public function services() {
        require_once 'models/Service.php';
        $serviceModel = new Service();
        $services = $serviceModel->getAllServices();
        require_once 'views/client/services.php';
    }
    
    public function gallery() {
        require_once 'views/client/gallery.php';
    }
    
    public function contact() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle contact form submission
            $this->sendContactEmail($_POST);
        }
        require_once 'views/client/contact.php';
    }

    public function notifications() {
        requireCustomer();
        require_once 'models/Notification.php';

        $notificationModel = new Notification();
        $notifications = $notificationModel->getUserNotifications($_SESSION['user_id']);
        $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);

        require_once 'views/client/notifications.php';
    }

    public function markNotificationRead($id = null) {
        requireCustomer();

        if(!$id) {
            header('Location: index.php?controller=home&action=notifications');
            exit;
        }

        require_once 'models/Notification.php';
        $notificationModel = new Notification();
        $notificationModel->markAsRead($id, $_SESSION['user_id']);

        header('Location: index.php?controller=home&action=notifications');
        exit;
    }

    public function processNotification($id = null) {
        requireCustomer();

        if(!$id) {
            header('Location: index.php?controller=home&action=notifications');
            exit;
        }

        require_once 'models/Notification.php';
        $notificationModel = new Notification();
        $notification = $notificationModel->getUserNotificationById($id, $_SESSION['user_id']);

        if(!$notification) {
            $_SESSION['error'] = 'Notification not found.';
            header('Location: index.php?controller=home&action=notifications');
            exit;
        }

        $notificationModel->markAsRead($id, $_SESSION['user_id']);

        if(!empty($notification['booking_id'])) {
            header('Location: index.php?controller=quotation&action=view&id=' . urlencode($notification['booking_id']));
            exit;
        }

        header('Location: index.php?controller=home&action=notifications');
        exit;
    }

    public function markAllNotificationsRead() {
        requireCustomer();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Notification.php';
            $notificationModel = new Notification();
            $notificationModel->markAllAsRead($_SESSION['user_id']);
        }

        header('Location: index.php?controller=home&action=notifications');
        exit;
    }
    
    private function sendContactEmail($data) {
        // Email sending logic
        $to = ADMIN_EMAIL;
        $subject = "Contact Form Inquiry from " . $data['name'];
        $message = "Name: " . $data['name'] . "\n";
        $message .= "Email: " . $data['email'] . "\n";
        $message .= "Message: " . $data['message'];
        $headers = "From: " . $data['email'];
        
        mail($to, $subject, $message, $headers);
    }
}
?>
