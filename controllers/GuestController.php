<?php

require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/GuestModel.php";

function showGuestDashboard() {
    requireGuest();

    $guest = findUserById($_SESSION["user_id"]);

    if (!$guest) {
        $_SESSION["error"] = "Guest account not found.";
        redirect("index.php?route=login");
    }

    require __DIR__ . "/../views/guestDashboardView.php";
}

function showGuestProfile() {
    requireGuest();

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
        $_SESSION["success"] = "Password changed successfully. Please login again.";
        session_unset();
        session_destroy();
        header("Location: index.php?route=login");
        exit();
    } else {
        $_SESSION["error"] = "Password change failed.";
        redirect("index.php?route=guest-change-password");
    }
}

?>