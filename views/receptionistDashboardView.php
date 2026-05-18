<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Receptionist Dashboard</h2>

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
        <p><strong>Role:</strong> Receptionist</p>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3><?php echo $stats["today_checkins"]; ?></h3>
            <p>Expected Check-ins Today</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["today_checkouts"]; ?></h3>
            <p>Expected Check-outs Today</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["checked_in_guests"]; ?></h3>
            <p>Currently Checked-in Guests</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["available_rooms"]; ?></h3>
            <p>Available Rooms</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["pending_service_requests"]; ?></h3>
            <p>Pending Service Requests</p>
        </div>

        <div class="dashboard-card">
            <h3><?php echo $stats["pending_payments"]; ?></h3>
            <p>Pending Payments</p>
        </div>
    </div>

    <h3>Receptionist Features</h3>

    <div class="feature-grid">
        <a class="feature-card" href="index.php?route=receptionist-check-in">Check In Guest</a>
        <a class="feature-card" href="index.php?route=receptionist-check-out">Check Out Guest</a>
        <a class="feature-card" href="index.php?route=receptionist-payments">Process Payments</a>
        <a class="feature-card" href="index.php?route=receptionist-service-requests">Manage Service Requests</a>
        <a class="feature-card" href="index.php?route=receptionist-room-status">Room Status Board</a>
        <a class="feature-card" href="index.php?route=receptionist-daily-report">Daily Report</a>
    </div>

    <div class="nav-links">
        <a class="btn btn-danger" href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>

