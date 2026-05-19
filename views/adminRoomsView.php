<!DOCTYPE html>
<html>
<head>
    <title>Manage Rooms</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Manage Rooms</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="booking-card">
        <h3>Add Room</h3>

        <form action="index.php?route=do-admin-create-room" method="POST">
            <div class="form-group">
                <label>Room Type</label>
                <select name="room_type_id" >
                    <?php foreach ($roomTypes as $type) { ?>
                        <option value="<?php echo $type["id"]; ?>"><?php echo htmlspecialchars($type["name"]); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Room Number</label>
                <input type="text" name="room_number" >
            </div>

            <div class="form-group">
                <label>Floor</label>
                <input type="number" name="floor" >
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="dirty">Dirty</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" rows="2"></textarea>
            </div>

            <button type="submit">Add Room</button>
        </form>
    </div>

    <h3>Room List</h3>

    <?php foreach ($rooms as $room) { ?>
        <div class="booking-card">
            <form action="index.php?route=do-admin-update-room" method="POST">
                <input type="hidden" name="id" value="<?php echo $room["id"]; ?>">

                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type_id">
                        <?php foreach ($roomTypes as $type) { ?>
                            <option value="<?php echo $type["id"]; ?>" <?php if ($type["id"] == $room["room_type_id"]) { echo "selected"; } ?>>
                                <?php echo htmlspecialchars($type["name"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Room Number</label>
                    <input type="text" name="room_number" value="<?php echo htmlspecialchars($room["room_number"]); ?>">
                </div>

                <div class="form-group">
                    <label>Floor</label>
                    <input type="number" name="floor" value="<?php echo $room["floor"]; ?>" >
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="available" <?php if ($room["status"] === "available") { echo "selected"; } ?>>Available</option>
                        <option value="occupied" <?php if ($room["status"] === "occupied") { echo "selected"; } ?>>Occupied</option>
                        <option value="dirty" <?php if ($room["status"] === "dirty") { echo "selected"; } ?>>Dirty</option>
                        <option value="maintenance" <?php if ($room["status"] === "maintenance") { echo "selected"; } ?>>Maintenance</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="2"><?php echo htmlspecialchars($room["notes"]); ?></textarea>
                </div>

                <button type="submit">Update</button>
            </form>

            <form action="index.php?route=do-admin-delete-room" method="POST" onsubmit="return confirm('Delete this room?');">
                <input type="hidden" name="id" value="<?php echo $room["id"]; ?>">
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