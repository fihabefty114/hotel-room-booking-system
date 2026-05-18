<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Admin Dashboard</h2>

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
        <p><strong>Role:</strong> Admin</p>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card"><h3><?php echo $stats["total_rooms"]; ?></h3><p>Total Rooms</p></div>
        <div class="dashboard-card"><h3><?php echo $stats["available_rooms"]; ?></h3><p>Available Rooms</p></div>
        <div class="dashboard-card"><h3><?php echo $stats["occupied_rooms"]; ?></h3><p>Occupied Rooms</p></div>
        <div class="dashboard-card"><h3><?php echo $stats["dirty_rooms"]; ?></h3><p>Dirty Rooms</p></div>
        <div class="dashboard-card"><h3><?php echo $stats["total_guests"]; ?></h3><p>Total Guests</p></div>
        <div class="dashboard-card"><h3><?php echo $stats["total_bookings"]; ?></h3><p>Total Bookings</p></div>
        <div class="dashboard-card"><h3><?php echo $stats["pending_payments"]; ?></h3><p>Pending Payments</p></div>
        <div class="dashboard-card"><h3><?php echo $stats["today_revenue"]; ?></h3><p>Today Revenue</p></div>
    </div>

    <h3>Admin Features</h3>

    <div class="feature-grid">
        <a class="feature-card" href="index.php?route=admin-room-types">Manage Room Types</a>
        <a class="feature-card" href="index.php?route=admin-rooms">Manage Rooms</a>
        <a class="feature-card" href="index.php?route=admin-seasonal-pricing">Manage Seasonal Pricing</a>
        <a class="feature-card" href="index.php?route=admin-staff">Manage Staff Accounts</a>
        <a class="feature-card" href="index.php?route=admin-guests">Manage Guest Accounts</a>
        <a class="feature-card" href="index.php?route=admin-bookings">View All Bookings</a>
        <a class="feature-card" href="index.php?route=admin-reviews">Manage Reviews</a>
        <a class="feature-card" href="index.php?route=admin-reports">Reports / Printable Export</a>
        <a class="feature-card" href="index.php?route=admin-room-status-ajax">Room Status</a>
    </div>

    <div class="nav-links">
        <a class="btn btn-danger" href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>