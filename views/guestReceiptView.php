<!DOCTYPE html>
<html>
<head>
    <title>Receipt View</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2>Hotel Room Booking Receipt</h2>

    <div class="receipt-box">
        <p><strong>Bill ID:</strong> <?php echo $receipt["id"]; ?></p>
        <p><strong>Booking ID:</strong> <?php echo $receipt["booking_id"]; ?></p>
        <p><strong>Guest Name:</strong> <?php echo htmlspecialchars($receipt["guest_name"]); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($receipt["email"]); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($receipt["phone"]); ?></p>
        <p><strong>Room Type:</strong> <?php echo htmlspecialchars($receipt["room_type_name"]); ?></p>
        <p><strong>Check-in:</strong> <?php echo $receipt["checkin_date"]; ?></p>
        <p><strong>Check-out:</strong> <?php echo $receipt["checkout_date"]; ?></p>
        <p><strong>Number of Guests:</strong> <?php echo $receipt["num_guests"]; ?></p>

        <hr>

        <p><strong>Base Amount:</strong> <?php echo $receipt["base_amount"]; ?> BDT</p>
        <p><strong>Extras Amount:</strong> <?php echo $receipt["extras_amount"]; ?> BDT</p>
        <p><strong>Discount Amount:</strong> <?php echo $receipt["discount_amount"]; ?> BDT</p>
        <p><strong>Total Amount:</strong> <?php echo $receipt["total_amount"]; ?> BDT</p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($receipt["payment_method"]); ?></p>
        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($receipt["payment_status"]); ?></p>
    </div>

    <div class="nav-links no-print">
        <button type="button" onclick="window.print()">Print Receipt</button>
        <a class="btn" href="index.php?route=guest-download-receipt&billing_id=<?php echo $receipt["id"]; ?>">Download Receipt</a>
        <a class="btn" href="index.php?route=guest-billing-history">Back to Billing</a>
    </div>

</div>

</body>
</html>