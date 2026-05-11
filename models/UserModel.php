<?php

require_once __DIR__ . "/../config/Database.php";

function findUserByEmail($email) {
    $conn = getConnection();

    $sql = "SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $user;
}

function findUserById($id) {
    $conn = getConnection();

    $sql = "SELECT * FROM users WHERE id = ? AND is_active = 1 LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $user;
}

function emailExists($email) {
    $conn = getConnection();

    $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $exists = false;

    if ($result && mysqli_num_rows($result) > 0) {
        $exists = true;
    }

    mysqli_stmt_close($stmt);

    return $exists;
}

function registerGuestUser($name, $email, $passwordHash, $phone, $nationality, $idNumber) {
    $conn = getConnection();

    $role = "guest";
    $profilePic = "";
    $isActive = 1;

    $sql = "INSERT INTO users 
            (name, email, password_hash, phone, nationality, id_number, role, profile_pic, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssi",
        $name,
        $email,
        $passwordHash,
        $phone,
        $nationality,
        $idNumber,
        $role,
        $profilePic,
        $isActive
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

?>