<?php
require_once 'includes/functions.php';
require_once 'models/Booking.php';

class QuotationController {
    public function view($id = null) {
        requireRole(['admin', 'user']);

        if(!$id) {
            die('Quotation id required');
        }
        $booking = new Booking();
        $bookingData = $booking->getBookingById($id);

        if(!$bookingData) {
            die('Quotation not found');
        }

        if(!isAdmin() && (int)$bookingData['user_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error'] = 'You are not allowed to view that quotation.';
            header('Location: index.php?controller=home&action=index');
            exit;
        }

        $services = $booking->getBookingServices($id);

        $services_total = array_sum(array_column($services, 'price_at_booking'));
        $total = $services_total + ($bookingData['venue_fee'] ?? 0);

        require_once 'views/client/quotation.php';
    }
}
?>
