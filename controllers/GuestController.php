<?php

require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/GuestModel.php";
require_once __DIR__ . "/../models/RoomModel.php";
require_once __DIR__ . "/../models/BookingModel.php";
require_once __DIR__ . "/../models/ServiceRequestModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";
require_once __DIR__ . "/../models/BillingModel.php";

   //Guest Dashboard

function showGuestDashboard() {
    requireGuest();

    $guest = findUserById($_SESSION["user_id"]);

    if (!$guest) {
        $_SESSION["error"] = "Guest account not found.";
        redirect("index.php?route=login");
    }

    require __DIR__ . "/../views/guestDashboardView.php";
}

  // Guest Profile

function showGuestProfile() {
    requireGuest();

    syncGuestLoyaltyPoints($_SESSION["user_id"]);

    $guest = findUserById($_SESSION["user_id"]);
    $loyaltyBalance = getGuestLoyaltyBalance($_SESSION["user_id"]);
    $loyaltyHistory = getGuestLoyaltyHistory($_SESSION["user_id"]);

    if (!$guest) {
        $_SESSION["error"] = "Guest account not found.";
        redirect("index.php?route=login");
    }

    require __DIR__ . "/../views/guestProfileView.php";
}

function showGuestEditProfile() {
    requireGuest();

    $guest = findUserById($_SESSION["user_id"]);

    if (!$guest) {
        $_SESSION["error"] = "Guest account not found.";
        redirect("index.php?route=login");
    }

    require __DIR__ . "/../views/guestEditProfileView.php";
}

function handleGuestProfileUpdate() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-edit-profile");
    }

    if (isset($_POST["name"])) {
        $name = safeInput($_POST["name"]);
    } else {
        $name = "";
    }

    if (isset($_POST["phone"])) {
        $phone = safeInput($_POST["phone"]);
    } else {
        $phone = "";
    }

    if (isset($_POST["nationality"])) {
        $nationality = safeInput($_POST["nationality"]);
    } else {
        $nationality = "";
    }

    if (isset($_POST["id_number"])) {
        $idNumber = safeInput($_POST["id_number"]);
    } else {
        $idNumber = "";
    }

    if ($name === "" || $phone === "" || $nationality === "" || $idNumber === "") {
        $_SESSION["error"] = "All fields are required.";
        redirect("index.php?route=guest-edit-profile");
    }

    if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
        $_SESSION["error"] = "Name can contain only letters and spaces.";
        redirect("index.php?route=guest-edit-profile");
    }

    if (!preg_match("/^01[3-9][0-9]{8}$/", $phone)) {
        $_SESSION["error"] = "Phone must be 11 digits and start with 013 to 019.";
        redirect("index.php?route=guest-edit-profile");
    }

    if (!preg_match("/^[A-Za-z\s]+$/", $nationality)) {
        $_SESSION["error"] = "Nationality can contain only letters and spaces.";
        redirect("index.php?route=guest-edit-profile");
    }

    $success = updateGuestProfile($_SESSION["user_id"], $name, $phone, $nationality, $idNumber);

    if ($success) {
        $_SESSION["name"] = $name;
        $_SESSION["success"] = "Profile updated successfully.";
        redirect("index.php?route=guest-profile");
    } else {
        $_SESSION["error"] = "Profile update failed.";
        redirect("index.php?route=guest-edit-profile");
    }
}

