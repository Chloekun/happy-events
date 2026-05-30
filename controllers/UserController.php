<?php
require_once 'includes/functions.php';
require_once 'models/User.php';
require_once 'models/Booking.php';

class UserController {
    public function __construct() {
        requireCustomer();
    }

    public function dashboard() {
        header('Location: index.php?controller=home&action=index');
        exit;
    }

    public function profile() {
        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel->updateProfile($_SESSION['user_id'], [
                'full_name' => $_POST['full_name'] ?? $user['full_name'],
                'contact_number' => $_POST['contact_number'] ?? $user['contact_number'],
                'address' => $_POST['address'] ?? $user['address']
            ]);
            $_SESSION['success'] = 'Profile updated.';
            header('Location: index.php?controller=user&action=profile');
            return;
        }

        require_once 'views/client/profile.php';
    }
}
?>
