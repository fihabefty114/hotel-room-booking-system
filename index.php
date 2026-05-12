<?php

session_start();

$route = "login";

if (isset($_GET["route"])) {
    $route = $_GET["route"];
}

switch ($route) {

    /* =========================
       Auth Routes
       Login / Register / Logout
    ========================= */

    case "login":
        require_once __DIR__ . "/controllers/AuthController.php";
        showLoginPage();
        break;

    case "register":
        require_once __DIR__ . "/controllers/AuthController.php";
        showRegisterPage();
        break;

    case "do-login":
        require_once __DIR__ . "/controllers/AuthController.php";
        handleLogin();
        break;

    case "do-register":
        require_once __DIR__ . "/controllers/AuthController.php";
        handleRegister();
        break;

    case "logout":
        require_once __DIR__ . "/controllers/AuthController.php";
        handleLogout();
        break;


    /* =========================
       Guest Routes
       Ifti / Guest Module
    ========================= */

    


       case "guest-search-rooms":
        require_once __DIR__ . "/controllers/GuestController.php";
        showGuestSearchRooms();
        break;

    case "ajax-search-rooms":
        require_once __DIR__ . "/controllers/GuestAjaxController.php";
        searchAvailableRoomsAjax();
        break;

        case "guest-dashboard":
        require_once __DIR__ . "/controllers/GuestController.php";
        showGuestDashboard();
        break;

    case "guest-profile":
        require_once __DIR__ . "/controllers/GuestController.php";
        showGuestProfile();
        break;

    case "guest-edit-profile":
        require_once __DIR__ . "/controllers/GuestController.php";
        showGuestEditProfile();
        break;

    case "do-guest-update-profile":
        require_once __DIR__ . "/controllers/GuestController.php";
        handleGuestProfileUpdate();
        break;

    case "do-guest-upload-profile-picture":
        require_once __DIR__ . "/controllers/GuestController.php";
        handleGuestProfilePictureUpload();
        break;

    case "guest-change-password":
        require_once __DIR__ . "/controllers/GuestController.php";
        showGuestChangePassword();
        break;

    case "do-guest-change-password":
        require_once __DIR__ . "/controllers/GuestController.php";
        handleGuestPasswordChange();
        break;

        case "guest-book-room":
    require_once __DIR__ . "/controllers/GuestController.php";
    showGuestBookRoom();
    break;

case "do-guest-confirm-booking":
    require_once __DIR__ . "/controllers/GuestController.php";
    handleGuestBookingConfirm();
    break;

case "guest-booking-confirmation":
    require_once __DIR__ . "/controllers/GuestController.php";
    showGuestBookingConfirmation();
    break;

    case "guest-my-bookings":
    require_once __DIR__ . "/controllers/GuestController.php";
    showGuestMyBookings();
    break;

case "do-guest-cancel-booking":
    require_once __DIR__ . "/controllers/GuestController.php";
    handleGuestCancelBooking();
    break;

    case "guest-service-requests":
    require_once __DIR__ . "/controllers/GuestController.php";
    showGuestServiceRequests();
    break;

case "do-guest-service-request":
    require_once __DIR__ . "/controllers/GuestController.php";
    handleGuestServiceRequestSubmit();
    break;

case "guest-reviews":
    require_once __DIR__ . "/controllers/GuestController.php";
    showGuestReviews();
    break;

case "do-guest-submit-review":
    require_once __DIR__ . "/controllers/GuestController.php";
    handleGuestReviewSubmit();
    break;

case "do-guest-update-review":
    require_once __DIR__ . "/controllers/GuestController.php";
    handleGuestReviewUpdate();
    break;

case "do-guest-delete-review":
    require_once __DIR__ . "/controllers/GuestController.php";
    handleGuestReviewDelete();
    break;

case "guest-billing-history":
    require_once __DIR__ . "/controllers/GuestController.php";
    showGuestBillingHistory();
    break;

case "do-guest-redeem-points":
    require_once __DIR__ . "/controllers/GuestController.php";
    handleGuestRedeemPoints();
    break;
case "guest-room-type-details":
    require_once __DIR__ . "/controllers/GuestController.php";
    showGuestRoomTypeDetails();
    break;

case "guest-receipt":
    require_once __DIR__ . "/controllers/GuestController.php";
    showGuestReceipt();
    break;

case "guest-download-receipt":
    require_once __DIR__ . "/controllers/GuestController.php";
    downloadGuestReceipt();
    break;

    /* =========================
       Receptionist Routes
       Groupmate / Receptionist Module
    ========================= */

    case "receptionist-dashboard":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        showReceptionistDashboard();
        break;

    case "receptionist-check-in":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        showCheckInPage();
        break;

    case "receptionist-check-out":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        showCheckOutPage();
        break;


    /* =========================
       Housekeeping Routes
    ========================= */

    case "housekeeping-dashboard":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        showHousekeepingDashboard();
        break;


    /* =========================
       Admin Routes
    ========================= */

    case "admin-dashboard":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminDashboard();
        break;


    /* =========================
       Default Route
    ========================= */

    default:
        require_once __DIR__ . "/controllers/AuthController.php";
        showLoginPage();
        break;
}

?>