function handleGuestProfilePictureUpload() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-profile");
    }

    if (!isset($_FILES["profile_pic"])) {
        $_SESSION["error"] = "Please select a profile picture.";
        redirect("index.php?route=guest-profile");
    }

    if ($_FILES["profile_pic"]["error"] !== 0) {
        $_SESSION["error"] = "File upload failed.";
        redirect("index.php?route=guest-profile");
    }

    $fileName = $_FILES["profile_pic"]["name"];
    $fileTmp = $_FILES["profile_pic"]["tmp_name"];
    $fileSize = $_FILES["profile_pic"]["size"];

    $fileInfo = pathinfo($fileName);
    $extension = "";

    if (isset($fileInfo["extension"])) {
        $extension = strtolower($fileInfo["extension"]);
    }

    if ($extension !== "jpg" && $extension !== "jpeg" && $extension !== "png") {
        $_SESSION["error"] = "Only JPG, JPEG, and PNG files are allowed.";
        redirect("index.php?route=guest-profile");
    }

    if ($fileSize > 2097152) {
        $_SESSION["error"] = "File size must be less than or equal to 2MB.";
        redirect("index.php?route=guest-profile");
    }

    $newFileName = "guest_" . $_SESSION["user_id"] . "_" . time() . "." . $extension;
    $targetPath = "uploads/" . $newFileName;

    $uploaded = move_uploaded_file($fileTmp, $targetPath);

    if (!$uploaded) {
        $_SESSION["error"] = "Could not save uploaded file.";
        redirect("index.php?route=guest-profile");
    }

    $success = updateGuestProfilePicture($_SESSION["user_id"], $targetPath);

    if ($success) {
        $_SESSION["success"] = "Profile picture updated successfully.";
    } else {
        $_SESSION["error"] = "Profile picture path could not be saved.";
    }

    redirect("index.php?route=guest-profile");
}

function showGuestChangePassword() {
    requireGuest();

    require __DIR__ . "/../views/guestChangePasswordView.php";
}

function handleGuestPasswordChange() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-change-password");
    }

    if (isset($_POST["current_password"])) {
        $currentPassword = $_POST["current_password"];
    } else {
        $currentPassword = "";
    }

    if (isset($_POST["new_password"])) {
        $newPassword = $_POST["new_password"];
    } else {
        $newPassword = "";
    }

    if (isset($_POST["confirm_password"])) {
        $confirmPassword = $_POST["confirm_password"];
    } else {
        $confirmPassword = "";
    }

    if ($currentPassword === "" || $newPassword === "" || $confirmPassword === "") {
        $_SESSION["error"] = "All password fields are required.";
        redirect("index.php?route=guest-change-password");
    }

    if (strlen($newPassword) < 6) {
        $_SESSION["error"] = "New password must be at least 6 characters.";
        redirect("index.php?route=guest-change-password");
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION["error"] = "New password and confirm password do not match.";
        redirect("index.php?route=guest-change-password");
    }

    $guest = findUserById($_SESSION["user_id"]);

    if (!$guest) {
        $_SESSION["error"] = "Guest account not found.";
        redirect("index.php?route=login");
    }

    $currentPasswordMatched = false;

    if (password_verify($currentPassword, $guest["password_hash"])) {
        $currentPasswordMatched = true;
    } else {
        if ($currentPassword === $guest["password_hash"]) {
            $currentPasswordMatched = true;
        }
    }

    if (!$currentPasswordMatched) {
        $_SESSION["error"] = "Current password is incorrect.";
        redirect("index.php?route=guest-change-password");
    }

    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $success = updateGuestPassword($_SESSION["user_id"], $newPasswordHash);

    if ($success) {
        session_unset();
        session_destroy();

        session_start();
        $_SESSION["success"] = "Password changed successfully. Please login again.";
        redirect("index.php?route=login");
    } else {
        $_SESSION["error"] = "Password change failed.";
        redirect("index.php?route=guest-change-password");
    }
}

   //Guest Room Search

function showGuestSearchRooms() {
    requireGuest();

    require __DIR__ . "/../views/guestSearchRoomView.php";
}

