<!DOCTYPE html>
<html>
<head>
    <title>Seasonal Pricing</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Manage Seasonal Pricing</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="booking-card">
        <h3>Add Seasonal Pricing</h3>

        <form action="index.php?route=do-admin-create-seasonal-pricing" method="POST">
            <div class="form-group">
                <label>Room Type</label>
                <select name="room_type_id" required>
                    <?php foreach ($roomTypes as $type) { ?>
                        <option value="<?php echo $type["id"]; ?>"><?php echo htmlspecialchars($type["name"]); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Label</label>
                <input type="text" name="label" required>
            </div>

            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" required>
            </div>

            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" required>
            </div>

            <div class="form-group">
                <label>Price Per Night</label>
                <input type="number" name="price_per_night" min="1" required>
            </div>

            <button type="submit">Add Pricing</button>
        </form>
    </div>

    <h3>Pricing List</h3>

    <?php foreach ($pricingList as $item) { ?>
        <div class="booking-card">
            <form action="index.php?route=do-admin-update-seasonal-pricing" method="POST">
                <input type="hidden" name="id" value="<?php echo $item["id"]; ?>">

                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type_id">
                        <?php foreach ($roomTypes as $type) { ?>
                            <option value="<?php echo $type["id"]; ?>" <?php if ($type["id"] == $item["room_type_id"]) { echo "selected"; } ?>>
                                <?php echo htmlspecialchars($type["name"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Label</label>
                    <input type="text" name="label" value="<?php echo htmlspecialchars($item["label"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="<?php echo $item["start_date"]; ?>" required>
                </div>

                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="<?php echo $item["end_date"]; ?>" required>
                </div>

                <div class="form-group">
                    <label>Price Per Night</label>
                    <input type="number" name="price_per_night" value="<?php echo $item["price_per_night"]; ?>" required>
                </div>

                <button type="submit">Update</button>
            </form>

            <form action="index.php?route=do-admin-delete-seasonal-pricing" method="POST" onsubmit="return confirm('Delete pricing?');">
                <input type="hidden" name="id" value="<?php echo $item["id"]; ?>">
                <button type="submit" class="btn-danger">Delete</button>
            </form>
        </div>
    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=admin-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>