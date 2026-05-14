<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Reports</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Maintenance Reports</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="booking-card">
        <h3>Submit Maintenance Report</h3>

        <form action="index.php?route=do-housekeeping-maintenance" method="POST">
            <div class="form-group">
                <label>Select Room</label>
                <select name="room_id" required>
                    <?php foreach ($rooms as $room) { ?>
                        <option value="<?php echo $room["id"]; ?>">
                            Room <?php echo htmlspecialchars($room["room_number"]); ?> -
                            <?php echo htmlspecialchars($room["room_type_name"]); ?> -
                            Status: <?php echo htmlspecialchars($room["status"]); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Issue Type</label>
                <select name="issue_type" required>
                    <option value="electricity">Electricity</option>
                    <option value="plumbing">Plumbing</option>
                    <option value="furniture">Furniture</option>
                    <option value="ac">AC</option>
                    <option value="cleaning_damage">Cleaning Damage</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" required></textarea>
            </div>

            <button type="submit">Submit Report</button>
        </form>
    </div>

    <h3>My Maintenance Reports</h3>

    <?php if (count($reports) === 0) { ?>
        <p>No maintenance report found.</p>
    <?php } else { ?>
        <table class="data-table">
            <tr>
                <th>Report ID</th>
                <th>Room</th>
                <th>Issue Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Reported At</th>
            </tr>

            <?php foreach ($reports as $report) { ?>
                <tr>
                    <td><?php echo $report["id"]; ?></td>
                    <td><?php echo htmlspecialchars($report["room_number"]); ?></td>
                    <td><?php echo htmlspecialchars($report["issue_type"]); ?></td>
                    <td><?php echo htmlspecialchars($report["description"]); ?></td>
                    <td><span class="status-badge"><?php echo htmlspecialchars($report["status"]); ?></span></td>
                    <td><?php echo $report["reported_at"]; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=housekeeping-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
