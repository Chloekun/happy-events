<?php
require_once 'config/database.php';

class Booking {
    private $conn;
    private $table_name = "bookings";
    private $columnCache = [];

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createBooking($data) {
        // Generate unique booking reference
        $booking_ref = 'HE' . date('Ymd') . rand(1000, 9999);
        
        $query = "INSERT INTO " . $this->table_name . "
                  SET booking_ref=:booking_ref, user_id=:user_id, event_type_id=:event_type_id,
                  event_title=:event_title, event_date=:event_date, event_time=:event_time,
                  venue_address=:venue_address, venue_location=:venue_location,
                  number_of_guests=:number_of_guests, budget_range=:budget_range,
                  additional_notes=:additional_notes, venue_fee=:venue_fee";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":booking_ref", $booking_ref);
        $stmt->bindParam(":user_id", $data['user_id']);
        $stmt->bindParam(":event_type_id", $data['event_type_id']);
        $stmt->bindParam(":event_title", $data['event_title']);
        $stmt->bindParam(":event_date", $data['event_date']);
        $stmt->bindParam(":event_time", $data['event_time']);
        $stmt->bindParam(":venue_address", $data['venue_address']);
        $stmt->bindParam(":venue_location", $data['venue_location']);
        $stmt->bindParam(":number_of_guests", $data['number_of_guests']);
        $stmt->bindParam(":budget_range", $data['budget_range']);
        $stmt->bindParam(":additional_notes", $data['additional_notes']);
        $stmt->bindParam(":venue_fee", $data['venue_fee']);
        
        if($stmt->execute()) {
            return ['booking_id' => $this->conn->lastInsertId(), 'booking_ref' => $booking_ref];
        }
        return false;
    }

    public function createAdminBooking($data) {
        $booking_ref = 'HE' . date('Ymd') . rand(1000, 9999);

        $query = "INSERT INTO " . $this->table_name . "
                  SET booking_ref=:booking_ref,
                      user_id=:user_id,
                      event_type_id=:event_type_id,
                      event_title=:event_title,
                      event_date=:event_date,
                      event_time=:event_time,
                      venue_address=:venue_address,
                      venue_location=:venue_location,
                      number_of_guests=:number_of_guests,
                      budget_range=:budget_range,
                      additional_notes=:additional_notes,
                      status=:status,
                      total_amount=:total_amount,
                      venue_fee=:venue_fee,
                      admin_remarks=:admin_remarks";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_ref", $booking_ref);
        $stmt->bindParam(":user_id", $data['user_id']);
        $stmt->bindParam(":event_type_id", $data['event_type_id']);
        $stmt->bindParam(":event_title", $data['event_title']);
        $stmt->bindParam(":event_date", $data['event_date']);
        $stmt->bindParam(":event_time", $data['event_time']);
        $stmt->bindParam(":venue_address", $data['venue_address']);
        $stmt->bindParam(":venue_location", $data['venue_location']);
        $stmt->bindParam(":number_of_guests", $data['number_of_guests']);
        $stmt->bindParam(":budget_range", $data['budget_range']);
        $stmt->bindParam(":additional_notes", $data['additional_notes']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":total_amount", $data['total_amount']);
        $stmt->bindParam(":venue_fee", $data['venue_fee']);
        $stmt->bindParam(":admin_remarks", $data['admin_remarks']);

