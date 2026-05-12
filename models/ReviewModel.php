<?php

require_once __DIR__ . "/../config/Database.php";

function getGuestCompletedBookingsForReview($guestId) {
    $conn = getConnection();

    $sql = "SELECT bookings.id AS booking_id,
                   bookings.checkin_date,
                   bookings.checkout_date,
                   bookings.status AS booking_status,
                   room_types.name AS room_type_name,
                   reviews.id AS review_id,
                   reviews.overall_rating,
                   reviews.cleanliness_rating,
                   reviews.service_rating,
                   reviews.review_text,
                   reviews.created_at
            FROM bookings
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            LEFT JOIN reviews ON bookings.id = reviews.booking_id
            WHERE bookings.guest_id = ?
            AND bookings.status = 'checked_out'
            ORDER BY bookings.checkout_date DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $guestId);
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

function guestReviewExists($bookingId, $guestId) {
    $conn = getConnection();

    $sql = "SELECT id FROM reviews
            WHERE booking_id = ?
            AND guest_id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $bookingId, $guestId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $exists = false;

    if ($result && mysqli_num_rows($result) > 0) {
        $exists = true;
    }

    mysqli_stmt_close($stmt);

    return $exists;
}

function createGuestReview($bookingId, $guestId, $overallRating, $cleanlinessRating, $serviceRating, $reviewText) {
    $conn = getConnection();

    $sql = "INSERT INTO reviews
            (booking_id, guest_id, overall_rating, cleanliness_rating, service_rating, review_text)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiiiis",
        $bookingId,
        $guestId,
        $overallRating,
        $cleanlinessRating,
        $serviceRating,
        $reviewText
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function updateGuestReview($reviewId, $guestId, $overallRating, $cleanlinessRating, $serviceRating, $reviewText) {
    $conn = getConnection();

    $sql = "UPDATE reviews
            SET overall_rating = ?, cleanliness_rating = ?, service_rating = ?, review_text = ?
            WHERE id = ?
            AND guest_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiisii",
        $overallRating,
        $cleanlinessRating,
        $serviceRating,
        $reviewText,
        $reviewId,
        $guestId
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function deleteGuestReview($reviewId, $guestId) {
    $conn = getConnection();

    $sql = "DELETE FROM reviews
            WHERE id = ?
            AND guest_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $reviewId, $guestId);

    mysqli_stmt_execute($stmt);

    if (mysqli_affected_rows($conn) > 0) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

?>