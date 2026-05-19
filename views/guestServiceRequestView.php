<!DOCTYPE html>
<html>
<head>
    <title>Service Requests</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Service Requests</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="booking-card">
        <h3>Submit New Service Request</h3>

        <?php if (count($activeStays) === 0) { ?>
            <p>You have no active checked-in stay. Service request is available only during active stay.</p>
        <?php } else { ?>

            <form action="index.php?route=do-guest-service-request" method="POST">

                <div class="form-group">
                    <label>Select Active Booking</label>
                    <select name="booking_id" >
                        <?php foreach ($activeStays as $stay) { ?>
                            <option value="<?php echo $stay["id"]; ?>">
                                Booking <?php echo $stay["id"]; ?> -
                                Room <?php echo htmlspecialchars($stay["room_number"]); ?> -
                                <?php echo htmlspecialchars($stay["room_type_name"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Service Type</label>
                    <select name="service_type" >
                        <option value="extra_bed">Extra Bed</option>
                        <option value="toiletries">Toiletries</option>
                        <option value="laundry">Laundry</option>
                        <option value="room_service">Room Service</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" ></textarea>
                </div>

                <button type="submit">Submit Request</button>
            </form>

        <?php } ?>
    </div>

    <h3>My Service Request Status</h3>

    <?php if (count($requests) === 0) { ?>
        <p>No service request found.</p>
    <?php } else { ?>

        <table class="data-table">
            <tr>
                <th>Booking</th>
                <th>Room</th>
                <th>Service Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Requested At</th>
            </tr>

            <?php foreach ($requests as $request) { ?>
                <tr>
                    <td><?php echo $request["booking_id"]; ?></td>
                    <td><?php echo htmlspecialchars($request["room_number"]); ?></td>
                    <td><?php echo htmlspecialchars($request["service_type"]); ?></td>
                    <td><?php echo htmlspecialchars($request["description"]); ?></td>
                    <td><span class="status-badge"><?php echo htmlspecialchars($request["status"]); ?></span></td>
                    <td><?php echo $request["requested_at"]; ?></td>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>