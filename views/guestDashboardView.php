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

    <div class="feature-grid">
        <a class="feature-card" href="index.php?route=guest-profile">Manage Profile</a>
        <a class="feature-card" href="index.php?route=guest-search-rooms">Search Available Rooms with AJAX</a>        
        <a class="feature-card" href="index.php?route=guest-search-rooms">Book Room</a>
        <a class="feature-card" href="#">My Bookings - Next phase</a>
        <a class="feature-card" href="#">Service Requests - Next phase</a>
        <a class="feature-card" href="#">Reviews - Next phase</a>
        <a class="feature-card" href="index.php?route=guest-profile">Loyalty Points</a>
        <a class="feature-card" href="#">Billing History - Next phase</a>
    </div>

    <div class="nav-links">
        <a class="btn btn-danger" href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>