function showGuestRoomTypeDetails() {
    requireGuest();

    if (isset($_GET["room_type_id"])) {
        $roomTypeId = (int)$_GET["room_type_id"];
    } else {
        $roomTypeId = 0;
    }

    if (isset($_GET["checkin_date"])) {
        $checkinDate = safeInput($_GET["checkin_date"]);
    } else {
        $checkinDate = "";
    }

    if (isset($_GET["checkout_date"])) {
        $checkoutDate = safeInput($_GET["checkout_date"]);
    } else {
        $checkoutDate = "";
    }

    if (isset($_GET["num_guests"])) {
        $numGuests = (int)$_GET["num_guests"];
    } else {
        $numGuests = 0;
    }

    if ($roomTypeId <= 0) {
        $_SESSION["error"] = "Invalid room type.";
        redirect("index.php?route=guest-search-rooms");
    }

    $roomType = getRoomTypeDetailsById($roomTypeId);

    if (!$roomType) {
        $_SESSION["error"] = "Room type not found.";
        redirect("index.php?route=guest-search-rooms");
    }

    $images = getRoomTypeImages($roomType["name"]);

    $amenities = json_decode($roomType["amenities"], true);

    if (!is_array($amenities)) {
        $amenities = array();
    }

    require __DIR__ . "/../views/guestRoomTypeDetailView.php";
}

   //Guest Booking

function calculateNights($checkinDate, $checkoutDate) {
    $checkinTime = strtotime($checkinDate);
    $checkoutTime = strtotime($checkoutDate);

    $difference = $checkoutTime - $checkinTime;
    $nights = $difference / (60 * 60 * 24);

    return $nights;
}

function getBookingPriceInfo($roomTypeId, $checkinDate, $checkoutDate, $numGuests) {
    $availableRooms = searchAvailableRoomTypes($checkinDate, $checkoutDate, $numGuests);

    $selectedRoom = null;

    foreach ($availableRooms as $room) {
        if ($room["id"] == $roomTypeId) {
            $selectedRoom = $room;
        }
    }

    return $selectedRoom;
}

function showGuestBookRoom() {
    requireGuest();

    if (isset($_GET["room_type_id"])) {
        $roomTypeId = (int)$_GET["room_type_id"];
    } else {
        $roomTypeId = 0;
    }

    if (isset($_GET["checkin_date"])) {
        $checkinDate = safeInput($_GET["checkin_date"]);
    } else {
        $checkinDate = "";
    }

    if (isset($_GET["checkout_date"])) {
        $checkoutDate = safeInput($_GET["checkout_date"]);
    } else {
        $checkoutDate = "";
    }

    if (isset($_GET["num_guests"])) {
        $numGuests = (int)$_GET["num_guests"];
    } else {
        $numGuests = 0;
    }

    if ($roomTypeId <= 0 || $checkinDate === "" || $checkoutDate === "" || $numGuests <= 0) {
        $_SESSION["error"] = "Invalid booking request.";
        redirect("index.php?route=guest-search-rooms");
    }

    if ($checkoutDate <= $checkinDate) {
        $_SESSION["error"] = "Check-out date must be after check-in date.";
        redirect("index.php?route=guest-search-rooms");
    }

    $nights = calculateNights($checkinDate, $checkoutDate);

    if ($nights <= 0) {
        $_SESSION["error"] = "Invalid number of nights.";
        redirect("index.php?route=guest-search-rooms");
    }

    $selectedRoom = getBookingPriceInfo($roomTypeId, $checkinDate, $checkoutDate, $numGuests);

    if (!$selectedRoom) {
        $_SESSION["error"] = "Selected room type is not available.";
        redirect("index.php?route=guest-search-rooms");
    }

    $pricePerNight = $selectedRoom["display_price"];
    $totalPrice = $pricePerNight * $nights;

    require __DIR__ . "/../views/guestBookingView.php";
}

