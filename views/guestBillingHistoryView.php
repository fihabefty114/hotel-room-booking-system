<!DOCTYPE html>
<html>
<head>
    <title>Billing History</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Billing History</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="info-box">
        <p><strong>Current Loyalty Points:</strong> <?php echo $loyaltyBalance; ?></p>
        <p>Point rule used here: 1 point = 1 BDT discount.</p>
    </div>

    <?php if (count($bills) === 0) { ?>
        <p>No billing history found.</p>
    <?php } else { ?>

        <table class="data-table">
            <tr>
                <th>Bill ID</th>
                <th>Booking</th>
                <th>Room Type</th>
                <th>Base</th>
                <th>Extras</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Action</th>
            </tr>

            <?php foreach ($bills as $bill) { ?>
                <tr>
                    <td><?php echo $bill["id"]; ?></td>
                    <td><?php echo $bill["booking_id"]; ?></td>
                    <td><?php echo htmlspecialchars($bill["room_type_name"]); ?></td>
                    <td><?php echo $bill["base_amount"]; ?> BDT</td>
                    <td><?php echo $bill["extras_amount"]; ?> BDT</td>
                    <td><?php echo $bill["discount_amount"]; ?> BDT</td>
                    <td><?php echo $bill["total_amount"]; ?> BDT</td>
                    <td><span class="status-badge"><?php echo htmlspecialchars($bill["payment_status"]); ?></span></td>
                    <td>
                        <?php if ($bill["payment_status"] === "pending" && $loyaltyBalance > 0 && $bill["discount_amount"] == 0) { ?>
                            <form action="index.php?route=do-guest-redeem-points" method="POST">
                                <input type="hidden" name="billing_id" value="<?php echo $bill["id"]; ?>">
                                <button type="submit">Redeem Points</button>
                            </form>
                        <?php } else { ?>
                            No action
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-dashboard">Back to Dashboard</a>
        <a class="btn" href="index.php?route=guest-profile">View Loyalty History</a>
    </div>

</div>

</body>
</html>