<!DOCTYPE html>
<html>
<head>
    <title>Housekeeping Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Housekeeping Dashboard</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="info-box">
        <p><strong>Welcome:</strong> <?php echo htmlspecialchars($_SESSION["name"]); ?></p>
        <p><strong>Role:</strong> Housekeeping</p>
    </div>

    <h3>Housekeeping Summary</h3>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3><?php echo $stats["dirty_rooms"]; ?></h3>
            <p>Dirty Rooms</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["my_pending_tasks"]; ?></h3>
            <p>My Pending Tasks</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["my_in_progress_tasks"]; ?></h3>
            <p>My In-progress Tasks</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["my_completed_tasks_today"]; ?></h3>
            <p>Completed Today</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["my_open_maintenance_reports"]; ?></h3>
            <p>Open Maintenance Reports</p>
        </div>
    </div>

    <h3>Housekeeping Features</h3>

    <div class="feature-grid">
        <a class="feature-card" href="index.php?route=housekeeping-profile">Manage Profile</a>
        <a class="feature-card" href="index.php?route=housekeeping-tasks">Cleaning Tasks</a>
        <a class="feature-card" href="index.php?route=housekeeping-room-status">Room Status Board</a>
        <a class="feature-card" href="index.php?route=housekeeping-maintenance">Maintenance Reports</a>
        <a class="feature-card" href="index.php?route=housekeeping-daily-report">Daily Report</a>
    </div>

    <div class="nav-links">
        <a class="btn btn-danger" href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>