function handleGuestBookingConfirm() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-search-rooms");
    }

    if (isset($_POST["room_type_id"])) {
        $roomTypeId = (int)$_POST["room_type_id"];
    } else {
        $roomTypeId = 0;
    }

    if (isset($_POST["checkin_date"])) {
        $checkinDate = safeInput($_POST["checkin_date"]);
    } else {
        $checkinDate = "";
    }

    if (isset($_POST["checkout_date"])) {
        $checkoutDate = safeInput($_POST["checkout_date"]);
    } else {
        $checkoutDate = "";
    }

    if (isset($_POST["num_guests"])) {
        $numGuests = (int)$_POST["num_guests"];
    } else {
        $numGuests = 0;
    }

    if ($roomTypeId <= 0 || $checkinDate === "" || $checkoutDate === "" || $numGuests <= 0) {
        $_SESSION["error"] = "Invalid booking information.";
        redirect("index.php?route=guest-search-rooms");
    }

    if ($checkoutDate <= $checkinDate) {
        $_SESSION["error"] = "Check-out date must be after check-in date.";
        redirect("index.php?route=guest-search-rooms");
    }

    $nights = calculateNights($checkinDate, $checkoutDate);

    if ($nights <= 0) {
        $_SESSION["error"] = "Invalid number of nights.";
        redirect("index.php?route=guest-search-rooms");
    }

    $selectedRoom = getBookingPriceInfo($roomTypeId, $checkinDate, $checkoutDate, $numGuests);

    if (!$selectedRoom) {
        $_SESSION["error"] = "Room is no longer available.";
        redirect("index.php?route=guest-search-rooms");
    }

    $pricePerNight = $selectedRoom["display_price"];
    $totalPrice = $pricePerNight * $nights;

    $bookingId = createGuestBooking(
        $_SESSION["user_id"],
        $roomTypeId,
        $checkinDate,
        $checkoutDate,
        $numGuests,
        $totalPrice
    );

    if (!$bookingId) {
        $_SESSION["error"] = "Booking failed. Please try again.";
        redirect("index.php?route=guest-search-rooms");
    }

    createBookingBilling($bookingId, $_SESSION["user_id"], $totalPrice, $totalPrice);

    $_SESSION["success"] = "Room booked successfully.";
    redirect("index.php?route=guest-booking-confirmation&booking_id=" . $bookingId);
}

function showGuestBookingConfirmation() {
    requireGuest();

    if (isset($_GET["booking_id"])) {
        $bookingId = (int)$_GET["booking_id"];
    } else {
        $bookingId = 0;
    }

    if ($bookingId <= 0) {
        $_SESSION["error"] = "Invalid booking confirmation request.";
        redirect("index.php?route=guest-dashboard");
    }

    $booking = getGuestBookingConfirmation($bookingId, $_SESSION["user_id"]);

    if (!$booking) {
        $_SESSION["error"] = "Booking not found.";
        redirect("index.php?route=guest-dashboard");
    }

    require __DIR__ . "/../views/guestBookingConfirmationView.php";
}

  // Guest My Bookings

function showGuestMyBookings() {
    requireGuest();

    $bookings = getGuestBookingsWithDetails($_SESSION["user_id"]);

    require __DIR__ . "/../views/guestMyBookingsView.php";
}

function handleGuestCancelBooking() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-my-bookings");
    }

    if (isset($_POST["booking_id"])) {
        $bookingId = (int)$_POST["booking_id"];
    } else {
        $bookingId = 0;
    }

    if ($bookingId <= 0) {
        $_SESSION["error"] = "Invalid booking.";
        redirect("index.php?route=guest-my-bookings");
    }

    $booking = getGuestBookingForCancel($bookingId, $_SESSION["user_id"]);

    if (!$booking) {
        $_SESSION["error"] = "Booking not found.";
        redirect("index.php?route=guest-my-bookings");
    }

    if ($booking["status"] !== "confirmed") {
        $_SESSION["error"] = "Only confirmed bookings can be cancelled.";
        redirect("index.php?route=guest-my-bookings");
    }

    $todayTime = strtotime(date("Y-m-d"));
    $checkinTime = strtotime($booking["checkin_date"]);

    if ($checkinTime <= $todayTime) {
        $_SESSION["error"] = "Booking cannot be cancelled on or after check-in date.";
        redirect("index.php?route=guest-my-bookings");
    }

    $success = cancelGuestConfirmedBooking($bookingId, $_SESSION["user_id"]);

    if ($success) {
        $_SESSION["success"] = "Booking cancelled successfully.";
    } else {
        $_SESSION["error"] = "Booking cancellation failed.";
    }

    redirect("index.php?route=guest-my-bookings");
}

   //Guest Service Requests

