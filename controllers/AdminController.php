<?php

require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/../models/AdminModel.php";

function requireAdmin() {
    requireRole("admin");
}

function makeAmenitiesJson($amenitiesText) {
    $items = explode(",", $amenitiesText);
    $cleanItems = array();

    for ($i = 0; $i < count($items); $i++) {
        $item = trim($items[$i]);

        if ($item !== "") {
            $cleanItems[] = $item;
        }
    }

    return json_encode($cleanItems);
}
function uploadAdminRoomTypeThumbnail($fileInputName, $oldPath) {
    if (!isset($_FILES[$fileInputName])) {
        return $oldPath;
    }

    if ($_FILES[$fileInputName]["name"] === "") {
        return $oldPath;
    }

    if ($_FILES[$fileInputName]["error"] !== 0) {
        $_SESSION["error"] = "File upload error code: " . $_FILES[$fileInputName]["error"];
        return $oldPath;
    }

    $fileName = $_FILES[$fileInputName]["name"];
    $fileTmpName = $_FILES[$fileInputName]["tmp_name"];
    $fileSize = $_FILES[$fileInputName]["size"];

    $fileInfo = pathinfo($fileName);

    if (isset($fileInfo["extension"])) {
        $fileExt = strtolower($fileInfo["extension"]);
    } else {
        $_SESSION["error"] = "Invalid image file.";
        return $oldPath;
    }

    if ($fileExt !== "jpg" && $fileExt !== "jpeg" && $fileExt !== "png") {
        $_SESSION["error"] = "Only JPG, JPEG, and PNG images are allowed.";
        return $oldPath;
    }

    if ($fileSize > 10485760) {
        $_SESSION["error"] = "Image size must be less than 10MB.";
        return $oldPath;
    }

    $uploadFolder = __DIR__ . "/../assets/images/room_types/";

    if (!is_dir($uploadFolder)) {
        mkdir($uploadFolder, 0777, true);
    }

    if (!is_dir($uploadFolder)) {
        $_SESSION["error"] = "Upload folder could not be created.";
        return $oldPath;
    }

    $newFileName = "room_type_" . time() . "_" . rand(1000, 9999) . "." . $fileExt;
    $destination = $uploadFolder . $newFileName;

    if (move_uploaded_file($fileTmpName, $destination)) {
        return "assets/images/room_types/" . $newFileName;
    } else {
        $_SESSION["error"] = "Image upload failed. Check folder path or permission.";
        return $oldPath;
    }
}

/* Dashboard */

function showAdminDashboard() {
    requireAdmin();

    $stats = getAdminDashboardStats();

    require __DIR__ . "/../views/adminDashboardView.php";
}

/* Room Types */

function showAdminRoomTypes() {
    requireAdmin();

    $roomTypes = getAdminRoomTypes();

    require __DIR__ . "/../views/adminRoomTypesView.php";
}

function handleAdminRoomTypeCreate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-room-types");
    }

    $name = safeInput($_POST["name"]);
    $description = safeInput($_POST["description"]);
    $pricePerNight = (double)$_POST["price_per_night"];
    $maxCapacity = (int)$_POST["max_capacity"];
    $amenities = makeAmenitiesJson($_POST["amenities"]);

    $thumbnailPath = uploadAdminRoomTypeThumbnail("thumbnail_photo", "assets/images/room_default.jpg");

    if (isset($_SESSION["error"])) {
        redirect("index.php?route=admin-room-types");
    }

    if ($name === "" || $description === "" || $pricePerNight <= 0 || $maxCapacity <= 0) {
        $_SESSION["error"] = "Valid room type information is required.";
        redirect("index.php?route=admin-room-types");
    }

    $success = createAdminRoomType($name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities);

    if ($success) {
        $_SESSION["success"] = "Room type created successfully.";
    } else {
        $_SESSION["error"] = "Room type creation failed.";
    }

    redirect("index.php?route=admin-room-types");
}

function handleAdminRoomTypeUpdate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-room-types");
    }

    $id = (int)$_POST["id"];
    $name = safeInput($_POST["name"]);
    $description = safeInput($_POST["description"]);
    $pricePerNight = (double)$_POST["price_per_night"];
    $maxCapacity = (int)$_POST["max_capacity"];
    $amenities = makeAmenitiesJson($_POST["amenities"]);

    if (isset($_POST["old_thumbnail_path"])) {
        $oldThumbnailPath = safeInput($_POST["old_thumbnail_path"]);
    } else {
        $oldThumbnailPath = "assets/images/room_default.jpg";
    }

    $thumbnailPath = uploadAdminRoomTypeThumbnail("thumbnail_photo", $oldThumbnailPath);

    if (isset($_SESSION["error"])) {
        redirect("index.php?route=admin-room-types");
    }

    if ($name === "" || $description === "" || $pricePerNight <= 0 || $maxCapacity <= 0) {
        $_SESSION["error"] = "Valid room type information is required.";
        redirect("index.php?route=admin-room-types");
    }

    $success = updateAdminRoomType($id, $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities);

    if ($success) {
        $_SESSION["success"] = "Room type updated successfully.";
    } else {
        $_SESSION["error"] = "Room type update failed.";
    }

    redirect("index.php?route=admin-room-types");
}

