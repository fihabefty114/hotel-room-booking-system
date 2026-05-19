<?php
if (!isset($keyword)) {
    $keyword = "";
}

if (!isset($bookings)) {
    $bookings = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Check Out</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Check Out Guest</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success">
            <?php echo $_SESSION["success"]; ?>
        </div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error">
            <?php echo $_SESSION["error"]; ?>
        </div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <form id="checkOutSearchForm" action="index.php" method="GET" novalidate>
        <input type="hidden" name="route" value="receptionist-check-out">

        <div class="form-group">
            <label>Search by Room Number or Guest Name</label>
            <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>">
        </div>

        <button type="submit">Search</button>
    </form>

    <hr>

    <?php if ($keyword !== "") { ?>

        <?php if (count($bookings) === 0) { ?>
            <p>No checked-in booking found.</p>
        <?php } ?>

        <?php foreach ($bookings as $booking) { ?>
            <div class="booking-card">
                <h3>Booking ID: <?php echo htmlspecialchars($booking["id"]); ?></h3>

                <p><strong>Guest:</strong> <?php echo htmlspecialchars($booking["guest_name"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking["email"]); ?></p>
                <p><strong>Room Number:</strong> <?php echo htmlspecialchars($booking["room_number"]); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($booking["room_type_name"]); ?></p>
                <p><strong>Check-in:</strong> <?php echo htmlspecialchars($booking["checkin_date"]); ?></p>
                <p><strong>Check-out:</strong> <?php echo htmlspecialchars($booking["checkout_date"]); ?></p>

                <form class="checkOutConfirmForm" action="index.php?route=do-receptionist-check-out" method="POST" novalidate>
                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking["id"]); ?>">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($booking["room_id"]); ?>">

                    <button type="submit">Confirm Check Out</button>
                </form>
            </div>
        <?php } ?>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=receptionist-dashboard">Back to Dashboard</a>
    </div>

</div>

<script>
document.getElementById("checkOutSearchForm").addEventListener("submit", function(event) {
    var keyword = this.querySelector("[name='keyword']").value.trim();

    if (keyword === "") {
        event.preventDefault();
        alert("Please enter Room Number or Guest Name.");
        return;
    }
});

var checkOutForms = document.querySelectorAll(".checkOutConfirmForm");

checkOutForms.forEach(function(form) {
    form.addEventListener("submit", function(event) {
        var bookingId = form.querySelector("[name='booking_id']").value.trim();
        var roomId = form.querySelector("[name='room_id']").value.trim();

        if (bookingId === "") {
            event.preventDefault();
            alert("Booking ID is missing.");
            return;
        }

        if (roomId === "") {
            event.preventDefault();
            alert("Room ID is missing.");
            return;
        }

        var confirmCheckOut = confirm("Are you sure you want to check out this guest?");

        if (!confirmCheckOut) {
            event.preventDefault();
            return;
        }
    });
});
</script>

</body>
</html>