function showGuestServiceRequests() {
    requireGuest();

    $activeStays = getGuestActiveStays($_SESSION["user_id"]);
    $requests = getGuestServiceRequests($_SESSION["user_id"]);

    require __DIR__ . "/../views/guestServiceRequestView.php";
}

function handleGuestServiceRequestSubmit() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-service-requests");
    }

    if (isset($_POST["booking_id"])) {
        $bookingId = (int)$_POST["booking_id"];
    } else {
        $bookingId = 0;
    }

    if (isset($_POST["service_type"])) {
        $serviceType = safeInput($_POST["service_type"]);
    } else {
        $serviceType = "";
    }

    if (isset($_POST["description"])) {
        $description = safeInput($_POST["description"]);
    } else {
        $description = "";
    }

    if ($bookingId <= 0 || $serviceType === "" || $description === "") {
        $_SESSION["error"] = "Booking, service type, and description are required.";
        redirect("index.php?route=guest-service-requests");
    }

    if ($serviceType !== "extra_bed" && $serviceType !== "toiletries" && $serviceType !== "laundry" && $serviceType !== "room_service" && $serviceType !== "other") {
        $_SESSION["error"] = "Invalid service type.";
        redirect("index.php?route=guest-service-requests");
    }

    $booking = getGuestActiveBookingById($bookingId, $_SESSION["user_id"]);

    if (!$booking) {
        $_SESSION["error"] = "Active stay not found. Service request is allowed only during checked-in stay.";
        redirect("index.php?route=guest-service-requests");
    }

    $success = createGuestServiceRequest(
        $bookingId,
        $_SESSION["user_id"],
        $booking["room_id"],
        $serviceType,
        $description
    );

    if ($success) {
        $_SESSION["success"] = "Service request submitted successfully.";
    } else {
        $_SESSION["error"] = "Service request submission failed.";
    }

    redirect("index.php?route=guest-service-requests");
}

   //Guest Reviews

function showGuestReviews() {
    requireGuest();

    $reviewItems = getGuestCompletedBookingsForReview($_SESSION["user_id"]);

    require __DIR__ . "/../views/guestReviewView.php";
}

function handleGuestReviewSubmit() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-reviews");
    }

    if (isset($_POST["booking_id"])) {
        $bookingId = (int)$_POST["booking_id"];
    } else {
        $bookingId = 0;
    }

    if (isset($_POST["overall_rating"])) {
        $overallRating = (int)$_POST["overall_rating"];
    } else {
        $overallRating = 0;
    }

    if (isset($_POST["cleanliness_rating"])) {
        $cleanlinessRating = (int)$_POST["cleanliness_rating"];
    } else {
        $cleanlinessRating = 0;
    }

    if (isset($_POST["service_rating"])) {
        $serviceRating = (int)$_POST["service_rating"];
    } else {
        $serviceRating = 0;
    }

    if (isset($_POST["review_text"])) {
        $reviewText = safeInput($_POST["review_text"]);
    } else {
        $reviewText = "";
    }

    if ($bookingId <= 0 || $overallRating < 1 || $overallRating > 5 || $cleanlinessRating < 1 || $cleanlinessRating > 5 || $serviceRating < 1 || $serviceRating > 5 || $reviewText === "") {
        $_SESSION["error"] = "All review fields are required and ratings must be 1 to 5.";
        redirect("index.php?route=guest-reviews");
    }

    if (guestReviewExists($bookingId, $_SESSION["user_id"])) {
        $_SESSION["error"] = "Review already exists for this booking.";
        redirect("index.php?route=guest-reviews");
    }

    $success = createGuestReview($bookingId, $_SESSION["user_id"], $overallRating, $cleanlinessRating, $serviceRating, $reviewText);

    if ($success) {
        $_SESSION["success"] = "Review submitted successfully.";
    } else {
        $_SESSION["error"] = "Review submission failed.";
    }

    redirect("index.php?route=guest-reviews");
}

