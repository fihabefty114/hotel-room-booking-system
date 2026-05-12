<?php
 
require_once __DIR__ . "/../models/UserModel.php";
 
function safeInput($data) {

    $data = trim($data);

    $data = htmlspecialchars($data);

    return $data;

}
 
function redirect($url) {

    header("Location: " . $url);

    exit();

}
 
function isLoggedIn() {

    if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {

        return true;

    } else {

        return false;

    }

}
 
function requireRole($role) {

    if (!isLoggedIn()) {

        $_SESSION["error"] = "Please login first.";

        redirect("index.php?route=login");

    }
 
    if ($_SESSION["role"] !== $role) {

        $_SESSION["error"] = "You are not allowed to access this page.";

        redirectByRole($_SESSION["role"]);

    }

}
 
function requireGuest() {

    requireRole("guest");

}
 
function redirectByRole($role) {

    if ($role === "guest") {

        redirect("index.php?route=guest-dashboard");

    } else if ($role === "receptionist") {

        redirect("index.php?route=receptionist-dashboard");

    } else if ($role === "housekeeping") {

        redirect("index.php?route=housekeeping-dashboard");

    } else if ($role === "admin") {

        redirect("index.php?route=admin-dashboard");

    } else {

        $_SESSION["error"] = "This role dashboard is not implemented yet.";

        redirect("index.php?route=login");

    }

}
 
function showLoginPage() {

    require __DIR__ . "/../views/loginView.php";

}
 
function showRegisterPage() {

    require __DIR__ . "/../views/registerView.php";

}
 
function handleLogin() {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {

        redirect("index.php?route=login");

    }
 
    if (isset($_POST["email"])) {

        $email = safeInput($_POST["email"]);

    } else {

        $email = "";

    }
 
    if (isset($_POST["password"])) {

        $password = $_POST["password"];

    } else {

        $password = "";

    }
 
    if ($email === "" || $password === "") {

        $_SESSION["error"] = "Email and password are required.";

        redirect("index.php?route=login");

    }
 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION["error"] = "Invalid email format.";

        redirect("index.php?route=login");

    }
 
    $user = findUserByEmail($email);
 
    if (!$user) {

        $_SESSION["error"] = "No active user found with this email.";

        redirect("index.php?route=login");

    }
 
    $passwordMatched = false;
 
    if (password_verify($password, $user["password_hash"])) {

        $passwordMatched = true;

    } else {

        if ($password === $user["password_hash"]) {

            $passwordMatched = true;

        }

    }
 
    if (!$passwordMatched) {

        $_SESSION["error"] = "Invalid password.";

        redirect("index.php?route=login");

    }
 
    $_SESSION["user_id"] = $user["id"];

    $_SESSION["name"] = $user["name"];

    $_SESSION["email"] = $user["email"];

    $_SESSION["role"] = $user["role"];
 
    redirectByRole($user["role"]);

}
 
function handleRegister() {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {

        redirect("index.php?route=register");

    }
 
    if (isset($_POST["name"])) {

        $name = safeInput($_POST["name"]);

    } else {

        $name = "";

    }
 
    if (isset($_POST["email"])) {

        $email = safeInput($_POST["email"]);

    } else {

        $email = "";

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
 
    if (isset($_POST["password"])) {

        $password = $_POST["password"];

    } else {

        $password = "";

    }
 
    if (isset($_POST["confirm_password"])) {

        $confirmPassword = $_POST["confirm_password"];

    } else {

        $confirmPassword = "";

    }
 
    $_SESSION["old_name"] = $name;

    $_SESSION["old_email"] = $email;

    $_SESSION["old_phone"] = $phone;

    $_SESSION["old_nationality"] = $nationality;

    $_SESSION["old_id_number"] = $idNumber;
 
    if ($name === "" || $email === "" || $phone === "" || $nationality === "" || $idNumber === "" || $password === "" || $confirmPassword === "") {

        $_SESSION["error"] = "All fields are required.";

        redirect("index.php?route=register");

    }
 
    if (!preg_match("/^[A-Za-z\s]+$/", $name)) {

        $_SESSION["error"] = "Name can contain only letters and spaces.";

        redirect("index.php?route=register");

    }
 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION["error"] = "Invalid email format.";

        redirect("index.php?route=register");

    }
 
    if (!preg_match("/^01[3-9][0-9]{8}$/", $phone)) {

        $_SESSION["error"] = "Phone must be 11 digits and start with 013 to 019.";

        redirect("index.php?route=register");

    }
 
    if (!preg_match("/^[A-Za-z\s]+$/", $nationality)) {

        $_SESSION["error"] = "Nationality can contain only letters and spaces.";

        redirect("index.php?route=register");

    }
 
    if (strlen($password) < 6) {

        $_SESSION["error"] = "Password must be at least 6 characters.";

        redirect("index.php?route=register");

    }
 
    if ($password !== $confirmPassword) {

        $_SESSION["error"] = "Password and confirm password do not match.";

        redirect("index.php?route=register");

    }
 
    if (emailExists($email)) {

        $_SESSION["error"] = "This email is already registered.";

        redirect("index.php?route=register");

    }
 
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
 
    $success = registerGuestUser($name, $email, $passwordHash, $phone, $nationality, $idNumber);
 
    if ($success) {

        unset($_SESSION["old_name"]);

        unset($_SESSION["old_email"]);

        unset($_SESSION["old_phone"]);

        unset($_SESSION["old_nationality"]);

        unset($_SESSION["old_id_number"]);
 
        $_SESSION["success"] = "Guest registration successful. Please login.";

        redirect("index.php?route=login");

    } else {

        $_SESSION["error"] = "Registration failed. Please try again.";

        redirect("index.php?route=register");

    }

}
 
function handleLogout() {

    session_unset();

    session_destroy();
 
    header("Location: index.php?route=login");

    exit();

}
 
?>
 