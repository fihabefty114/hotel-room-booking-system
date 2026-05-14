<?php

require_once __DIR__ . "/../config/Database.php";

function getHousekeepingDashboardStats($staffId) {
    $conn = getConnection();

    $stats = array(
        "dirty_rooms" => 0,
        "my_pending_tasks" => 0,
        "my_in_progress_tasks" => 0,
        "my_completed_tasks_today" => 0,
        "my_open_maintenance_reports" => 0
    );

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'dirty'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["dirty_rooms"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM housekeeping_tasks 
            WHERE assigned_to = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["my_pending_tasks"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM housekeeping_tasks 
            WHERE assigned_to = ? AND status = 'in_progress'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["my_in_progress_tasks"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM housekeeping_tasks 
            WHERE assigned_to = ? 
            AND status = 'completed' 
            AND DATE(created_at) = CURDATE()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["my_completed_tasks_today"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM maintenance_reports 
            WHERE reported_by = ? 
            AND (status = 'open' OR status = 'in_progress')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $stats["my_open_maintenance_reports"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    return $stats;
}

function getHousekeepingProfile($userId) {
    $conn = getConnection();

    $sql = "SELECT * FROM users WHERE id = ? AND role = 'housekeeping' LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $user;
}

function updateHousekeepingProfile($userId, $name, $phone, $idNumber) {
    $conn = getConnection();

    $sql = "UPDATE users 
            SET name = ?, phone = ?, id_number = ?
            WHERE id = ? AND role = 'housekeeping'";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "sssi", $name, $phone, $idNumber, $userId);

    mysqli_stmt_execute($stmt);

    $success = false;

    if (mysqli_affected_rows($conn) >= 0) {
        $success = true;
    }

    mysqli_stmt_close($stmt);

    return $success;
}

function getDirtyRoomsForHousekeeping() {
    $conn = getConnection();

    $sql = "SELECT rooms.*, room_types.name AS room_type_name
            FROM rooms
            INNER JOIN room_types ON rooms.room_type_id = room_types.id
            WHERE rooms.status = 'dirty'
            ORDER BY rooms.room_number ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rooms = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $rooms;
}

function hasActiveCleaningTaskForRoom($roomId) {
    $conn = getConnection();

    $sql = "SELECT id FROM housekeeping_tasks
            WHERE room_id = ?
            AND (status = 'pending' OR status = 'in_progress')
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $roomId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $exists = false;

    if ($result && mysqli_num_rows($result) > 0) {
        $exists = true;
    }

    mysqli_stmt_close($stmt);

    return $exists;
}

function createHousekeepingCleaningTask($roomId, $staffId, $notes) {
    $conn = getConnection();

    $taskType = "cleaning";
    $status = "pending";

    $sql = "INSERT INTO housekeeping_tasks
            (room_id, assigned_to, task_type, status, notes)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "iisss", $roomId, $staffId, $taskType, $status, $notes);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function getMyHousekeepingTasks($staffId) {
    $conn = getConnection();

    $sql = "SELECT housekeeping_tasks.*, rooms.room_number, rooms.floor, room_types.name AS room_type_name
            FROM housekeeping_tasks
            INNER JOIN rooms ON housekeeping_tasks.room_id = rooms.id
            INNER JOIN room_types ON rooms.room_type_id = room_types.id
            WHERE housekeeping_tasks.assigned_to = ?
            ORDER BY housekeeping_tasks.created_at DESC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $tasks = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $tasks;
}

function getHousekeepingTaskById($taskId, $staffId) {
    $conn = getConnection();

    $sql = "SELECT * FROM housekeeping_tasks
            WHERE id = ?
            AND assigned_to = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $taskId, $staffId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $task = null;

    if ($result && mysqli_num_rows($result) === 1) {
        $task = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);

    return $task;
}

function updateHousekeepingTaskStatus($taskId, $staffId, $status) {
    $conn = getConnection();

    $sql = "UPDATE housekeeping_tasks
            SET status = ?
            WHERE id = ?
            AND assigned_to = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "sii", $status, $taskId, $staffId);

    mysqli_stmt_execute($stmt);

    $updated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $updated = true;
    }

    mysqli_stmt_close($stmt);

    return $updated;
}

function markRoomAvailableAfterCleaning($roomId) {
    $conn = getConnection();

    $status = "available";
    $notes = "Cleaned and ready";

    $sql = "UPDATE rooms
            SET status = ?, notes = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssi", $status, $notes, $roomId);

    mysqli_stmt_execute($stmt);

    $updated = false;

    if (mysqli_affected_rows($conn) > 0) {
        $updated = true;
    }

    mysqli_stmt_close($stmt);

    return $updated;
}

function getHousekeepingRoomStatusBoard() {
    $conn = getConnection();

    $sql = "SELECT rooms.*, room_types.name AS room_type_name
            FROM rooms
            INNER JOIN room_types ON rooms.room_type_id = room_types.id
            ORDER BY rooms.room_number ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rooms = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $rooms;
}

function getAllRoomsForHousekeeping() {
    $conn = getConnection();

    $sql = "SELECT rooms.*, room_types.name AS room_type_name
            FROM rooms
            INNER JOIN room_types ON rooms.room_type_id = room_types.id
            ORDER BY rooms.room_number ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rooms = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $rooms;
}

function createHousekeepingMaintenanceReport($roomId, $staffId, $issueType, $description) {
    $conn = getConnection();

    $status = "open";

    $sql = "INSERT INTO maintenance_reports
            (room_id, reported_by, issue_type, description, status)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "iisss", $roomId, $staffId, $issueType, $description, $status);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

function getMyMaintenanceReports($staffId) {
    $conn = getConnection();

    $sql = "SELECT maintenance_reports.*, rooms.room_number
            FROM maintenance_reports
            INNER JOIN rooms ON maintenance_reports.room_id = rooms.id
            WHERE maintenance_reports.reported_by = ?
            ORDER BY maintenance_reports.reported_at DESC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $reports = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reports[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $reports;
}

function getHousekeepingDailyReport($staffId) {
    $conn = getConnection();

    $report = array(
        "completed_tasks_today" => 0,
        "in_progress_tasks" => 0,
        "dirty_rooms" => 0,
        "available_rooms" => 0,
        "maintenance_reports_today" => 0
    );

    $sql = "SELECT COUNT(*) AS total FROM housekeeping_tasks
            WHERE assigned_to = ?
            AND status = 'completed'
            AND DATE(created_at) = CURDATE()";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["completed_tasks_today"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM housekeeping_tasks
            WHERE assigned_to = ?
            AND status = 'in_progress'";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["in_progress_tasks"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'dirty'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["dirty_rooms"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'available'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["available_rooms"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    $sql = "SELECT COUNT(*) AS total FROM maintenance_reports
            WHERE reported_by = ?
            AND DATE(reported_at) = CURDATE()";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $staffId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $report["maintenance_reports_today"] = $row["total"];
    }

    mysqli_stmt_close($stmt);

    return $report;
}

?>
