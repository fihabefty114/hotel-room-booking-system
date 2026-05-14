<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Check In</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Check In Guest</h2>

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
        <input type="hidden" name="route" value="receptionist-check-in">

        <div class="form-group">
            <label>Search by Booking ID or Guest Name</label>
            <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" required>
        </div>

        <button type="submit">Search</button>
    </form>

    <hr>

    <?php if ($keyword !== "") { ?>

        <?php if (count($bookingData) === 0) { ?>
            <p>No confirmed booking found.</p>
        <?php } ?>

        <?php foreach ($bookingData as $item) { ?>

            <?php
                $booking = $item["booking"];
                $availableRooms = $item["available_rooms"];
            ?>

            <div class="booking-card">

                <h3>Booking ID: <?php echo $booking["id"]; ?></h3>

                <p><strong>Guest:</strong> <?php echo htmlspecialchars($booking["guest_name"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking["email"]); ?></p>
                <p><strong>ID Number:</strong> <?php echo htmlspecialchars($booking["id_number"]); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($booking["room_type_name"]); ?></p>
                <p><strong>Check-in Date:</strong> <?php echo $booking["checkin_date"]; ?></p>
                <p><strong>Check-out Date:</strong> <?php echo $booking["checkout_date"]; ?></p>
                <p><strong>Number of Guests:</strong> <?php echo $booking["num_guests"]; ?></p>
                <p><strong>Total Price:</strong> <?php echo $booking["total_price"]; ?> BDT</p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($booking["status"]); ?></p>

                <?php if (count($availableRooms) > 0) { ?>

                    <form action="index.php?route=do-receptionist-check-in" method="POST">
                        <input type="hidden" name="booking_id" value="<?php echo $booking["id"]; ?>">

                        <div class="form-group">
                            <label>Assign Available Room</label>
                            <select name="room_id" required>
                                <?php foreach ($availableRooms as $room) { ?>
                                    <option value="<?php echo $room["id"]; ?>">
                                        Room <?php echo htmlspecialchars($room["room_number"]); ?>
                                        - Floor <?php echo $room["floor"]; ?>
                                        - Status: <?php echo htmlspecialchars($room["status"]); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <button type="submit">Confirm Check In</button>
                    </form>

                <?php } else { ?>

                    <p style="color:red;">
                        No available room found for this room type.
                    </p>

                <?php } ?>

            </div>

        <?php } ?>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=receptionist-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>