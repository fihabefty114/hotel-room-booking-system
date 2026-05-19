<?php
if (!isset($keyword)) {
    $keyword = "";
}

if (!isset($bills)) {
    $bills = array();
}
?>

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

    <form id="paymentSearchForm" action="index.php" method="GET" novalidate>
        <input type="hidden" name="route" value="receptionist-payments">

        <div class="form-group">
            <label>Search by Booking ID or Guest Name</label>
            <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>">
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
                <h3>Bill ID: <?php echo htmlspecialchars($bill["id"]); ?></h3>

                <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($bill["booking_id"]); ?></p>
                <p><strong>Guest:</strong> <?php echo htmlspecialchars($bill["guest_name"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($bill["email"]); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($bill["room_type_name"]); ?></p>
                <p><strong>Base Amount:</strong> <?php echo htmlspecialchars($bill["base_amount"]); ?> BDT</p>
                <p><strong>Extras:</strong> <?php echo htmlspecialchars($bill["extras_amount"]); ?> BDT</p>
                <p><strong>Discount:</strong> <?php echo htmlspecialchars($bill["discount_amount"]); ?> BDT</p>
                <p><strong>Total Amount:</strong> <?php echo htmlspecialchars($bill["total_amount"]); ?> BDT</p>
                <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($bill["payment_status"]); ?></p>

                <form class="paymentProcessForm" action="index.php?route=do-receptionist-payment" method="POST" novalidate>
                    <input type="hidden" name="billing_id" value="<?php echo htmlspecialchars($bill["id"]); ?>">

                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method">
                            <option value="">Select Payment Method</option>
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

<script>
document.getElementById("paymentSearchForm").addEventListener("submit", function(event) {
    var keyword = this.querySelector("[name='keyword']").value.trim();

    if (keyword === "") {
        event.preventDefault();
        alert("Please enter Booking ID or Guest Name.");
        return;
    }
});

var paymentForms = document.querySelectorAll(".paymentProcessForm");

paymentForms.forEach(function(form) {
    form.addEventListener("submit", function(event) {
        var billingId = form.querySelector("[name='billing_id']").value.trim();
        var paymentMethod = form.querySelector("[name='payment_method']").value.trim();

        if (billingId === "") {
            event.preventDefault();
            alert("Billing ID is missing.");
            return;
        }

        if (paymentMethod === "") {
            event.preventDefault();
            alert("Please select a payment method.");
            return;
        }
    });
});
</script>

</body>
</html>