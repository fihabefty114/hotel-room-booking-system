<!DOCTYPE html>
<html>
<head>
    <title>Manage Reviews</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Manage Reviews</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <?php foreach ($reviews as $review) { ?>
        <div class="booking-card">
            <h3>Review ID: <?php echo $review["id"]; ?></h3>

            <p><strong>Guest:</strong> <?php echo htmlspecialchars($review["guest_name"]); ?></p>
            <p><strong>Room Type:</strong> <?php echo htmlspecialchars($review["room_type_name"]); ?></p>
            <p><strong>Overall:</strong> <?php echo $review["overall_rating"]; ?>/5</p>
            <p><strong>Cleanliness:</strong> <?php echo $review["cleanliness_rating"]; ?>/5</p>
            <p><strong>Service:</strong> <?php echo $review["service_rating"]; ?>/5</p>
            <p><strong>Comment:</strong> <?php echo htmlspecialchars($review["review_text"]); ?></p>

            <form action="index.php?route=do-admin-reply-review" method="POST">
                <input type="hidden" name="review_id" value="<?php echo $review["id"]; ?>">

                <div class="form-group">
                    <label>Admin Reply</label>
                    <textarea name="admin_reply" rows="3"><?php echo htmlspecialchars($review["admin_reply"]); ?></textarea>
                </div>

                <button type="submit">Save Reply</button>
            </form>

            <form action="index.php?route=do-admin-delete-review" method="POST" onsubmit="return confirm('Delete this review?');">
                <input type="hidden" name="review_id" value="<?php echo $review["id"]; ?>">
                <button type="submit" class="btn-danger">Delete Review</button>
            </form>
        </div>
    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=admin-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>