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

    case "guest-dashboard":
        require_once __DIR__ . "/controllers/GuestController.php";
        showGuestDashboard();
        break;

    case "guest-profile":
        require_once __DIR__ . "/controllers/GuestController.php";
        showGuestProfile();
        break;

    case "guest-search-rooms":
        require_once __DIR__ . "/controllers/GuestController.php";
        showGuestSearchRooms();
        break;

    case "ajax-search-rooms":
        require_once __DIR__ . "/controllers/GuestAjaxController.php";
        searchAvailableRoomsAjax();
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