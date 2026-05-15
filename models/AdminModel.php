<?php

require_once __DIR__ . "/../config/Database.php";

function getAdminDashboardStats() {
    $conn = getConnection();

    $stats = array(
        "total_rooms" => 0,
        "available_rooms" => 0,
        "occupied_rooms" => 0,
        "dirty_rooms" => 0,
        "total_guests" => 0,
        "total_bookings" => 0,
        "pending_payments" => 0,
        "today_revenue" => 0
    );

    $sql = "SELECT COUNT(*) AS total FROM rooms";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["total_rooms"] = $row["total"];
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

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'occupied'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["occupied_rooms"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'dirty'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["dirty_rooms"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'guest'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["total_guests"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM bookings";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["total_bookings"] = $row["total"];
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

    $sql = "SELECT SUM(total_amount) AS total FROM billing WHERE payment_status = 'paid' AND DATE(paid_at) = CURDATE()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row["total"] !== null) {
            $stats["today_revenue"] = $row["total"];
        }
    }
    mysqli_stmt_close($stmt);

    return $stats;
}

/* =========================
   Room Types
========================= */

function getAdminRoomTypes() {
    $conn = getConnection();

    $sql = "SELECT * FROM room_types ORDER BY id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $roomTypes = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $roomTypes[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $roomTypes;
}

function createAdminRoomType($name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities) {
    $conn = getConnection();

    $sql = "INSERT INTO room_types
            (name, description, price_per_night, max_capacity, thumbnail_path, amenities)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssdiss", $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function updateAdminRoomType($id, $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities) {
    $conn = getConnection();

    $sql = "UPDATE room_types
            SET name = ?, description = ?, price_per_night = ?, max_capacity = ?, thumbnail_path = ?, amenities = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssdissi", $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities, $id);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function deleteAdminRoomType($id) {
    $conn = getConnection();

    $sql = "DELETE FROM room_types WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $success = false;

    if (mysqli_affected_rows($conn) > 0) {
        $success = true;
    }

    mysqli_stmt_close($stmt);

    return $success;
}

/* =========================
   Rooms
========================= */

function getAdminRooms() {
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

function createAdminRoom($roomTypeId, $roomNumber, $floor, $status, $notes) {
    $conn = getConnection();

    $sql = "INSERT INTO rooms
            (room_type_id, room_number, floor, status, notes)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "isiss", $roomTypeId, $roomNumber, $floor, $status, $notes);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function updateAdminRoom($id, $roomTypeId, $roomNumber, $floor, $status, $notes) {
    $conn = getConnection();

    $sql = "UPDATE rooms
            SET room_type_id = ?, room_number = ?, floor = ?, status = ?, notes = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "isissi", $roomTypeId, $roomNumber, $floor, $status, $notes, $id);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function deleteAdminRoom($id) {
    $conn = getConnection();

    $sql = "DELETE FROM rooms WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $success = false;

    if (mysqli_affected_rows($conn) > 0) {
        $success = true;
    }

    mysqli_stmt_close($stmt);

    return $success;
}

/* =========================
   Seasonal Pricing
========================= */

function getAdminSeasonalPricing() {
    $conn = getConnection();

    $sql = "SELECT seasonal_pricing.*, room_types.name AS room_type_name
            FROM seasonal_pricing
            INNER JOIN room_types ON seasonal_pricing.room_type_id = room_types.id
            ORDER BY seasonal_pricing.start_date DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $items = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $items;
}

function createAdminSeasonalPricing($roomTypeId, $label, $startDate, $endDate, $pricePerNight) {
    $conn = getConnection();

    $sql = "INSERT INTO seasonal_pricing
            (room_type_id, label, start_date, end_date, price_per_night)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "isssd", $roomTypeId, $label, $startDate, $endDate, $pricePerNight);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function updateAdminSeasonalPricing($id, $roomTypeId, $label, $startDate, $endDate, $pricePerNight) {
    $conn = getConnection();

    $sql = "UPDATE seasonal_pricing
            SET room_type_id = ?, label = ?, start_date = ?, end_date = ?, price_per_night = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "isssdi", $roomTypeId, $label, $startDate, $endDate, $pricePerNight, $id);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function deleteAdminSeasonalPricing($id) {
    $conn = getConnection();

    $sql = "DELETE FROM seasonal_pricing WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $success = false;

    if (mysqli_affected_rows($conn) > 0) {
        $success = true;
    }

    mysqli_stmt_close($stmt);

    return $success;
}

/* =========================
   Staff
========================= */

function getAdminStaff() {
    $conn = getConnection();

    $sql = "SELECT * FROM users
            WHERE role = 'admin' OR role = 'receptionist' OR role = 'housekeeping'
            ORDER BY role ASC, id DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $staff = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $staff[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $staff;
}

function createAdminStaff($name, $email, $passwordHash, $phone, $nationality, $idNumber, $role) {
    $conn = getConnection();

    $profilePic = "";
    $isActive = 1;

    $sql = "INSERT INTO users
            (name, email, password_hash, phone, nationality, id_number, role, profile_pic, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssssssssi", $name, $email, $passwordHash, $phone, $nationality, $idNumber, $role, $profilePic, $isActive);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function updateAdminStaff($id, $name, $phone, $nationality, $idNumber, $role, $isActive) {
    $conn = getConnection();

    $sql = "UPDATE users
            SET name = ?, phone = ?, nationality = ?, id_number = ?, role = ?, is_active = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "sssssii", $name, $phone, $nationality, $idNumber, $role, $isActive, $id);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

/* =========================
   Guests + Bookings
========================= */

function getAdminGuests() {
    $conn = getConnection();

    $sql = "SELECT * FROM users WHERE role = 'guest' ORDER BY id DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $guests = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $guests[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $guests;
}

function updateAdminGuestStatus($id, $isActive) {
    $conn = getConnection();

    $sql = "UPDATE users SET is_active = ? WHERE id = ? AND role = 'guest'";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $isActive, $id);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function getAdminBookings() {
    $conn = getConnection();

    $sql = "SELECT bookings.*, users.name AS guest_name, users.email,
                   room_types.name AS room_type_name,
                   rooms.room_number
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            LEFT JOIN rooms ON bookings.room_id = rooms.id
            ORDER BY bookings.id DESC";

    $stmt = mysqli_prepare($conn, $sql);
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

function updateAdminBookingStatus($id, $status) {
    $conn = getConnection();

    $sql = "UPDATE bookings SET status = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

/* =========================
   Reviews + Reports
========================= */

function getAdminReviews() {
    $conn = getConnection();

    $sql = "SELECT reviews.*, users.name AS guest_name, room_types.name AS room_type_name
            FROM reviews
            INNER JOIN users ON reviews.guest_id = users.id
            INNER JOIN bookings ON reviews.booking_id = bookings.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            ORDER BY reviews.id DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $reviews = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $reviews;
}

function replyAdminReview($reviewId, $adminReply) {
    $conn = getConnection();

    $sql = "UPDATE reviews SET admin_reply = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "si", $adminReply, $reviewId);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function deleteAdminReview($reviewId) {
    $conn = getConnection();

    $sql = "DELETE FROM reviews WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $reviewId);

    mysqli_stmt_execute($stmt);

    $success = false;

    if (mysqli_affected_rows($conn) > 0) {
        $success = true;
    }

    mysqli_stmt_close($stmt);

    return $success;
}

function getAdminReports() {
    $conn = getConnection();

    $report = array(
        "total_revenue" => 0,
        "paid_bills" => 0,
        "pending_bills" => 0,
        "confirmed_bookings" => 0,
        "checked_in_bookings" => 0,
        "checked_out_bookings" => 0,
        "cancelled_bookings" => 0,
        "service_requests" => 0,
        "maintenance_reports" => 0
    );

    $sql = "SELECT SUM(total_amount) AS total FROM billing WHERE payment_status = 'paid'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row["total"] !== null) {
            $report["total_revenue"] = $row["total"];
        }
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM billing WHERE payment_status = 'paid'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["paid_bills"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM billing WHERE payment_status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["pending_bills"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'confirmed'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["confirmed_bookings"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'checked_in'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["checked_in_bookings"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'checked_out'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["checked_out_bookings"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'cancelled'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["cancelled_bookings"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM service_requests";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["service_requests"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM maintenance_reports";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["maintenance_reports"] = $row["total"];
    }
    mysqli_stmt_close($stmt);

    return $report;
}

?>