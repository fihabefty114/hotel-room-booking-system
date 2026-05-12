<?php

require_once __DIR__ . "/../config/Database.php";

function updateGuestProfile($userId, $name, $phone, $nationality, $idNumber) {
    $conn = getConnection();

    $sql = "UPDATE users 
            SET name = ?, phone = ?, nationality = ?, id_number = ?
            WHERE id = ? AND role = 'guest'";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssssi", $name, $phone, $nationality, $idNumber, $userId);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function updateGuestProfilePicture($userId, $profilePicPath) {
    $conn = getConnection();

    $sql = "UPDATE users 
            SET profile_pic = ?
            WHERE id = ? AND role = 'guest'";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "si", $profilePicPath, $userId);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function updateGuestPassword($userId, $passwordHash) {
    $conn = getConnection();

    $sql = "UPDATE users 
            SET password_hash = ?
            WHERE id = ? AND role = 'guest'";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "si", $passwordHash, $userId);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function getGuestLoyaltyBalance($guestId) {
    $conn = getConnection();

    $sql = "SELECT balance 
            FROM loyalty_points 
            WHERE guest_id = ?
            ORDER BY id DESC 
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $guestId);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $balance = 0;

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $balance = $row["balance"];
    }

    mysqli_stmt_close($stmt);

    return $balance;
}

function getGuestLoyaltyHistory($guestId) {
    $conn = getConnection();

    $sql = "SELECT loyalty_points.*, bookings.checkin_date, bookings.checkout_date
            FROM loyalty_points
            LEFT JOIN bookings ON loyalty_points.booking_id = bookings.id
            WHERE loyalty_points.guest_id = ?
            ORDER BY loyalty_points.created_at DESC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $guestId);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $history = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $history[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $history;
}

?>