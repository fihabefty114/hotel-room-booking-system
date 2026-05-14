<!DOCTYPE html>
<html>
<head>
    <title>Housekeeping Room Status</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/housekeepingAjax.js"></script>
</head>
<body onload="loadHousekeepingRoomStatus()">

<div class="container wide-container">

    <h2>Room Status Board</h2>

    <p>This room status board loads using AJAX without full page reload.</p>

    <button type="button" onclick="loadHousekeepingRoomStatus()">Refresh Room Status</button>

    <div id="housekeepingRoomStatusResult">
        <p>Loading...</p>
    </div>

    <div class="nav-links">
        <a class="btn" href="index.php?route=housekeeping-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
