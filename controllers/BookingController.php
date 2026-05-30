<?php
require_once 'includes/functions.php';
require_once 'models/Booking.php';
require_once 'models/Service.php';
require_once 'models/EventType.php';
require_once 'models/Notification.php';

class BookingController {
    
    public function showBookingForm() {
        requireCustomer();
        
        $serviceModel = new Service();
        $eventTypeModel = new EventType();
        
        $services = $serviceModel->getAllServices();
        $eventTypes = $eventTypeModel->getAllEventTypes();
        
        require_once 'views/client/booking.php';
    }
    
    public function createBooking() {
        requireCustomer();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $booking = new Booking();
            
            // Calculate venue fee
            $venue_fee = $booking->getVenueFee($_POST['venue_location']);
            
            $bookingData = [
                'user_id' => $_SESSION['user_id'],
                'event_type_id' => $_POST['event_type'],
                'event_title' => $_POST['event_title'],
                'event_date' => $_POST['event_date'],
                'event_time' => $_POST['event_time'],
                'venue_address' => $_POST['venue_address'],
                'venue_location' => $_POST['venue_location'],
                'number_of_guests' => $_POST['number_of_guests'],
                'budget_range' => $_POST['budget_range'],
                'additional_notes' => $_POST['additional_notes'],
                'venue_fee' => $venue_fee
            ];
            
            $result = $booking->createBooking($bookingData);
            
            if($result) {
                // Add selected services
                if(isset($_POST['services'])) {
                    $booking->addBookingServices($result['booking_id'], $_POST['services']);
                }
                
                // Create notification
                $notification = new Notification();
                $notification->createNotification(
                    $_SESSION['user_id'],
                    $result['booking_id'],
                    'Booking Submitted',
                    'Your booking request has been submitted for approval.',
                    'booking'
                );
                
                $_SESSION['success'] = "Booking submitted successfully! Your reference number is: " . $result['booking_ref'];
                header("Location: index.php?controller=home&action=index");
            } else {
                $_SESSION['error'] = "Failed to submit booking. Please try again.";
                header("Location: index.php?controller=booking&action=showBookingForm");
            }
        }
    }
    
    public function viewQuotation($booking_id) {
        requireRole(['admin', 'user']);

        $booking = new Booking();
        $bookingData = $booking->getBookingById($booking_id);

        if(!$bookingData) {
            die('Booking not found');
        }

        if(!isAdmin() && (int)$bookingData['user_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error'] = 'You are not allowed to view that quotation.';
            header('Location: index.php?controller=home&action=index');
            exit;
        }

        $services = $booking->getBookingServices($booking_id);
        
        // Calculate total
        $services_total = array_sum(array_column($services, 'price_at_booking'));
        $total = $services_total + $bookingData['venue_fee'];
        
        require_once 'views/client/quotation.php';
    }

    // AJAX endpoint to retrieve venue fee for a given location
    public function getVenueFee() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $location = isset($_POST['location']) ? $_POST['location'] : '';
            $booking = new Booking();
            $fee = $booking->getVenueFee($location);
            echo intval($fee);
            exit;
        }
        echo 0;
        exit;
    }
}
?>
