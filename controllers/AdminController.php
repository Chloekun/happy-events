<?php
require_once 'includes/functions.php';
require_once 'models/Booking.php';
require_once 'models/User.php';
require_once 'models/EventType.php';
require_once 'models/Service.php';

class AdminController {
    public function __construct() {
        requireAdmin();
    }

    public function dashboard() {
        $booking = new Booking();
        $analyticsPeriod = $_GET['period'] ?? 'yearly';
        $allowedPeriods = ['day', 'month', 'quarter', 'yearly'];
        if(!in_array($analyticsPeriod, $allowedPeriods, true)) {
            $analyticsPeriod = 'yearly';
        }
        $stats = $booking->getStatistics($analyticsPeriod);
        require_once 'views/admin/dashboard.php';
    }

    public function bookings() {
        $booking = new Booking();
        $eventTypeModel = new EventType();
        $queryState = $this->bookingQueryState();
        $filters = $queryState['filters'];
        $bookingPage = $booking->getPaginatedBookings($queryState);
        $bookings = $bookingPage['bookings'];
        $eventTypes = $eventTypeModel->getAllEventTypes();
        $bookingStatuses = ['pending', 'approved', 'rejected', 'completed'];
        $paymentStatuses = ['unpaid', 'partial', 'paid', 'refunded'];
        require_once 'views/admin/bookings.php';
    }

    public function bookingsData() {
        $booking = new Booking();
        $queryState = $this->bookingQueryState();
        $bookingPage = $booking->getPaginatedBookings($queryState);

        header('Content-Type: application/json');
        echo json_encode([
            'bookings' => $bookingPage['bookings'],
            'meta' => [
                'total' => $bookingPage['total'],
                'page' => $bookingPage['page'],
                'per_page' => $bookingPage['per_page'],
                'total_pages' => $bookingPage['total_pages'],
                'sort' => $bookingPage['sort'],
                'direction' => $bookingPage['direction'],
                'payment_status_supported' => $bookingPage['payment_status_supported']
            ]
        ]);
        exit;
    }

    public function createBooking() {
        $bookingData = [];
        $selectedServices = [];
        $formAction = 'index.php?controller=admin&action=storeBooking';
        $formTitle = 'Create Booking';
        $this->loadBookingForm($bookingData, $selectedServices, $formAction, $formTitle);
    }

    public function viewBooking($id = null) {
        if(!$id) {
            $_SESSION['error'] = 'Booking not found.';
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $booking = new Booking();
        $bookingData = $booking->getBookingById($id);

        if(!$bookingData) {
            $_SESSION['error'] = 'Booking not found.';
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $bookingServices = $booking->getBookingServices($id);
        require_once 'views/admin/booking_view.php';
    }

    public function storeBooking() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $booking = new Booking();
        $services = $_POST['services'] ?? [];
        $totalAmount = $booking->calculateServicesTotal($services);
        $venueLocation = $_POST['venue_location'] ?? '';
        $venueFee = $booking->getVenueFee($venueLocation);

        $data = $this->bookingPayload($venueFee, $totalAmount);
        $result = $booking->createAdminBooking($data);

        if($result) {
            $booking->replaceBookingServices($result['booking_id'], $services);
            $_SESSION['success'] = 'Booking created.';
        } else {
            $_SESSION['error'] = 'Failed to create booking.';
        }

        header('Location: index.php?controller=admin&action=bookings');
        exit;
    }

    public function editBooking($id = null) {
        if(!$id) {
            $_SESSION['error'] = 'Booking not found.';
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $booking = new Booking();
        $bookingData = $booking->getBookingById($id);

        if(!$bookingData) {
            $_SESSION['error'] = 'Booking not found.';
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $selectedServices = array_column($booking->getBookingServices($id), 'id');
        $formAction = 'index.php?controller=admin&action=updateBooking&id=' . urlencode($id);
        $formTitle = 'Edit Booking';
        $this->loadBookingForm($bookingData, $selectedServices, $formAction, $formTitle);
    }

    public function updateBooking($id = null) {
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $booking = new Booking();
        $services = $_POST['services'] ?? [];
        $totalAmount = $booking->calculateServicesTotal($services);
        $venueLocation = $_POST['venue_location'] ?? '';
        $venueFee = $booking->getVenueFee($venueLocation);
        $data = $this->bookingPayload($venueFee, $totalAmount);

        if($booking->updateBooking($id, $data)) {
            $booking->replaceBookingServices($id, $services);
            $_SESSION['success'] = 'Booking updated.';
        } else {
            $_SESSION['error'] = 'Failed to update booking.';
        }

        header('Location: index.php?controller=admin&action=bookings');
        exit;
    }

    public function deleteBooking($id = null) {
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $booking = new Booking();
        if($booking->deleteBooking($id)) {
            $_SESSION['success'] = 'Booking deleted.';
        } else {
            $_SESSION['error'] = 'Failed to delete booking.';
        }

        header('Location: index.php?controller=admin&action=bookings');
        exit;
    }

    public function updateBookingStatus($id = null) {
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $status = $_POST['status'] ?? '';
        $allowedStatuses = ['approved', 'rejected'];

        if(!in_array($status, $allowedStatuses, true)) {
            $_SESSION['error'] = 'Invalid booking action.';
            header('Location: index.php?controller=admin&action=bookings');
            exit;
        }

        $remarks = $status === 'approved' ? 'Booking accepted by admin.' : 'Booking declined by admin.';
        $booking = new Booking();

        if($booking->updateBookingStatus($id, $status, $remarks)) {
            $_SESSION['success'] = $status === 'approved' ? 'Booking accepted.' : 'Booking declined.';
        } else {
            $_SESSION['error'] = 'Failed to update booking.';
        }

        header('Location: index.php?controller=admin&action=bookings');
        exit;
    }

    public function customers() {
        $user = new User();
        // simple listing logic
        $customers = [];
        require_once 'views/admin/customers.php';
    }

    public function services() {
        require_once 'models/Service.php';
        $service = new Service();
        $services = $service->getAllServices();
        require_once 'views/admin/services.php';
    }

    public function createService() {
        $formAction = 'index.php?controller=admin&action=storeService';
        $formTitle = 'Add Service';
        $serviceData = [];
        require_once 'views/admin/service_form.php';
    }

    public function storeService() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=services');
            exit;
        }

        $data = $this->servicePayload();

        if($data['name'] === '' || (float)$data['price'] < 0) {
            $_SESSION['error'] = 'Please provide a valid service name and price.';
            header('Location: index.php?controller=admin&action=createService');
            exit;
        }

        $service = new Service();
        if($service->createService($data)) {
            $_SESSION['success'] = 'Service added.';
        } else {
            $_SESSION['error'] = 'Failed to add service.';
        }

        header('Location: index.php?controller=admin&action=services');
        exit;
    }

    public function viewService($id = null) {
        $service = $this->findServiceOrRedirect($id);
        require_once 'views/admin/service_view.php';
    }

    public function editService($id = null) {
        $serviceData = $this->findServiceOrRedirect($id);
        $formAction = 'index.php?controller=admin&action=updateService&id=' . urlencode($id);
        $formTitle = 'Edit Service';
        require_once 'views/admin/service_form.php';
    }

    public function updateService($id = null) {
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: index.php?controller=admin&action=services');
            exit;
        }

        $data = $this->servicePayload();

        if($data['name'] === '' || (float)$data['price'] < 0) {
            $_SESSION['error'] = 'Please provide a valid service name and price.';
            header('Location: index.php?controller=admin&action=editService&id=' . urlencode($id));
            exit;
        }

        $service = new Service();
        if($service->updateService($id, $data)) {
            $_SESSION['success'] = 'Service updated.';
        } else {
            $_SESSION['error'] = 'Failed to update service.';
        }

        header('Location: index.php?controller=admin&action=services');
        exit;
    }

