<?php

require_once __DIR__ . "/../config/Database.php";

function createGuestBooking($guestId, $roomTypeId, $checkinDate, $checkoutDate, $numGuests, $totalPrice) {
    $conn = getConnection();

    $status = "confirmed";
    $source = "online";
    $roomId = null;

    $sql = "INSERT INTO bookings
            (guest_id, room_id, room_type_id, checkin_date, checkout_date, num_guests, total_price, status, source)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiissidss",
        $guestId,
        $roomId,
        $roomTypeId,
        $checkinDate,
        $checkoutDate,
        $numGuests,
        $totalPrice,
        $status,
        $source
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if ($success) {
        $booking = getLatestGuestBooking($guestId, $roomTypeId, $checkinDate, $checkoutDate);

        if ($booking) {
            return $booking["id"];
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getLatestGuestBooking($guestId, $roomTypeId, $checkinDate, $checkoutDate) {
    $conn = getConnection();

    $sql = "SELECT * FROM bookings
            WHERE guest_id = ?
            AND room_type_id = ?
            AND checkin_date = ?
            AND checkout_date = ?
            ORDER BY id DESC
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "iiss", $guestId, $roomTypeId, $checkinDate, $checkoutDate);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $booking = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $booking = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $booking;
}

function createBookingBilling($bookingId, $guestId, $baseAmount, $totalAmount) {
    $conn = getConnection();

    $extrasAmount = 0;
    $discountAmount = 0;
    $paymentMethod = "";
    $paymentStatus = "pending";
    $receiptPath = "";

    $sql = "INSERT INTO billing
            (booking_id, guest_id, base_amount, extras_amount, discount_amount, total_amount, payment_method, payment_status, receipt_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiddddsss",
        $bookingId,
        $guestId,
        $baseAmount,
        $extrasAmount,
        $discountAmount,
        $totalAmount,
        $paymentMethod,
        $paymentStatus,
        $receiptPath
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function getGuestBookingConfirmation($bookingId, $guestId) {
    $conn = getConnection();

    $sql = "SELECT bookings.*, room_types.name AS room_type_name, room_types.description,
                   billing.total_amount, billing.payment_status
            FROM bookings
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            LEFT JOIN billing ON bookings.id = billing.booking_id
            WHERE bookings.id = ?
            AND bookings.guest_id = ?
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

?>