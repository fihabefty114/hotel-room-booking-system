<?php

require_once __DIR__ . "/../config/Database.php";

function getReceptionistDashboardStats() {
    $conn = getConnection();

    $stats = array(
        "today_checkins" => 0,
        "today_checkouts" => 0,
        "checked_in_guests" => 0,
        "available_rooms" => 0,
        "pending_service_requests" => 0,
        "pending_payments" => 0
    );

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE checkin_date = CURDATE() AND status = 'confirmed'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["today_checkins"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE checkout_date = CURDATE() AND status = 'checked_in'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["today_checkouts"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'checked_in'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["checked_in_guests"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'available'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["available_rooms"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM service_requests WHERE status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["pending_service_requests"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM billing WHERE payment_status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["pending_payments"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    return $stats;
}

function searchReceptionistCheckInBookings($keyword) {
    $conn = getConnection();

    $keywordLike = "%" . $keyword . "%";

    $sql = "SELECT bookings.*, users.name AS guest_name, users.email, users.id_number,
                   room_types.name AS room_type_name
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE bookings.status = 'confirmed'
            AND (bookings.id = ? OR users.name LIKE ?)
            ORDER BY bookings.checkin_date ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keywordLike);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $bookings = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $bookings;
}

function getAvailableRoomsByRoomType($roomTypeId) {
    $conn = getConnection();

    $sql = "SELECT * FROM rooms
            WHERE room_type_id = ?
            AND status = 'available'
            ORDER BY room_number ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $roomTypeId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rooms = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $rooms;
}

function completeGuestCheckIn($bookingId, $roomId) {
    $conn = getConnection();

    $sql = "UPDATE rooms SET status = 'occupied'
            WHERE id = ?
            AND status = 'available'";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $roomId);
    mysqli_stmt_execute($stmt);

    $roomUpdated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $roomUpdated = true;
    }

    mysqli_stmt_close($stmt);

    if (!$roomUpdated) {
        return false;
    }

    $sql = "UPDATE bookings
            SET status = 'checked_in', room_id = ?
            WHERE id = ?
            AND status = 'confirmed'";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $roomId, $bookingId);
    mysqli_stmt_execute($stmt);

    $bookingUpdated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $bookingUpdated = true;
    }

    mysqli_stmt_close($stmt);

    return $bookingUpdated;
}

function searchReceptionistCheckOutBookings($keyword) {
    $conn = getConnection();

    $keywordLike = "%" . $keyword . "%";

    $sql = "SELECT bookings.*, users.name AS guest_name, users.email,
                   rooms.id AS room_id, rooms.room_number,
                   room_types.name AS room_type_name
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            INNER JOIN rooms ON bookings.room_id = rooms.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE bookings.status = 'checked_in'
            AND (rooms.room_number = ? OR users.name LIKE ?)
            ORDER BY bookings.checkout_date ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keywordLike);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $bookings = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $bookings;
}

function completeGuestCheckOut($bookingId, $roomId) {
    $conn = getConnection();

    $sql = "UPDATE bookings
            SET status = 'checked_out'
            WHERE id = ?
            AND status = 'checked_in'";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $bookingId);
    mysqli_stmt_execute($stmt);

    $bookingUpdated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $bookingUpdated = true;
    }

    mysqli_stmt_close($stmt);

    if (!$bookingUpdated) {
        return false;
    }

    $sql = "UPDATE rooms
            SET status = 'dirty'
            WHERE id = ?
            AND status = 'occupied'";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $roomId);
    mysqli_stmt_execute($stmt);

    $roomUpdated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $roomUpdated = true;
    }

    mysqli_stmt_close($stmt);

    return $roomUpdated;
}

function getReceptionistRoomStatusBoard() {
    $conn = getConnection();

    $sql = "SELECT rooms.*, room_types.name AS room_type_name
            FROM rooms
            INNER JOIN room_types ON rooms.room_type_id = room_types.id
            ORDER BY rooms.room_number ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rooms = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $rooms;
}

function getReceptionistServiceRequests() {
    $conn = getConnection();

    $sql = "SELECT service_requests.*, users.name AS guest_name, rooms.room_number
            FROM service_requests
            INNER JOIN users ON service_requests.guest_id = users.id
            INNER JOIN rooms ON service_requests.room_id = rooms.id
            WHERE service_requests.status = 'pending'
            OR service_requests.status = 'in_progress'
            ORDER BY service_requests.requested_at DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $requests = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $requests[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $requests;
}

function updateReceptionistServiceRequestStatus($requestId, $status) {
    $conn = getConnection();

    $sql = "UPDATE service_requests
            SET status = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $requestId);
    mysqli_stmt_execute($stmt);

    $updated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $updated = true;
    }

    mysqli_stmt_close($stmt);

    return $updated;
}

function searchReceptionistPaymentBills($keyword) {
    $conn = getConnection();

    $keywordLike = "%" . $keyword . "%";

    $sql = "SELECT billing.*, users.name AS guest_name, users.email,
                   bookings.status AS booking_status,
                   room_types.name AS room_type_name
            FROM billing
            INNER JOIN users ON billing.guest_id = users.id
            INNER JOIN bookings ON billing.booking_id = bookings.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE billing.payment_status = 'pending'
            AND (billing.booking_id = ? OR users.name LIKE ?)
            ORDER BY billing.id DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keywordLike);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $bills = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bills[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $bills;
}

function getReceptionistBillById($billingId) {
    $conn = getConnection();

    $sql = "SELECT * FROM billing
            WHERE id = ?
            AND payment_status = 'pending'
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $billingId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $bill = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $bill = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $bill;
}

function markReceptionistBillPaid($billingId, $paymentMethod) {
    $conn = getConnection();

    $paymentStatus = "paid";

    $sql = "UPDATE billing
            SET payment_method = ?,
                payment_status = ?,
                paid_at = NOW()
            WHERE id = ?
            AND payment_status = 'pending'";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $paymentMethod, $paymentStatus, $billingId);
    mysqli_stmt_execute($stmt);

    $updated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $updated = true;
    }

    mysqli_stmt_close($stmt);

    return $updated;
}

function getReceptionistDailyReport() {
    $conn = getConnection();

    $report = array(
        "arrivals" => 0,
        "departures" => 0,
        "revenue" => 0,
        "occupied_rooms" => 0,
        "available_rooms" => 0
    );

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE checkin_date = CURDATE()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["arrivals"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE checkout_date = CURDATE()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["departures"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT SUM(total_amount) AS total FROM billing WHERE payment_status = 'paid' AND DATE(paid_at) = CURDATE()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($row["total"] !== null) {
            $report["revenue"] = $row["total"];
        }
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'occupied'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["occupied_rooms"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'available'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["available_rooms"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    return $report;
}

?>