    public function deleteService($id = null) {
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: index.php?controller=admin&action=services');
            exit;
        }

        $service = new Service();
        if($service->deleteService($id)) {
            $_SESSION['success'] = 'Service deleted.';
        } else {
            $_SESSION['error'] = 'Failed to delete service.';
        }

        header('Location: index.php?controller=admin&action=services');
        exit;
    }

    public function reports() {
        require_once 'views/admin/reports.php';
    }

    private function loadBookingForm($bookingData, $selectedServices, $formAction, $formTitle) {
        $userModel = new User();
        $eventTypeModel = new EventType();
        $serviceModel = new Service();

        $customers = $userModel->getCustomers();
        $eventTypes = $eventTypeModel->getAllEventTypes();
        $services = $serviceModel->getAllServices();
        $statuses = ['pending', 'approved', 'rejected', 'completed'];

        require_once 'views/admin/booking_form.php';
    }

    private function bookingQueryState() {
        return [
            'page' => $_GET['page'] ?? 1,
            'per_page' => $_GET['per_page'] ?? 10,
            'sort' => $_GET['sort'] ?? 'created_at',
            'direction' => $_GET['direction'] ?? 'desc',
            'filters' => [
                'search' => trim($_GET['search'] ?? ''),
                'status' => $_GET['status'] ?? '',
                'payment_status' => $_GET['payment_status'] ?? '',
                'event_type_id' => $_GET['event_type_id'] ?? '',
                'date_from' => $_GET['date_from'] ?? '',
                'date_to' => $_GET['date_to'] ?? ''
            ]
        ];
    }

    private function bookingPayload($venueFee, $totalAmount) {
        return [
            'user_id' => $_POST['user_id'] ?? null,
            'event_type_id' => $_POST['event_type_id'] ?? null,
            'event_title' => trim($_POST['event_title'] ?? ''),
            'event_date' => $_POST['event_date'] ?? null,
            'event_time' => $_POST['event_time'] ?? null,
            'venue_address' => trim($_POST['venue_address'] ?? ''),
            'venue_location' => $_POST['venue_location'] ?? '',
            'number_of_guests' => $_POST['number_of_guests'] ?? null,
            'budget_range' => $_POST['budget_range'] ?? '',
            'additional_notes' => trim($_POST['additional_notes'] ?? ''),
            'status' => $_POST['status'] ?? 'pending',
            'total_amount' => $totalAmount,
            'venue_fee' => $venueFee,
            'admin_remarks' => trim($_POST['admin_remarks'] ?? '')
        ];
    }

    private function servicePayload() {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => $_POST['price'] ?? 0,
            'category' => trim($_POST['category'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
    }

    private function findServiceOrRedirect($id) {
        if(!$id) {
            $_SESSION['error'] = 'Service not found.';
            header('Location: index.php?controller=admin&action=services');
            exit;
        }

        $serviceModel = new Service();
        $service = $serviceModel->getServiceById($id);

        if(!$service) {
            $_SESSION['error'] = 'Service not found.';
            header('Location: index.php?controller=admin&action=services');
            exit;
        }

        return $service;
    }
}
?>
