<?php

require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/../models/RoomModel.php";

function searchAvailableRoomsAjax() {
    header("Content-Type: application/json");

    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"])) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Please login first."
        ));
        return;
    }

    if ($_SESSION["role"] !== "guest") {
        echo json_encode(array(
            "status" => "error",
            "message" => "Only guest can search rooms."
        ));
        return;
    }

    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    if (!$data) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Invalid request data."
        ));
        return;
    }

    if (isset($data["checkin_date"])) {
        $checkinDate = trim($data["checkin_date"]);
    } else {
        $checkinDate = "";
    }

    if (isset($data["checkout_date"])) {
        $checkoutDate = trim($data["checkout_date"]);
    } else {
        $checkoutDate = "";
    }

    if (isset($data["num_guests"])) {
        $numGuests = (int)$data["num_guests"];
    } else {
        $numGuests = 0;
    }

    if ($checkinDate === "" || $checkoutDate === "" || $numGuests <= 0) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Check-in date, check-out date, and number of guests are required."
        ));
        return;
    }

    if ($checkoutDate <= $checkinDate) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Check-out date must be after check-in date."
        ));
        return;
    }

    $roomTypes = searchAvailableRoomTypes($checkinDate, $checkoutDate, $numGuests);

    echo json_encode(array(
        "status" => "success",
        "message" => "Room search completed.",
        "rooms" => $roomTypes
    ));
}

?>