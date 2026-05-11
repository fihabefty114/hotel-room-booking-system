<?php

require_once __DIR__ . "/../models/UserModel.php";

function showGuestDashboard() {
    requireGuest();

    $guest = findUserById($_SESSION["user_id"]);

    if (!$guest) {
        $_SESSION["error"] = "Guest account not found.";
        redirect("index.php?route=login");
    }

    require __DIR__ . "/../views/guestDashboardView.php";
}

?>