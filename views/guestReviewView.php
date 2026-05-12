<!DOCTYPE html>
<html>
<head>
    <title>My Reviews</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Reviews</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <?php if (count($reviewItems) === 0) { ?>
        <p>No completed stay found for review.</p>
    <?php } else { ?>

        <?php foreach ($reviewItems as $item) { ?>

            <div class="booking-card">

                <h3>Booking ID: <?php echo $item["booking_id"]; ?></h3>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($item["room_type_name"]); ?></p>
                <p><strong>Stay:</strong> <?php echo $item["checkin_date"]; ?> to <?php echo $item["checkout_date"]; ?></p>

                <?php if ($item["review_id"] === null) { ?>

                    <h4>Write Review</h4>

                    <form action="index.php?route=do-guest-submit-review" method="POST">
                        <input type="hidden" name="booking_id" value="<?php echo $item["booking_id"]; ?>">

                        <div class="form-group">
                            <label>Overall Rating</label>
                            <input type="number" name="overall_rating" min="1" max="5" required>
                        </div>

                        <div class="form-group">
                            <label>Cleanliness Rating</label>
                            <input type="number" name="cleanliness_rating" min="1" max="5" required>
                        </div>

                        <div class="form-group">
                            <label>Service Rating</label>
                            <input type="number" name="service_rating" min="1" max="5" required>
                        </div>

                        <div class="form-group">
                            <label>Review Text</label>
                            <textarea name="review_text" rows="4" required></textarea>
                        </div>

                        <button type="submit">Submit Review</button>
                    </form>

                <?php } else { ?>

                    <h4>Your Review</h4>

                    <form action="index.php?route=do-guest-update-review" method="POST">
                        <input type="hidden" name="review_id" value="<?php echo $item["review_id"]; ?>">

                        <div class="form-group">
                            <label>Overall Rating</label>
                            <input type="number" name="overall_rating" min="1" max="5" value="<?php echo $item["overall_rating"]; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Cleanliness Rating</label>
                            <input type="number" name="cleanliness_rating" min="1" max="5" value="<?php echo $item["cleanliness_rating"]; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Service Rating</label>
                            <input type="number" name="service_rating" min="1" max="5" value="<?php echo $item["service_rating"]; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Review Text</label>
                            <textarea name="review_text" rows="4" required><?php echo htmlspecialchars($item["review_text"]); ?></textarea>
                        </div>

                        <button type="submit">Update Review</button>
                    </form>

                    <br>

                    <form action="index.php?route=do-guest-delete-review" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                        <input type="hidden" name="review_id" value="<?php echo $item["review_id"]; ?>">
                        <button type="submit" class="btn-danger">Delete Review</button>
                    </form>

                <?php } ?>

            </div>

        <?php } ?>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>