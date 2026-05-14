<?php

require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/../models/HousekeepingModel.php";

function requireHousekeeping() {
    requireRole("housekeeping");
}

function showHousekeepingDashboard() {
    requireHousekeeping();

    $stats = getHousekeepingDashboardStats($_SESSION["user_id"]);

    require __DIR__ . "/../views/housekeepingDashboardView.php";
}

function showHousekeepingProfile() {
    requireHousekeeping();

    $profile = getHousekeepingProfile($_SESSION["user_id"]);

    if (!$profile) {
        $_SESSION["error"] = "Profile not found.";
        redirect("index.php?route=housekeeping-dashboard");
    }

    require __DIR__ . "/../views/housekeepingProfileView.php";
}

function handleHousekeepingProfileUpdate() {
    requireHousekeeping();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=housekeeping-profile");
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

    if (isset($_POST["id_number"])) {
        $idNumber = safeInput($_POST["id_number"]);
    } else {
        $idNumber = "";
    }

    if ($name === "" || $phone === "" || $idNumber === "") {
        $_SESSION["error"] = "All fields are required.";
        redirect("index.php?route=housekeeping-profile");
    }

    $success = updateHousekeepingProfile($_SESSION["user_id"], $name, $phone, $idNumber);

    if ($success) {
        $_SESSION["name"] = $name;
        $_SESSION["success"] = "Profile updated successfully.";
    } else {
        $_SESSION["error"] = "Profile update failed.";
    }

    redirect("index.php?route=housekeeping-profile");
}

function showHousekeepingTasks() {
    requireHousekeeping();

    $dirtyRooms = getDirtyRoomsForHousekeeping();
    $tasks = getMyHousekeepingTasks($_SESSION["user_id"]);

    require __DIR__ . "/../views/housekeepingTasksView.php";
}

function handleHousekeepingTakeTask() {
    requireHousekeeping();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=housekeeping-tasks");
    }

    if (isset($_POST["room_id"])) {
        $roomId = (int)$_POST["room_id"];
    } else {
        $roomId = 0;
    }

    if ($roomId <= 0) {
        $_SESSION["error"] = "Invalid room.";
        redirect("index.php?route=housekeeping-tasks");
    }

    $hasTask = hasActiveCleaningTaskForRoom($roomId);

    if ($hasTask) {
        $_SESSION["error"] = "This room already has an active cleaning task.";
        redirect("index.php?route=housekeeping-tasks");
    }

    $notes = "Cleaning task taken by housekeeping staff";

    $success = createHousekeepingCleaningTask($roomId, $_SESSION["user_id"], $notes);

    if ($success) {
        $_SESSION["success"] = "Cleaning task created successfully.";
    } else {
        $_SESSION["error"] = "Cleaning task creation failed.";
    }

    redirect("index.php?route=housekeeping-tasks");
}

function handleHousekeepingTaskUpdate() {
    requireHousekeeping();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=housekeeping-tasks");
    }

    if (isset($_POST["task_id"])) {
        $taskId = (int)$_POST["task_id"];
    } else {
        $taskId = 0;
    }

    if (isset($_POST["status"])) {
        $status = $_POST["status"];
    } else {
        $status = "";
    }

    if ($taskId <= 0) {
        $_SESSION["error"] = "Invalid task.";
        redirect("index.php?route=housekeeping-tasks");
    }

    if ($status !== "pending" && $status !== "in_progress" && $status !== "completed") {
        $_SESSION["error"] = "Invalid task status.";
        redirect("index.php?route=housekeeping-tasks");
    }

    $task = getHousekeepingTaskById($taskId, $_SESSION["user_id"]);

    if (!$task) {
        $_SESSION["error"] = "Task not found.";
        redirect("index.php?route=housekeeping-tasks");
    }

    $success = updateHousekeepingTaskStatus($taskId, $_SESSION["user_id"], $status);

    if ($success) {
        if ($status === "completed") {
            markRoomAvailableAfterCleaning($task["room_id"]);
            $_SESSION["success"] = "Task completed. Room is now available.";
        } else {
            $_SESSION["success"] = "Task status updated.";
        }
    } else {
        $_SESSION["error"] = "Task update failed.";
    }

    redirect("index.php?route=housekeeping-tasks");
}

function showHousekeepingRoomStatusPage() {
    requireHousekeeping();

    require __DIR__ . "/../views/housekeepingRoomStatusView.php";
}

function getHousekeepingRoomStatusAjax() {
    requireHousekeeping();

    $rooms = getHousekeepingRoomStatusBoard();

    header("Content-Type: application/json");
    echo json_encode($rooms);
}

function showHousekeepingMaintenancePage() {
    requireHousekeeping();

    $rooms = getAllRoomsForHousekeeping();
    $reports = getMyMaintenanceReports($_SESSION["user_id"]);

    require __DIR__ . "/../views/housekeepingMaintenanceView.php";
}

function handleHousekeepingMaintenanceReport() {
    requireHousekeeping();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        redirect("index.php?route=housekeeping-maintenance");
    }

    if (isset($_POST["room_id"])) {
        $roomId = (int)$_POST["room_id"];
    } else {
        $roomId = 0;
    }

    if (isset($_POST["issue_type"])) {
        $issueType = safeInput($_POST["issue_type"]);
    } else {
        $issueType = "";
    }

    if (isset($_POST["description"])) {
        $description = safeInput($_POST["description"]);
    } else {
        $description = "";
    }

    if ($roomId <= 0 || $issueType === "" || $description === "") {
        $_SESSION["error"] = "Room, issue type, and description are required.";
        redirect("index.php?route=housekeeping-maintenance");
    }

    $success = createHousekeepingMaintenanceReport($roomId, $_SESSION["user_id"], $issueType, $description);

    if ($success) {
        $_SESSION["success"] = "Maintenance report submitted successfully.";
    } else {
        $_SESSION["error"] = "Maintenance report submission failed.";
    }

    redirect("index.php?route=housekeeping-maintenance");
}

function showHousekeepingDailyReport() {
    requireHousekeeping();

    $report = getHousekeepingDailyReport($_SESSION["user_id"]);

    require __DIR__ . "/../views/housekeepingDailyReportView.php";
}

?>