function handleGuestReviewUpdate() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-reviews");
    }

    if (isset($_POST["review_id"])) {
        $reviewId = (int)$_POST["review_id"];
    } else {
        $reviewId = 0;
    }

    if (isset($_POST["overall_rating"])) {
        $overallRating = (int)$_POST["overall_rating"];
    } else {
        $overallRating = 0;
    }

    if (isset($_POST["cleanliness_rating"])) {
        $cleanlinessRating = (int)$_POST["cleanliness_rating"];
    } else {
        $cleanlinessRating = 0;
    }

    if (isset($_POST["service_rating"])) {
        $serviceRating = (int)$_POST["service_rating"];
    } else {
        $serviceRating = 0;
    }

    if (isset($_POST["review_text"])) {
        $reviewText = safeInput($_POST["review_text"]);
    } else {
        $reviewText = "";
    }

    if ($reviewId <= 0 || $overallRating < 1 || $overallRating > 5 || $cleanlinessRating < 1 || $cleanlinessRating > 5 || $serviceRating < 1 || $serviceRating > 5 || $reviewText === "") {
        $_SESSION["error"] = "Invalid review update request.";
        redirect("index.php?route=guest-reviews");
    }

    $success = updateGuestReview($reviewId, $_SESSION["user_id"], $overallRating, $cleanlinessRating, $serviceRating, $reviewText);

    if ($success) {
        $_SESSION["success"] = "Review updated successfully.";
    } else {
        $_SESSION["error"] = "Review update failed.";
    }

    redirect("index.php?route=guest-reviews");
}

function handleGuestReviewDelete() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-reviews");
    }

    if (isset($_POST["review_id"])) {
        $reviewId = (int)$_POST["review_id"];
    } else {
        $reviewId = 0;
    }

    if ($reviewId <= 0) {
        $_SESSION["error"] = "Invalid review.";
        redirect("index.php?route=guest-reviews");
    }

    $success = deleteGuestReview($reviewId, $_SESSION["user_id"]);

    if ($success) {
        $_SESSION["success"] = "Review deleted successfully.";
    } else {
        $_SESSION["error"] = "Review delete failed.";
    }

    redirect("index.php?route=guest-reviews");
}

   //Guest Billing / Receipt

function showGuestBillingHistory() {
    requireGuest();

    syncGuestLoyaltyPoints($_SESSION["user_id"]);

    $bills = getGuestBillingHistory($_SESSION["user_id"]);
    $loyaltyBalance = getGuestLoyaltyBalance($_SESSION["user_id"]);

    require __DIR__ . "/../views/guestBillingHistoryView.php";
}

