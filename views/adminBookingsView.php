<!DOCTYPE html>
<html>
<head>
    <title>Admin Bookings</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>All Bookings</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Guest</th>
            <th>Room Type</th>
            <th>Room</th>
            <th>Dates</th>
            <th>Total</th>
            <th>Status</th>
            <th>Update</th>
        </tr>

        <?php foreach ($bookings as $booking) { ?>
            <tr>
                <td><?php echo $booking["id"]; ?></td>
                <td><?php echo htmlspecialchars($booking["guest_name"]); ?></td>
                <td><?php echo htmlspecialchars($booking["room_type_name"]); ?></td>
                <td>
                    <?php
                        if ($booking["room_number"] !== null) {
                            echo htmlspecialchars($booking["room_number"]);
                        } else {
                            echo "Not assigned";
                        }
                    ?>
                </td>
                <td><?php echo $booking["checkin_date"]; ?> to <?php echo $booking["checkout_date"]; ?></td>
                <td><?php echo $booking["total_price"]; ?> BDT</td>
                <td><?php echo htmlspecialchars($booking["status"]); ?></td>
                <td>
                    <form action="index.php?route=do-admin-update-booking-status" method="POST">
                        <input type="hidden" name="id" value="<?php echo $booking["id"]; ?>">

                        <select name="status">
                            <option value="confirmed">Confirmed</option>
                            <option value="checked_in">Checked In</option>
                            <option value="checked_out">Checked Out</option>
                            <option value="cancelled">Cancelled</option>
                        </select>

                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div class="nav-links">
        <a class="btn" href="index.php?route=admin-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>