<?php

require_once __DIR__ . "/../config/Database.php";

function getSeasonalPriceForRoomType($roomTypeId, $checkinDate, $checkoutDate) {
    $conn = getConnection();

    $sql = "SELECT * FROM seasonal_pricing 
            WHERE room_type_id = ?
            AND end_date >= ?
            AND start_date <= ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "iss", $roomTypeId, $checkinDate, $checkoutDate);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $seasonalPrice = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $seasonalPrice = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $seasonalPrice;
}

function searchAvailableRoomTypes($checkinDate, $checkoutDate, $numGuests) {
    $conn = getConnection();

    $sql = "SELECT 
                room_types.id,
                room_types.name,
                room_types.description,
                room_types.price_per_night,
                room_types.max_capacity,
                room_types.thumbnail_path,
                room_types.amenities,

                (
                    SELECT COUNT(*) 
                    FROM rooms 
                    WHERE rooms.room_type_id = room_types.id
                    AND rooms.status = 'available'
                ) AS total_available_rooms,

                (
                    SELECT COUNT(*) 
                    FROM bookings 
                    WHERE bookings.room_type_id = room_types.id
                    AND bookings.status IN ('pending', 'confirmed', 'checked_in')
                    AND bookings.checkout_date > ?
                    AND bookings.checkin_date < ?
                ) AS booked_rooms

            FROM room_types
            WHERE room_types.max_capacity >= ?
            ORDER BY room_types.price_per_night ASC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssi", $checkinDate, $checkoutDate, $numGuests);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $availableRoomTypes = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $availableCount = $row["total_available_rooms"] - $row["booked_rooms"];

            if ($availableCount > 0) {
                $seasonalPrice = getSeasonalPriceForRoomType($row["id"], $checkinDate, $checkoutDate);

                $displayPrice = $row["price_per_night"];
                $seasonalLabel = "";

                if ($seasonalPrice) {
                    $displayPrice = $seasonalPrice["price_per_night"];
                    $seasonalLabel = $seasonalPrice["label"];
                }

                $amenities = json_decode($row["amenities"], true);

                if (!is_array($amenities)) {
                    $amenities = array();
                }

                $availableRoomTypes[] = array(
                    "id" => $row["id"],
                    "name" => $row["name"],
                    "description" => $row["description"],
                    "price_per_night" => $row["price_per_night"],
                    "display_price" => $displayPrice,
                    "seasonal_label" => $seasonalLabel,
                    "max_capacity" => $row["max_capacity"],
                    "thumbnail_path" => $row["thumbnail_path"],
                    "amenities" => $amenities,
                    "available_rooms" => $availableCount
                );
            }
        }
    }

    mysqli_stmt_close($stmt);

    return $availableRoomTypes;
}

?>