        if($stmt->execute()) {
            return ['booking_id' => $this->conn->lastInsertId(), 'booking_ref' => $booking_ref];
        }
        return false;
    }

    public function addBookingServices($booking_id, $services) {
        $query = "INSERT INTO booking_services (booking_id, service_id, price_at_booking)
                  SELECT :booking_id, id, price FROM services WHERE id = :service_id";
        
        foreach($services as $service_id) {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":booking_id", $booking_id);
            $stmt->bindParam(":service_id", $service_id);
            $stmt->execute();
        }
        return true;
    }

    public function replaceBookingServices($booking_id, $services) {
        $query = "DELETE FROM booking_services WHERE booking_id = :booking_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();

        if(!empty($services)) {
            $this->addBookingServices($booking_id, $services);
        }

        $total = $this->calculateServicesTotal($services);
        $this->updateTotalAmount($booking_id, $total);
        return true;
    }

    public function calculateServicesTotal($services) {
        if(empty($services)) {
            return 0;
        }

        $placeholders = [];
        $params = [];
        foreach(array_values($services) as $index => $service_id) {
            $key = ':service_' . $index;
            $placeholders[] = $key;
            $params[$key] = $service_id;
        }

        $query = "SELECT COALESCE(SUM(price), 0) as total FROM services WHERE id IN (" . implode(',', $placeholders) . ")";
        $stmt = $this->conn->prepare($query);
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function updateTotalAmount($booking_id, $total_amount) {
        $query = "UPDATE " . $this->table_name . " SET total_amount = :total_amount WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":total_amount", $total_amount);
        $stmt->bindParam(":id", $booking_id);
        return $stmt->execute();
    }

    public function getVenueFee($location) {
        $query = "SELECT fee FROM venue_fees WHERE location LIKE :location";
        $stmt = $this->conn->prepare($query);
        $searchLoc = "%" . $location . "%";
        $stmt->bindParam(":location", $searchLoc);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['fee'];
        }
        return 0;
    }

    public function getUserBookings($user_id) {
        $query = "SELECT b.*, et.name as event_type_name,
                  (SELECT SUM(price_at_booking) FROM booking_services WHERE booking_id = b.id) as services_total
                  FROM " . $this->table_name . " b
                  JOIN event_types et ON b.event_type_id = et.id
                  WHERE b.user_id = :user_id
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBookings() {
        $query = "SELECT b.*, u.full_name, u.email, u.contact_number, et.name as event_type_name
                  FROM " . $this->table_name . " b
                  JOIN users u ON b.user_id = u.id
                  JOIN event_types et ON b.event_type_id = et.id
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedBookings($options = []) {
        $page = max(1, (int)($options['page'] ?? 1));
        $perPage = (int)($options['per_page'] ?? 10);
        $allowedPerPage = [10, 25, 50, 100];
        if(!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $sort = $options['sort'] ?? 'created_at';
        $direction = strtolower($options['direction'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
        $paymentColumnExists = $this->hasColumn('payment_status');
        $sortMap = [
            'booking_id' => 'b.id',
            'booking_ref' => 'b.booking_ref',
            'customer_name' => 'u.full_name',
            'event_date' => 'b.event_date',
            'event_type' => 'et.name',
            'status' => 'b.status',
            'payment_status' => $paymentColumnExists ? 'b.payment_status' : 'b.status',
            'created_at' => 'b.created_at'
        ];
        $sortColumn = $sortMap[$sort] ?? 'b.created_at';

        $filters = $options['filters'] ?? [];
        $where = [];
        $params = [];

        $search = trim($filters['search'] ?? '');
        if($search !== '') {
            $where[] = "(b.booking_ref LIKE :search OR CAST(b.id AS CHAR) LIKE :search OR u.full_name LIKE :search OR u.email LIKE :search OR u.contact_number LIKE :search OR et.name LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if(!empty($filters['status'])) {
            $where[] = "b.status = :status";
            $params[':status'] = $filters['status'];
        }

        if($paymentColumnExists && !empty($filters['payment_status'])) {
            $where[] = "b.payment_status = :payment_status";
            $params[':payment_status'] = $filters['payment_status'];
        }

        if(!empty($filters['event_type_id'])) {
            $where[] = "b.event_type_id = :event_type_id";
            $params[':event_type_id'] = $filters['event_type_id'];
        }

        if(!empty($filters['date_from'])) {
            $where[] = "b.event_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }

        if(!empty($filters['date_to'])) {
            $where[] = "b.event_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }

        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $countQuery = "SELECT COUNT(*) as total
                       FROM " . $this->table_name . " b
                       JOIN users u ON b.user_id = u.id
                       JOIN event_types et ON b.event_type_id = et.id" . $whereSql;
        $countStmt = $this->conn->prepare($countQuery);
        foreach($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        $totalPages = max(1, (int)ceil($total / $perPage));
        if($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;
        $paymentSelect = $paymentColumnExists ? "b.payment_status" : "'not_tracked' as payment_status";

        $query = "SELECT b.*, " . $paymentSelect . ", u.full_name, u.email, u.contact_number, et.name as event_type_name
                  FROM " . $this->table_name . " b
                  JOIN users u ON b.user_id = u.id
                  JOIN event_types et ON b.event_type_id = et.id" .
                  $whereSql . "
                  ORDER BY " . $sortColumn . " " . $direction . ", b.id DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'bookings' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'sort' => $sort,
            'direction' => strtolower($direction),
            'payment_status_supported' => $paymentColumnExists
        ];
    }

    private function hasColumn($column) {
        if(isset($this->columnCache[$column])) {
            return $this->columnCache[$column];
        }

        $query = "SHOW COLUMNS FROM " . $this->table_name . " LIKE :column_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':column_name', $column);
        $stmt->execute();
        $this->columnCache[$column] = (bool)$stmt->fetch(PDO::FETCH_ASSOC);
        return $this->columnCache[$column];
    }

    public function updateBookingStatus($booking_id, $status, $remarks = '') {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = :status, admin_remarks = :remarks
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":remarks", $remarks);
        $stmt->bindParam(":id", $booking_id);
        
        return $stmt->execute();
    }

    public function updateBooking($booking_id, $data) {
        $query = "UPDATE " . $this->table_name . "
                  SET user_id=:user_id,
                      event_type_id=:event_type_id,
                      event_title=:event_title,
                      event_date=:event_date,
                      event_time=:event_time,
                      venue_address=:venue_address,
                      venue_location=:venue_location,
                      number_of_guests=:number_of_guests,
                      budget_range=:budget_range,
                      additional_notes=:additional_notes,
                      status=:status,
                      venue_fee=:venue_fee,
                      admin_remarks=:admin_remarks
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $data['user_id']);
        $stmt->bindParam(":event_type_id", $data['event_type_id']);
        $stmt->bindParam(":event_title", $data['event_title']);
        $stmt->bindParam(":event_date", $data['event_date']);
        $stmt->bindParam(":event_time", $data['event_time']);
        $stmt->bindParam(":venue_address", $data['venue_address']);
        $stmt->bindParam(":venue_location", $data['venue_location']);
        $stmt->bindParam(":number_of_guests", $data['number_of_guests']);
        $stmt->bindParam(":budget_range", $data['budget_range']);
        $stmt->bindParam(":additional_notes", $data['additional_notes']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":venue_fee", $data['venue_fee']);
        $stmt->bindParam(":admin_remarks", $data['admin_remarks']);
        $stmt->bindParam(":id", $booking_id);

        return $stmt->execute();
    }

    public function deleteBooking($booking_id) {
        $query = "DELETE FROM meetings WHERE booking_id = :booking_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();

        $query = "DELETE FROM quotations WHERE booking_id = :booking_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();

        $query = "DELETE FROM booking_services WHERE booking_id = :booking_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();

        $query = "DELETE FROM notifications WHERE booking_id = :booking_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $booking_id);

        return $stmt->execute();
    }

    public function getBookingById($id) {
        $query = "SELECT b.*, u.full_name, u.email, u.contact_number, u.address,
                  et.name as event_type_name
                  FROM " . $this->table_name . " b
                  JOIN users u ON b.user_id = u.id
                  JOIN event_types et ON b.event_type_id = et.id
                  WHERE b.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookingServices($booking_id) {
        $query = "SELECT s.*, bs.price_at_booking 
                  FROM booking_services bs
                  JOIN services s ON bs.service_id = s.id
                  WHERE bs.booking_id = :booking_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatistics($period = 'yearly') {
        $stats = [];
        $allowedPeriods = ['day', 'month', 'quarter', 'yearly'];
        if(!in_array($period, $allowedPeriods, true)) {
            $period = 'yearly';
        }
        $bookingWhere = $this->analyticsDateCondition('created_at', '', $period);
        $bookingAnd = $bookingWhere ? ' AND ' . $bookingWhere : '';
        $bookingWhereSql = $bookingWhere ? ' WHERE ' . $bookingWhere : '';
        $bookingAliasWhere = $this->analyticsDateCondition('created_at', 'b', $period);
        $bookingAliasAnd = $bookingAliasWhere ? ' AND ' . $bookingAliasWhere : '';
        $bookingAliasWhereSql = $bookingAliasWhere ? ' WHERE ' . $bookingAliasWhere : '';
        $chartGrouping = $this->analyticsChartGrouping($period);
        $stats['analytics_period'] = $period;
        
        // Total customers
        $query = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_customers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total reservations
        $query = "SELECT COUNT(*) as total FROM bookings" . $bookingWhereSql;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_reservations'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Pending requests
        $query = "SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'" . $bookingAnd;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['pending_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Today's bookings
        $query = "SELECT COUNT(*) as total FROM bookings WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['today_bookings'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $query = "SELECT status, COUNT(*) as total FROM bookings" . $bookingWhereSql . " GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['status_counts'] = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'completed' => 0
        ];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $stats['status_counts'][$row['status']] = (int)$row['total'];
        }

        $query = "SELECT COALESCE(SUM(COALESCE(total_amount, 0) + COALESCE(venue_fee, 0)), 0) as total FROM bookings WHERE status IN ('approved', 'completed')" . $bookingAnd;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['estimated_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $query = "SELECT COUNT(*) as total FROM bookings WHERE event_date >= CURDATE()" . $bookingAnd;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['upcoming_events'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $query = "SELECT COALESCE(ROUND(AVG(number_of_guests)), 0) as average_guests FROM bookings WHERE number_of_guests IS NOT NULL" . $bookingAnd;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['average_guests'] = $stmt->fetch(PDO::FETCH_ASSOC)['average_guests'];

        $query = "SELECT COUNT(*) as total FROM services WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['active_services'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $resolvedBookings = ($stats['status_counts']['approved'] ?? 0) + ($stats['status_counts']['completed'] ?? 0) + ($stats['status_counts']['rejected'] ?? 0);
        $approvedBookings = ($stats['status_counts']['approved'] ?? 0) + ($stats['status_counts']['completed'] ?? 0);
        $stats['approval_rate'] = $resolvedBookings > 0 ? round(($approvedBookings / $resolvedBookings) * 100) : 0;

        $query = "SELECT " . $chartGrouping['select'] . " as booking_date,
                         " . $chartGrouping['order'] . " as sort_key,
                         COUNT(*) as total
                  FROM bookings" . $bookingWhereSql . "
                  GROUP BY " . $chartGrouping['group'] . "
                  ORDER BY sort_key ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['daily_bookings'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total
                  FROM bookings
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month DESC
                  LIMIT 6";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['monthly_bookings'] = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

        $query = "SELECT et.name, COUNT(*) as total
                  FROM bookings b
                  JOIN event_types et ON b.event_type_id = et.id" .
                  $bookingAliasWhereSql . "
                  GROUP BY et.id, et.name
                  ORDER BY total DESC
                  LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['event_type_counts'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT COALESCE(NULLIF(venue_location, ''), 'Not specified') as location, COUNT(*) as total
                  FROM bookings" . $bookingWhereSql . "
                  GROUP BY COALESCE(NULLIF(venue_location, ''), 'Not specified')
                  ORDER BY total DESC
                  LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['location_counts'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT s.name, COUNT(*) as total, COALESCE(SUM(bs.price_at_booking), 0) as revenue
                  FROM booking_services bs
                  JOIN services s ON bs.service_id = s.id
                  JOIN bookings b ON bs.booking_id = b.id" .
                  $bookingAliasWhereSql . "
                  GROUP BY s.id, s.name
                  ORDER BY total DESC, revenue DESC
                  LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['popular_services'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT b.booking_ref, b.event_title, b.event_date, b.status, u.full_name
                  FROM bookings b
                  JOIN users u ON b.user_id = u.id" .
                  $bookingAliasWhereSql . "
                  ORDER BY b.created_at DESC
                  LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['recent_bookings'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

    private function analyticsDateCondition($column, $alias = '', $period = 'yearly') {
        $field = $alias ? $alias . '.' . $column : $column;

        switch($period) {
            case 'day':
                return "DATE(" . $field . ") = CURDATE()";
            case 'month':
                return "YEAR(" . $field . ") = YEAR(CURDATE()) AND MONTH(" . $field . ") = MONTH(CURDATE())";
            case 'quarter':
                return "YEAR(" . $field . ") = YEAR(CURDATE()) AND QUARTER(" . $field . ") = QUARTER(CURDATE())";
            case 'yearly':
            default:
                return "YEAR(" . $field . ") = YEAR(CURDATE())";
        }
    }

    private function analyticsChartGrouping($period) {
        switch($period) {
            case 'day':
                return [
                    'select' => "DATE_FORMAT(created_at, '%H:00')",
                    'group' => "DATE_FORMAT(created_at, '%H')",
                    'order' => "DATE_FORMAT(created_at, '%H')"
                ];
            case 'month':
                return [
                    'select' => "DATE_FORMAT(created_at, '%b %d')",
                    'group' => "DATE(created_at)",
                    'order' => "DATE(created_at)"
                ];
            case 'quarter':
                return [
                    'select' => "CONCAT('Week ', WEEK(created_at, 1))",
                    'group' => "YEARWEEK(created_at, 1)",
                    'order' => "YEARWEEK(created_at, 1)"
                ];
            case 'yearly':
            default:
                return [
                    'select' => "DATE_FORMAT(created_at, '%b')",
                    'group' => "DATE_FORMAT(created_at, '%Y-%m')",
                    'order' => "DATE_FORMAT(created_at, '%Y-%m')"
                ];
        }
    }
}
?>