function handleAdminRoomTypeDelete() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-room-types");
    }

    $id = (int)$_POST["id"];

    $success = deleteAdminRoomType($id);

    if ($success) {
        $_SESSION["success"] = "Room type deleted successfully.";
    } else {
        $_SESSION["error"] = "Room type delete failed. It may be used by rooms/bookings.";
    }

    redirect("index.php?route=admin-room-types");
}

/* Rooms */

function showAdminRooms() {
    requireAdmin();

    $rooms = getAdminRooms();
    $roomTypes = getAdminRoomTypes();

    require __DIR__ . "/../views/adminRoomsView.php";
}

function handleAdminRoomCreate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-rooms");
    }

    $roomTypeId = (int)$_POST["room_type_id"];
    $roomNumber = safeInput($_POST["room_number"]);
    $floor = (int)$_POST["floor"];
    $status = $_POST["status"];
    $notes = safeInput($_POST["notes"]);

    $success = createAdminRoom($roomTypeId, $roomNumber, $floor, $status, $notes);

    if ($success) {
        $_SESSION["success"] = "Room created successfully.";
    } else {
        $_SESSION["error"] = "Room creation failed.";
    }

    redirect("index.php?route=admin-rooms");
}

function handleAdminRoomUpdate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-rooms");
    }

    $id = (int)$_POST["id"];
    $roomTypeId = (int)$_POST["room_type_id"];
    $roomNumber = safeInput($_POST["room_number"]);
    $floor = (int)$_POST["floor"];
    $status = $_POST["status"];
    $notes = safeInput($_POST["notes"]);

    $success = updateAdminRoom($id, $roomTypeId, $roomNumber, $floor, $status, $notes);

    if ($success) {
        $_SESSION["success"] = "Room updated successfully.";
    } else {
        $_SESSION["error"] = "Room update failed.";
    }

    redirect("index.php?route=admin-rooms");
}

function handleAdminRoomDelete() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-rooms");
    }

    $id = (int)$_POST["id"];

    $success = deleteAdminRoom($id);

    if ($success) {
        $_SESSION["success"] = "Room deleted successfully.";
    } else {
        $_SESSION["error"] = "Room delete failed. It may be used by bookings.";
    }

    redirect("index.php?route=admin-rooms");
}

/* Seasonal Pricing */

function showAdminSeasonalPricing() {
    requireAdmin();

    $pricingList = getAdminSeasonalPricing();
    $roomTypes = getAdminRoomTypes();

    require __DIR__ . "/../views/adminSeasonalPricingView.php";
}

function handleAdminSeasonalPricingCreate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-seasonal-pricing");
    }

    $roomTypeId = (int)$_POST["room_type_id"];
    $label = safeInput($_POST["label"]);
    $startDate = safeInput($_POST["start_date"]);
    $endDate = safeInput($_POST["end_date"]);
    $pricePerNight = (double)$_POST["price_per_night"];

    $success = createAdminSeasonalPricing($roomTypeId, $label, $startDate, $endDate, $pricePerNight);

    if ($success) {
        $_SESSION["success"] = "Seasonal pricing created.";
    } else {
        $_SESSION["error"] = "Seasonal pricing creation failed.";
    }

    redirect("index.php?route=admin-seasonal-pricing");
}

function handleAdminSeasonalPricingUpdate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-seasonal-pricing");
    }

    $id = (int)$_POST["id"];
    $roomTypeId = (int)$_POST["room_type_id"];
    $label = safeInput($_POST["label"]);
    $startDate = safeInput($_POST["start_date"]);
    $endDate = safeInput($_POST["end_date"]);
    $pricePerNight = (double)$_POST["price_per_night"];

    $success = updateAdminSeasonalPricing($id, $roomTypeId, $label, $startDate, $endDate, $pricePerNight);

    if ($success) {
        $_SESSION["success"] = "Seasonal pricing updated.";
    } else {
        $_SESSION["error"] = "Seasonal pricing update failed.";
    }

    redirect("index.php?route=admin-seasonal-pricing");
}

