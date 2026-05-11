<!DOCTYPE html>
<html>
<head>
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2>Guest Dashboard</h2>

    <div class="info-box">
        <p><strong>Welcome:</strong> <?php echo htmlspecialchars($guest["name"]); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($guest["email"]); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($guest["phone"]); ?></p>
        <p><strong>Nationality:</strong> <?php echo htmlspecialchars($guest["nationality"]); ?></p>
        <p><strong>ID Number:</strong> <?php echo htmlspecialchars($guest["id_number"]); ?></p>
    </div>

    <h3>Guest Features</h3>

    <ul class="feature-list">
        <li>Manage Profile - Next phase</li>
        <li>Search Available Rooms with AJAX - Next phase</li>
        <li>Book Room - Next phase</li>
        <li>My Bookings - Next phase</li>
        <li>Service Requests - Next phase</li>
        <li>Reviews - Next phase</li>
        <li>Loyalty Points - Next phase</li>
        <li>Billing History - Next phase</li>
    </ul>

    <div class="nav-links">
        <a class="btn btn-danger" href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>