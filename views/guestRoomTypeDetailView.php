<!DOCTYPE html>
<html>
<head>
    <title>Room Type Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Room Type Details</h2>

    <div class="booking-card">
        <h3><?php echo htmlspecialchars($roomType["name"]); ?></h3>

        <p><strong>Description:</strong> <?php echo htmlspecialchars($roomType["description"]); ?></p>
        <p><strong>Capacity:</strong> <?php echo $roomType["max_capacity"]; ?> guests</p>
        <p><strong>Price Per Night:</strong> <?php echo $roomType["price_per_night"]; ?> BDT</p>

        <p><strong>Amenities:</strong>
            <?php
                if (count($amenities) > 0) {
                    for ($i = 0; $i < count($amenities); $i++) {
                        echo htmlspecialchars($amenities[$i]);

                        if ($i < count($amenities) - 1) {
                            echo ", ";
                        }
                    }
                } else {
                    echo "No amenities listed";
                }
            ?>
        </p>
    </div>

    <h3>Room Images</h3>

    <div class="image-grid">
        <?php foreach ($images as $image) { ?>
            <div class="room-image-box">
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Room Image">
            </div>
        <?php } ?>
    </div>

    <h3>Average Ratings From Past Reviews</h3>

    <div class="booking-card">
        <?php if ($roomType["avg_overall"] !== null) { ?>
            <p><strong>Overall Rating:</strong> <?php echo number_format($roomType["avg_overall"], 1); ?> / 5</p>
            <p><strong>Cleanliness Rating:</strong> <?php echo number_format($roomType["avg_cleanliness"], 1); ?> / 5</p>
            <p><strong>Service Rating:</strong> <?php echo number_format($roomType["avg_service"], 1); ?> / 5</p>
        <?php } else { ?>
            <p>No reviews available for this room type yet.</p>
        <?php } ?>
    </div>

    <div class="nav-links">

    <?php if ($checkinDate !== "" && $checkoutDate !== "" && $numGuests > 0) { ?>
        <a class="btn" href="index.php?route=guest-book-room&room_type_id=<?php echo $roomType["id"]; ?>&checkin_date=<?php echo htmlspecialchars($checkinDate); ?>&checkout_date=<?php echo htmlspecialchars($checkoutDate); ?>&num_guests=<?php echo $numGuests; ?>">
            Book This Room
        </a>
    <?php } ?>

    <a class="btn" href="index.php?route=guest-search-rooms">Back to Room Search</a>
    <a class="btn" href="index.php?route=guest-dashboard">Dashboard</a>
</div>

</div>

</body>
</html>