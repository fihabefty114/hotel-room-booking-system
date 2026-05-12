<?php

require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/../models/ReceptionistModel.php";

function requireReceptionist() {
    requireRole("receptionist");
}

function showReceptionistDashboard() {
    requireReceptionist();

    $stats = getReceptionistDashboardStats();

    require __DIR__ . "/../views/receptionistDashboardView.php";
}

function showReceptionistCheckInPage() {
    requireReceptionist();

    $keyword = "";
    $bookingData = array();

    if (isset($_GET["keyword"])) {
        $keyword = trim($_GET["keyword"]);

        if ($keyword !== "") {
            $bookings = searchReceptionistCheckInBookings($keyword);

            foreach ($bookings as $booking) {
                $availableRooms = getAvailableRoomsByRoomType($booking["room_type_id"]);

                $bookingData[] = array(
                    "booking" => $booking,
                    "available_rooms" => $availableRooms
                );
            }
        }
    }

    require __DIR__ . "/../views/receptionistCheckInView.php";
}

function processReceptionistCheckIn() {
    requireReceptionist();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=receptionist-check-in");
    }

    if (isset($_POST["booking_id"])) {
        $bookingId = (int)$_POST["booking_id"];
    } else {
        $bookingId = 0;
    }

    if (isset($_POST["room_id"])) {
        $roomId = (int)$_POST["room_id"];
    } else {
        $roomId = 0;
    }

    if ($bookingId <= 0 || $roomId <= 0) {
        $_SESSION["error"] = "Booking and room selection are required.";
        redirect("index.php?route=receptionist-check-in");
    }

    $success = completeGuestCheckIn($bookingId, $roomId);

    if ($success) {
        $_SESSION["success"] = "Guest checked in successfully.";
    } else {
        $_SESSION["error"] = "Check-in failed. Room may not be available.";
    }

    redirect("index.php?route=receptionist-check-in");
}

function showReceptionistCheckOutPage() {
    requireReceptionist();

    $keyword = "";
    $bookings = array();

    if (isset($_GET["keyword"])) {
        $keyword = trim($_GET["keyword"]);

        if ($keyword !== "") {
            $bookings = searchReceptionistCheckOutBookings($keyword);
        }
    }

    require __DIR__ . "/../views/receptionistCheckOutView.php";
}

function processReceptionistCheckOut() {
    requireReceptionist();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=receptionist-check-out");
    }

    if (isset($_POST["booking_id"])) {
        $bookingId = (int)$_POST["booking_id"];
    } else {
        $bookingId = 0;
    }

    if (isset($_POST["room_id"])) {
        $roomId = (int)$_POST["room_id"];
    } else {
        $roomId = 0;
    }

    if ($bookingId <= 0 || $roomId <= 0) {
        $_SESSION["error"] = "Invalid checkout request.";
        redirect("index.php?route=receptionist-check-out");
    }

    $success = completeGuestCheckOut($bookingId, $roomId);

    if ($success) {
        $_SESSION["success"] = "Guest checked out successfully. Room is now marked as dirty.";
    } else {
        $_SESSION["error"] = "Check-out failed.";
    }

    redirect("index.php?route=receptionist-check-out");
}

function showReceptionistRoomStatusPage() {
    requireReceptionist();

    require __DIR__ . "/../views/receptionistRoomStatusView.php";
}

function getReceptionistRoomStatusAjax() {
    requireReceptionist();

    $rooms = getReceptionistRoomStatusBoard();

    header("Content-Type: application/json");
    echo json_encode($rooms);
}

function showReceptionistServiceRequestsPage() {
    requireReceptionist();

    $requests = getReceptionistServiceRequests();

    require __DIR__ . "/../views/receptionistServiceRequestsView.php";
}

function processReceptionistServiceRequestUpdate() {
    requireReceptionist();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=receptionist-service-requests");
    }

    if (isset($_POST["request_id"])) {
        $requestId = (int)$_POST["request_id"];
    } else {
        $requestId = 0;
    }

    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    } else {
        $status = "";
    }

    if ($requestId <= 0) {
        $_SESSION["error"] = "Invalid service request.";
        redirect("index.php?route=receptionist-service-requests");
    }

    if ($status !== "pending" && $status !== "in_progress" && $status !== "completed") {
        $_SESSION["error"] = "Invalid service request status.";
        redirect("index.php?route=receptionist-service-requests");
    }

    $success = updateReceptionistServiceRequestStatus($requestId, $status);

    if ($success) {
        $_SESSION["success"] = "Service request updated successfully.";
    } else {
        $_SESSION["error"] = "Service request update failed.";
    }

    redirect("index.php?route=receptionist-service-requests");
}

function showReceptionistPaymentPage() {
    requireReceptionist();

    $keyword = "";
    $bills = array();

    if (isset($_GET["keyword"])) {
        $keyword = trim($_GET["keyword"]);

        if ($keyword !== "") {
            $bills = searchReceptionistPaymentBills($keyword);
        }
    }

    require __DIR__ . "/../views/receptionistPaymentView.php";
}

function processReceptionistPayment() {
    requireReceptionist();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=receptionist-payments");
    }

    if (isset($_POST["billing_id"])) {
        $billingId = (int)$_POST["billing_id"];
    } else {
        $billingId = 0;
    }

    if (isset($_POST["payment_method"])) {
        $paymentMethod = trim($_POST["payment_method"]);
    } else {
        $paymentMethod = "";
    }

    if ($billingId <= 0 || $paymentMethod === "") {
        $_SESSION["error"] = "Billing ID and payment method are required.";
        redirect("index.php?route=receptionist-payments");
    }

    if ($paymentMethod !== "cash" && $paymentMethod !== "card" && $paymentMethod !== "mobile_banking") {
        $_SESSION["error"] = "Invalid payment method.";
        redirect("index.php?route=receptionist-payments");
    }

    $bill = getReceptionistBillById($billingId);

    if (!$bill) {
        $_SESSION["error"] = "Pending bill not found.";
        redirect("index.php?route=receptionist-payments");
    }

    $success = markReceptionistBillPaid($billingId, $paymentMethod);

    if ($success) {
        $_SESSION["success"] = "Payment recorded successfully.";
    } else {
        $_SESSION["error"] = "Payment could not be recorded.";
    }

    redirect("index.php?route=receptionist-payments");
}

function showReceptionistDailyReport() {
    requireReceptionist();

    $report = getReceptionistDailyReport();

    require __DIR__ . "/../views/receptionistDailyReportView.php";
}

?>