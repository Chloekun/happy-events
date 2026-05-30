<?php
require_once 'models/User.php';

class AuthController {
    
    public function showLogin() {
        require_once 'views/client/login.php';
    }
    
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            // debug log
            if(!is_dir(__DIR__ . '/../storage')) mkdir(__DIR__ . '/../storage', 0755, true);
            $log = date('c') . " - AuthController::login called - username=" . $username . "\n";
            file_put_contents(__DIR__ . '/../storage/debug.log', $log, FILE_APPEND);

            if($username === '' || $password === '') {
                $_SESSION['error'] = "Please provide username and password";
                header("Location: index.php?controller=auth&action=showLogin");
                exit;
            }

            $user = new User();
            $user->username = $username;
            $user->password = $password;
            
            $result = $user->login();
            
            if($result) {
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['user_name'] = $result['full_name'];
                $_SESSION['user_role'] = $result['role'];
                
                if($result['role'] === 'admin') {
                    header("Location: index.php?controller=admin&action=dashboard");
                    exit;
                }

                header("Location: index.php?controller=home&action=index");
                exit;
            } else {
                $_SESSION['error'] = "Invalid username or password";
                header("Location: index.php?controller=auth&action=showLogin");
                exit;
            }
        }
    }
    
    public function showRegister() {
        require_once 'views/client/register.php';
    }

    public function showForgotPassword() {
        require_once 'views/client/forgot_password.php';
    }

    public function forgotPassword() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $login = isset($_POST['login']) ? trim($_POST['login']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

            if($login === '' || $password === '' || $confirm_password === '') {
                $_SESSION['error'] = 'Please complete all fields.';
                header('Location: index.php?controller=auth&action=showForgotPassword');
                exit;
            }

            if($password !== $confirm_password) {
                $_SESSION['error'] = 'Passwords do not match.';
                header('Location: index.php?controller=auth&action=showForgotPassword');
                exit;
            }

            $user = new User(); 
            $user->resetPasswordByLogin($login, $password);

            $_SESSION['success'] = 'If the account exists, the password has been updated. Please login.';
            header('Location: index.php?controller=auth&action=showLogin');
            exit;
            
        }
    }
    
    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

            if($password !== $confirm_password) {
                $_SESSION['error'] = "Passwords do not match";
                header("Location: index.php?controller=auth&action=showRegister");
                return;
            }
            
            $user = new User();
            $user->full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
            $user->email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $user->username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $user->password = $password;
            $user->contact_number = isset($_POST['contact_number']) ? trim($_POST['contact_number']) : '';
            $user->address = isset($_POST['address']) ? trim($_POST['address']) : '';
            
            if($user->register()) {
                $_SESSION['success'] = "Registration successful! Please login.";
                header("Location: index.php?controller=auth&action=showLogin");
                exit;
            } else {
                $_SESSION['error'] = "Username or email already exists";
                header("Location: index.php?controller=auth&action=showRegister");
                exit;
            }
        }
    }
    
    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
?>
