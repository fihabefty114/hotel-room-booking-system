<!DOCTYPE html>
<html>
<head>
    <title>Search Available Rooms</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/guestRoomSearch.js"></script>
</head>
<body>

<div class="container">

    <h2>Search Available Rooms</h2>

    <p>Enter your stay information</p>

    <div class="search-box">
        <div class="form-group">
            <label>Check-in Date</label>
            <input type="date" id="checkin_date" required>
        </div>

        <div class="form-group">
            <label>Check-out Date</label>
            <input type="date" id="checkout_date" required>
        </div>

        <div class="form-group">
            <label>Number of Guests</label>
            <input type="number" id="num_guests" min="1" required>
        </div>

        <button type="button" onclick="searchAvailableRooms()">Search Rooms</button>
    </div>

    <hr>

    <h3>Available Room Types</h3>

    <div id="roomSearchMessage"></div>

    <div id="roomSearchResult">
        <p>No search performed yet.</p>
    </div>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-dashboard">Back to Dashboard</a>
        <a class="btn btn-danger" href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>