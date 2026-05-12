<?php

require_once __DIR__ . "/../config/Database.php";

function getGuestBillingHistory($guestId) {
    $conn = getConnection();

    $sql = "SELECT billing.*, bookings.checkin_date, bookings.checkout_date,
                   bookings.status AS booking_status,
                   room_types.name AS room_type_name
            FROM billing
            INNER JOIN bookings ON billing.booking_id = bookings.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE billing.guest_id = ?
            ORDER BY billing.id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $guestId);

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

function getGuestBillingForRedeem($billingId, $guestId) {
    $conn = getConnection();

    $sql = "SELECT * FROM billing
            WHERE id = ?
            AND guest_id = ?
            AND payment_status = 'pending'
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $billingId, $guestId);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $bill = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $bill = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $bill;
}

function redeemGuestPointsForBill($billingId, $guestId, $bookingId, $discountAmount, $newBalance) {
    $conn = getConnection();

    $sql = "UPDATE billing
            SET discount_amount = discount_amount + ?,
                total_amount = total_amount - ?
            WHERE id = ?
            AND guest_id = ?
            AND payment_status = 'pending'
            AND discount_amount = 0";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ddii", $discountAmount, $discountAmount, $billingId, $guestId);

    mysqli_stmt_execute($stmt);

    $updated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $updated = true;
    }

    mysqli_stmt_close($stmt);

    if (!$updated) {
        return false;
    }

    $pointsEarned = 0;
    $pointsUsed = (int)$discountAmount;

    $sql = "INSERT INTO loyalty_points
            (guest_id, booking_id, points_earned, points_used, balance)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "iiiii", $guestId, $bookingId, $pointsEarned, $pointsUsed, $newBalance);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function getGuestReceiptDetails($billingId, $guestId) {
    $conn = getConnection();

    $sql = "SELECT billing.*, 
                   users.name AS guest_name,
                   users.email,
                   users.phone,
                   bookings.checkin_date,
                   bookings.checkout_date,
                   bookings.num_guests,
                   bookings.status AS booking_status,
                   room_types.name AS room_type_name
            FROM billing
            INNER JOIN users ON billing.guest_id = users.id
            INNER JOIN bookings ON billing.booking_id = bookings.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE billing.id = ?
            AND billing.guest_id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $billingId, $guestId);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $receipt = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $receipt = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $receipt;
}

?>