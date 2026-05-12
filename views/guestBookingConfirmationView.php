<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2>Booking Confirmation</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success">
            <?php echo $_SESSION["success"]; ?>
        </div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <div class="booking-summary">

        <h3>Your booking has been confirmed</h3>

        <p><strong>Booking ID:</strong> <?php echo $booking["id"]; ?></p>
        <p><strong>Room Type:</strong> <?php echo htmlspecialchars($booking["room_type_name"]); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($booking["description"]); ?></p>
        <p><strong>Check-in Date:</strong> <?php echo $booking["checkin_date"]; ?></p>
        <p><strong>Check-out Date:</strong> <?php echo $booking["checkout_date"]; ?></p>
        <p><strong>Number of Guests:</strong> <?php echo $booking["num_guests"]; ?></p>
        <p><strong>Total Price:</strong> <?php echo $booking["total_price"]; ?> BDT</p>
        <p><strong>Booking Status:</strong> <?php echo htmlspecialchars($booking["status"]); ?></p>
        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($booking["payment_status"]); ?></p>

    </div>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-dashboard">Back to Dashboard</a>
        <a class="btn" href="index.php?route=guest-search-rooms">Search More Rooms</a>
    </div>

</div>

</body>
</html>