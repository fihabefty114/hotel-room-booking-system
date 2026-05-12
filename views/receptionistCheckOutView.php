<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Check Out</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Check Out Guest</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success">
            <?php echo $_SESSION["success"]; ?>
        </div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error">
            <?php echo $_SESSION["error"]; ?>
        </div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <form action="index.php" method="GET">
        <input type="hidden" name="route" value="receptionist-check-out">

        <div class="form-group">
            <label>Search by Room Number or Guest Name</label>
            <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" required>
        </div>

        <button type="submit">Search</button>
    </form>

    <hr>

    <?php if ($keyword !== "") { ?>

        <?php if (count($bookings) === 0) { ?>
            <p>No checked-in booking found.</p>
        <?php } ?>

        <?php foreach ($bookings as $booking) { ?>
            <div class="booking-card">
                <h3>Booking ID: <?php echo $booking["id"]; ?></h3>

                <p><strong>Guest:</strong> <?php echo htmlspecialchars($booking["guest_name"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking["email"]); ?></p>
                <p><strong>Room Number:</strong> <?php echo htmlspecialchars($booking["room_number"]); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($booking["room_type_name"]); ?></p>
                <p><strong>Check-in:</strong> <?php echo $booking["checkin_date"]; ?></p>
                <p><strong>Check-out:</strong> <?php echo $booking["checkout_date"]; ?></p>

                <form action="index.php?route=do-receptionist-check-out" method="POST">
                    <input type="hidden" name="booking_id" value="<?php echo $booking["id"]; ?>">
                    <input type="hidden" name="room_id" value="<?php echo $booking["room_id"]; ?>">

                    <button type="submit">Confirm Check Out</button>
                </form>
            </div>
        <?php } ?>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=receptionist-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>