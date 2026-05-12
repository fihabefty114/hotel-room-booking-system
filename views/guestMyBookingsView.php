<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>My Bookings</h2>

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

    <?php if (count($bookings) === 0) { ?>
        <p>No booking found.</p>
    <?php } else { ?>

        <?php foreach ($bookings as $booking) { ?>

            <?php
                $today = date("Y-m-d");
                $bookingType = "Past";

                if ($booking["checkout_date"] >= $today) {
                    $bookingType = "Upcoming";
                }
            ?>

            <div class="booking-card">

                <h3>Booking ID: <?php echo $booking["id"]; ?></h3>

                <div class="booking-grid">

                    <div>
                        <p><strong>Room Type:</strong> <?php echo htmlspecialchars($booking["room_type_name"]); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($booking["description"]); ?></p>
                        <p><strong>Check-in Date:</strong> <?php echo $booking["checkin_date"]; ?></p>
                        <p><strong>Check-out Date:</strong> <?php echo $booking["checkout_date"]; ?></p>
                        <p><strong>Number of Guests:</strong> <?php echo $booking["num_guests"]; ?></p>
                    </div>

                    <div>
                        <p><strong>Total Price:</strong> <?php echo $booking["total_price"]; ?> BDT</p>

                        <?php if ($booking["payment_status"] !== null) { ?>
                            <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($booking["payment_status"]); ?></p>
                        <?php } else { ?>
                            <p><strong>Payment Status:</strong> Pending</p>
                        <?php } ?>

                        <p>
                            <strong>Booking Status:</strong>
                            <span class="status-badge">
                                <?php echo htmlspecialchars($booking["status"]); ?>
                            </span>
                        </p>

                        <p><strong>Booking Type:</strong> <?php echo $bookingType; ?></p>

                        <?php if ($booking["room_number"] !== null) { ?>
                            <p><strong>Assigned Room:</strong> <?php echo htmlspecialchars($booking["room_number"]); ?></p>
                            <p><strong>Floor:</strong> <?php echo htmlspecialchars($booking["floor"]); ?></p>
                        <?php } else { ?>
                            <p><strong>Assigned Room:</strong> Not assigned yet</p>
                        <?php } ?>
                    </div>

                </div>

                <?php if ($booking["status"] === "confirmed") { ?>
                    <form action="index.php?route=do-guest-cancel-booking" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                        <input type="hidden" name="booking_id" value="<?php echo $booking["id"]; ?>">
                        <button type="submit" class="btn-danger">Cancel Booking</button>
                    </form>
                <?php } ?>

            </div>

        <?php } ?>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-dashboard">Back to Dashboard</a>
        <a class="btn" href="index.php?route=guest-search-rooms">Search Rooms</a>
    </div>

</div>

</body>
</html>