<!DOCTYPE html>
<html>
<head>
    <title>Process Payments</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Process Guest Payments</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <form action="index.php" method="GET">
        <input type="hidden" name="route" value="receptionist-payments">

        <div class="form-group">
            <label>Search by Booking ID or Guest Name</label>
            <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" >
        </div>

        <button type="submit">Search Bill</button>
    </form>

    <hr>

    <?php if ($keyword !== "") { ?>

        <?php if (count($bills) === 0) { ?>
            <p>No pending bill found.</p>
        <?php } ?>

        <?php foreach ($bills as $bill) { ?>
            <div class="booking-card">
                <h3>Bill ID: <?php echo $bill["id"]; ?></h3>

                <p><strong>Booking ID:</strong> <?php echo $bill["booking_id"]; ?></p>
                <p><strong>Guest:</strong> <?php echo htmlspecialchars($bill["guest_name"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($bill["email"]); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($bill["room_type_name"]); ?></p>
                <p><strong>Base Amount:</strong> <?php echo $bill["base_amount"]; ?> BDT</p>
                <p><strong>Extras:</strong> <?php echo $bill["extras_amount"]; ?> BDT</p>
                <p><strong>Discount:</strong> <?php echo $bill["discount_amount"]; ?> BDT</p>
                <p><strong>Total Amount:</strong> <?php echo $bill["total_amount"]; ?> BDT</p>
                <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($bill["payment_status"]); ?></p>

                <form action="index.php?route=do-receptionist-payment" method="POST">
                    <input type="hidden" name="billing_id" value="<?php echo $bill["id"]; ?>">

                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" >
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile_banking">Mobile Banking</option>
                        </select>
                    </div>

                    <button type="submit">Mark as Paid</button>
                </form>
            </div>
        <?php } ?>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=receptionist-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