function handleAdminSeasonalPricingDelete() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-seasonal-pricing");
    }

    $id = (int)$_POST["id"];

    $success = deleteAdminSeasonalPricing($id);

    if ($success) {
        $_SESSION["success"] = "Seasonal pricing deleted.";
    } else {
        $_SESSION["error"] = "Seasonal pricing delete failed.";
    }

    redirect("index.php?route=admin-seasonal-pricing");
}

/* Staff */

function showAdminStaff() {
    requireAdmin();

    $staffList = getAdminStaff();

    require __DIR__ . "/../views/adminStaffView.php";
}

function handleAdminStaffCreate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-staff");
    }

    $name = safeInput($_POST["name"]);
    $email = safeInput($_POST["email"]);
    $phone = safeInput($_POST["phone"]);
    $nationality = safeInput($_POST["nationality"]);
    $idNumber = safeInput($_POST["id_number"]);
    $role = $_POST["role"];
    $password = $_POST["password"];

    if ($role !== "admin" && $role !== "receptionist" && $role !== "housekeeping") {
        $_SESSION["error"] = "Invalid staff role.";
        redirect("index.php?route=admin-staff");
    }

    if (emailExists($email)) {
        $_SESSION["error"] = "Email already exists.";
        redirect("index.php?route=admin-staff");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $success = createAdminStaff($name, $email, $passwordHash, $phone, $nationality, $idNumber, $role);

    if ($success) {
        $_SESSION["success"] = "Staff created successfully.";
    } else {
        $_SESSION["error"] = "Staff creation failed.";
    }

    redirect("index.php?route=admin-staff");
}

function handleAdminStaffUpdate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-staff");
    }

    $id = (int)$_POST["id"];
    $name = safeInput($_POST["name"]);
    $phone = safeInput($_POST["phone"]);
    $nationality = safeInput($_POST["nationality"]);
    $idNumber = safeInput($_POST["id_number"]);
    $role = $_POST["role"];
    $isActive = (int)$_POST["is_active"];

    $success = updateAdminStaff($id, $name, $phone, $nationality, $idNumber, $role, $isActive);

    if ($success) {
        $_SESSION["success"] = "Staff updated.";
    } else {
        $_SESSION["error"] = "Staff update failed.";
    }

    redirect("index.php?route=admin-staff");
}

/* Guests + Bookings */

function showAdminGuests() {
    requireAdmin();

    $guests = getAdminGuests();

    require __DIR__ . "/../views/adminGuestsView.php";
}

function handleAdminGuestStatusUpdate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-guests");
    }

    $id = (int)$_POST["id"];
    $isActive = (int)$_POST["is_active"];

    $success = updateAdminGuestStatus($id, $isActive);

    if ($success) {
        $_SESSION["success"] = "Guest status updated.";
    } else {
        $_SESSION["error"] = "Guest status update failed.";
    }

    redirect("index.php?route=admin-guests");
}

function showAdminBookings() {
    requireAdmin();

    $bookings = getAdminBookings();

    require __DIR__ . "/../views/adminBookingsView.php";
}

function handleAdminBookingStatusUpdate() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-bookings");
    }

    $id = (int)$_POST["id"];
    $status = $_POST["status"];

    $success = updateAdminBookingStatus($id, $status);

    if ($success) {
        $_SESSION["success"] = "Booking status updated.";
    } else {
        $_SESSION["error"] = "Booking status update failed.";
    }

    redirect("index.php?route=admin-bookings");
}

/* Reviews + Reports */

function showAdminReviews() {
    requireAdmin();

    $reviews = getAdminReviews();

    require __DIR__ . "/../views/adminReviewsView.php";
}

function handleAdminReviewReply() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-reviews");
    }

    $reviewId = (int)$_POST["review_id"];
    $adminReply = safeInput($_POST["admin_reply"]);

    $success = replyAdminReview($reviewId, $adminReply);

    if ($success) {
        $_SESSION["success"] = "Review reply saved.";
    } else {
        $_SESSION["error"] = "Review reply failed.";
    }

    redirect("index.php?route=admin-reviews");
}

function handleAdminReviewDelete() {
    requireAdmin();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=admin-reviews");
    }

    $reviewId = (int)$_POST["review_id"];

    $success = deleteAdminReview($reviewId);

    if ($success) {
        $_SESSION["success"] = "Review deleted.";
    } else {
        $_SESSION["error"] = "Review delete failed.";
    }

    redirect("index.php?route=admin-reviews");
}

function showAdminReports() {
    requireAdmin();

    $report = getAdminReports();

    require __DIR__ . "/../views/adminReportsView.php";
}

?>