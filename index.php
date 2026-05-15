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
        showReceptionistCheckInPage();
        break;

    case "do-receptionist-check-in":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        processReceptionistCheckIn();
        break;

    case "receptionist-check-out":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        showReceptionistCheckOutPage();
        break;

    case "do-receptionist-check-out":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        processReceptionistCheckOut();
        break;

    case "receptionist-room-status":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        showReceptionistRoomStatusPage();
        break;

    case "ajax-receptionist-room-status":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        getReceptionistRoomStatusAjax();
        break;

    case "receptionist-service-requests":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        showReceptionistServiceRequestsPage();
        break;

    case "do-update-service-request-status":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        processReceptionistServiceRequestUpdate();
        break;

    case "receptionist-payments":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        showReceptionistPaymentPage();
        break;

    case "do-receptionist-payment":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        processReceptionistPayment();
        break;

    case "receptionist-daily-report":
        require_once __DIR__ . "/controllers/ReceptionistController.php";
        showReceptionistDailyReport();
        break;


    /* =========================
       Housekeeping Routes
    ========================= */

    case "housekeeping-dashboard":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        showHousekeepingDashboard();
        break;

    case "housekeeping-profile":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        showHousekeepingProfile();
        break;

    case "do-housekeeping-profile-update":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        handleHousekeepingProfileUpdate();
        break;

    case "housekeeping-tasks":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        showHousekeepingTasks();
        break;

    case "do-housekeeping-take-task":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        handleHousekeepingTakeTask();
        break;

    case "do-housekeeping-update-task":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        handleHousekeepingTaskUpdate();
        break;

    case "housekeeping-room-status":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        showHousekeepingRoomStatusPage();
        break;

    case "ajax-housekeeping-room-status":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        getHousekeepingRoomStatusAjax();
        break;

    case "housekeeping-maintenance":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        showHousekeepingMaintenancePage();
        break;

    case "do-housekeeping-maintenance":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        handleHousekeepingMaintenanceReport();
        break;

    case "housekeeping-daily-report":
        require_once __DIR__ . "/controllers/HousekeepingController.php";
        showHousekeepingDailyReport();
        break;



    /* =========================
       Admin Routes
    ========================= */

   case "admin-dashboard":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminDashboard();
        break;

    case "admin-room-types":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminRoomTypes();
        break;

    case "do-admin-create-room-type":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminRoomTypeCreate();
        break;

    case "do-admin-update-room-type":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminRoomTypeUpdate();
        break;

    case "do-admin-delete-room-type":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminRoomTypeDelete();
        break;

    case "admin-rooms":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminRooms();
        break;

    case "do-admin-create-room":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminRoomCreate();
        break;

    case "do-admin-update-room":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminRoomUpdate();
        break;

    case "do-admin-delete-room":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminRoomDelete();
        break;

    case "admin-seasonal-pricing":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminSeasonalPricing();
        break;

    case "do-admin-create-seasonal-pricing":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminSeasonalPricingCreate();
        break;

    case "do-admin-update-seasonal-pricing":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminSeasonalPricingUpdate();
        break;

    case "do-admin-delete-seasonal-pricing":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminSeasonalPricingDelete();
        break;

    case "admin-staff":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminStaff();
        break;

    case "do-admin-create-staff":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminStaffCreate();
        break;

    case "do-admin-update-staff":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminStaffUpdate();
        break;

    case "admin-guests":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminGuests();
        break;

    case "do-admin-update-guest-status":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminGuestStatusUpdate();
        break;

    case "admin-bookings":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminBookings();
        break;

    case "do-admin-update-booking-status":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminBookingStatusUpdate();
        break;

    case "admin-reviews":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminReviews();
        break;

    case "do-admin-reply-review":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminReviewReply();
        break;

    case "do-admin-delete-review":
        require_once __DIR__ . "/controllers/AdminController.php";
        handleAdminReviewDelete();
        break;

    case "admin-reports":
        require_once __DIR__ . "/controllers/AdminController.php";
        showAdminReports();
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