function handleGuestRedeemPoints() {
    requireGuest();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=guest-billing-history");
    }

    if (isset($_POST["billing_id"])) {
        $billingId = (int)$_POST["billing_id"];
    } else {
        $billingId = 0;
    }

    if ($billingId <= 0) {
        $_SESSION["error"] = "Invalid billing request.";
        redirect("index.php?route=guest-billing-history");
    }

    $bill = getGuestBillingForRedeem($billingId, $_SESSION["user_id"]);

    if (!$bill) {
        $_SESSION["error"] = "Pending bill not found.";
        redirect("index.php?route=guest-billing-history");
    }

    if ($bill["discount_amount"] > 0) {
        $_SESSION["error"] = "Points already redeemed for this bill.";
        redirect("index.php?route=guest-billing-history");
    }

    $balance = getGuestLoyaltyBalance($_SESSION["user_id"]);

    if ($balance <= 0) {
        $_SESSION["error"] = "No loyalty points available.";
        redirect("index.php?route=guest-billing-history");
    }

    $discountAmount = $balance;

    if ($discountAmount > $bill["total_amount"]) {
        $discountAmount = $bill["total_amount"];
    }

    $newBalance = $balance - $discountAmount;

    $success = redeemGuestPointsForBill(
        $billingId,
        $_SESSION["user_id"],
        $bill["booking_id"],
        $discountAmount,
        $newBalance
    );

    if ($success) {
        $_SESSION["success"] = "Loyalty points redeemed successfully.";
    } else {
        $_SESSION["error"] = "Loyalty point redemption failed.";
    }

    redirect("index.php?route=guest-billing-history");
}

function showGuestReceipt() {
    requireGuest();

    if (isset($_GET["billing_id"])) {
        $billingId = (int)$_GET["billing_id"];
    } else {
        $billingId = 0;
    }

    if ($billingId <= 0) {
        $_SESSION["error"] = "Invalid receipt request.";
        redirect("index.php?route=guest-billing-history");
    }

    $receipt = getGuestReceiptDetails($billingId, $_SESSION["user_id"]);

    if (!$receipt) {
        $_SESSION["error"] = "Receipt not found.";
        redirect("index.php?route=guest-billing-history");
    }

    require __DIR__ . "/../views/guestReceiptView.php";
}

function downloadGuestReceipt() {
    requireGuest();

    if (isset($_GET["billing_id"])) {
        $billingId = (int)$_GET["billing_id"];
    } else {
        $billingId = 0;
    }

    if ($billingId <= 0) {
        $_SESSION["error"] = "Invalid receipt download request.";
        redirect("index.php?route=guest-billing-history");
    }

    $receipt = getGuestReceiptDetails($billingId, $_SESSION["user_id"]);

    if (!$receipt) {
        $_SESSION["error"] = "Receipt not found.";
        redirect("index.php?route=guest-billing-history");
    }

    $fileName = "receipt_" . $receipt["id"] . ".html";

    header("Content-Type: text/html");
    header("Content-Disposition: attachment; filename=" . $fileName);

    echo "<html>";
    echo "<head><title>Receipt</title></head>";
    echo "<body>";
    echo "<h2>Hotel Room Booking Receipt</h2>";
    echo "<p><strong>Bill ID:</strong> " . $receipt["id"] . "</p>";
    echo "<p><strong>Booking ID:</strong> " . $receipt["booking_id"] . "</p>";
    echo "<p><strong>Guest:</strong> " . htmlspecialchars($receipt["guest_name"]) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($receipt["email"]) . "</p>";
    echo "<p><strong>Room Type:</strong> " . htmlspecialchars($receipt["room_type_name"]) . "</p>";
    echo "<p><strong>Check-in:</strong> " . $receipt["checkin_date"] . "</p>";
    echo "<p><strong>Check-out:</strong> " . $receipt["checkout_date"] . "</p>";
    echo "<p><strong>Base Amount:</strong> " . $receipt["base_amount"] . " BDT</p>";
    echo "<p><strong>Extras:</strong> " . $receipt["extras_amount"] . " BDT</p>";
    echo "<p><strong>Discount:</strong> " . $receipt["discount_amount"] . " BDT</p>";
    echo "<p><strong>Total:</strong> " . $receipt["total_amount"] . " BDT</p>";
    echo "<p><strong>Payment Status:</strong> " . htmlspecialchars($receipt["payment_status"]) . "</p>";
    echo "</body>";
    echo "</html>";

    exit();
}

?>