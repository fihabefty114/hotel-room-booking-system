<!DOCTYPE html>
<html>
<head>
    <title>Manage Room Types</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Manage Room Types</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="booking-card">
        <h3>Add Room Type</h3>

        <form action="index.php?route=do-admin-create-room-type" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" >
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" ></textarea>
            </div>

            <div class="form-group">
                <label>Price Per Night</label>
                <input type="number" name="price_per_night" min="1" >
            </div>

            <div class="form-group">
                <label>Max Capacity</label>
                <input type="number" name="max_capacity" min="1" >
            </div>

            <div class="form-group">
                <label>Upload Thumbnail Photo</label>
                <input type="file" name="thumbnail_photo" accept=".jpg,.jpeg,.png">
                <small>Allowed: JPG, JPEG, PNG. Maximum size: 2MB.</small>
            </div>

            <div class="form-group">
                <label>Amenities comma separated</label>
                <textarea name="amenities" rows="2">WiFi, AC, TV</textarea>
            </div>

            <button type="submit">Add Room Type</button>
        </form>
    </div>

    <h3>Existing Room Types</h3>

    <?php if (count($roomTypes) === 0) { ?>
        <p>No room type found.</p>
    <?php } ?>

    <?php foreach ($roomTypes as $type) { ?>
        <div class="booking-card">

            <h3><?php echo htmlspecialchars($type["name"]); ?></h3>

            <?php if ($type["thumbnail_path"] !== "") { ?>
                <div class="form-group">
                    <label>Current Thumbnail</label>
                    <br>
                    <img src="<?php echo htmlspecialchars($type["thumbnail_path"]); ?>" alt="Room Thumbnail" style="width:180px; height:110px; object-fit:cover; border-radius:8px; border:1px solid #d5dcff;">
                </div>
            <?php } ?>

            <form action="index.php?route=do-admin-update-room-type" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $type["id"]; ?>">
                <input type="hidden" name="old_thumbnail_path" value="<?php echo htmlspecialchars($type["thumbnail_path"]); ?>">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($type["name"]); ?>" >
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" ><?php echo htmlspecialchars($type["description"]); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Price Per Night</label>
                    <input type="number" name="price_per_night" value="<?php echo $type["price_per_night"]; ?>">
                </div>

                <div class="form-group">
                    <label>Max Capacity</label>
                    <input type="number" name="max_capacity" value="<?php echo $type["max_capacity"]; ?>" >
                </div>

                <div class="form-group">
                    <label>Upload New Thumbnail Photo</label>
                    <input type="file" name="thumbnail_photo" accept=".jpg,.jpeg,.png">
                    <small>Leave empty if you do not want to change the current photo.</small>
                </div>

                <div class="form-group">
                    <label>Amenities</label>
                    <textarea name="amenities" rows="2"><?php echo htmlspecialchars($type["amenities"]); ?></textarea>
                </div>

                <button type="submit">Update</button>
            </form>

            <form action="index.php?route=do-admin-delete-room-type" method="POST" onsubmit="return confirm('Delete this room type?');">
                <input type="hidden" name="id" value="<?php echo $type["id"]; ?>">
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