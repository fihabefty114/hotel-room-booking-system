<?php

session_start();

require_once __DIR__ . "/controllers/AuthController.php";
require_once __DIR__ . "/controllers/GuestController.php";

$route = "login";

if (isset($_GET["route"])) {
    $route = $_GET["route"];
}

switch ($route) {
    case "login":
        showLoginPage();
        break;

    case "register":
        showRegisterPage();
        break;

    case "do-login":
        handleLogin();
        break;

    case "do-register":
        handleRegister();
        break;

    case "guest-dashboard":
        showGuestDashboard();
        break;

    case "logout":
        handleLogout();
        break;

    default:
        showLoginPage();
        break;
}

?>