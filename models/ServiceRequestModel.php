<?php

require_once __DIR__ . "/../config/Database.php";

function getGuestActiveStays($guestId) {
    $conn = getConnection();

    $sql = "SELECT bookings.*, rooms.room_number, rooms.floor, room_types.name AS room_type_name
            FROM bookings
            INNER JOIN rooms ON bookings.room_id = rooms.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE bookings.guest_id = ?
            AND bookings.status = 'checked_in'
            ORDER BY bookings.checkin_date DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guestId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $stays = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $stays[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $stays;
}

function getGuestActiveBookingById($bookingId, $guestId) {
    $conn = getConnection();

    $sql = "SELECT * FROM bookings
            WHERE id = ?
            AND guest_id = ?
            AND status = 'checked_in'
            AND room_id IS NOT NULL
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $bookingId, $guestId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $booking = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $booking = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $booking;
}

function createGuestServiceRequest($bookingId, $guestId, $roomId, $serviceType, $description) {
    $conn = getConnection();

    $status = "pending";

    $sql = "INSERT INTO service_requests
            (booking_id, guest_id, room_id, service_type, description, status)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiisss",
        $bookingId,
        $guestId,
        $roomId,
        $serviceType,
        $description,
        $status
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function getGuestServiceRequests($guestId) {
    $conn = getConnection();

    $sql = "SELECT service_requests.*, rooms.room_number, bookings.checkin_date, bookings.checkout_date
            FROM service_requests
            INNER JOIN rooms ON service_requests.room_id = rooms.id
            INNER JOIN bookings ON service_requests.booking_id = bookings.id
            WHERE service_requests.guest_id = ?
            ORDER BY service_requests.requested_at DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guestId);
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

?>