<!DOCTYPE html>
<html>
<head>
    <title>Confirm Booking</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2>Confirm Booking</h2>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error">
            <?php echo $_SESSION["error"]; ?>
        </div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="booking-summary">

        <h3><?php echo htmlspecialchars($selectedRoom["name"]); ?></h3>

        <p><strong>Description:</strong> <?php echo htmlspecialchars($selectedRoom["description"]); ?></p>
        <p><strong>Check-in Date:</strong> <?php echo htmlspecialchars($checkinDate); ?></p>
        <p><strong>Check-out Date:</strong> <?php echo htmlspecialchars($checkoutDate); ?></p>
        <p><strong>Number of Guests:</strong> <?php echo $numGuests; ?></p>
        <p><strong>Total Nights:</strong> <?php echo $nights; ?></p>
        <p><strong>Available Rooms:</strong> <?php echo $selectedRoom["available_rooms"]; ?></p>

        <?php if ($selectedRoom["seasonal_label"] !== "") { ?>
            <p><strong>Seasonal Pricing:</strong> <?php echo htmlspecialchars($selectedRoom["seasonal_label"]); ?></p>
        <?php } ?>

        <p><strong>Price Per Night:</strong> <?php echo $pricePerNight; ?> BDT</p>
        <p><strong>Total Price:</strong> <?php echo $totalPrice; ?> BDT</p>

        <form action="index.php?route=do-guest-confirm-booking" method="POST">
            <input type="hidden" name="room_type_id" value="<?php echo $roomTypeId; ?>">
            <input type="hidden" name="checkin_date" value="<?php echo htmlspecialchars($checkinDate); ?>">
            <input type="hidden" name="checkout_date" value="<?php echo htmlspecialchars($checkoutDate); ?>">
            <input type="hidden" name="num_guests" value="<?php echo $numGuests; ?>">

            <button type="submit">Confirm Booking</button>
        </form>

    </div>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-search-rooms">Back to Room Search</a>
        <a class="btn" href="index.php?route=guest-dashboard">Dashboard</a>
    </div>

</div>

</body>
</html>