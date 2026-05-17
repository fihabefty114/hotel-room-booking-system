<!DOCTYPE html>
<html>
<head>
    <title>Admin Room Status AJAX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/adminAjax.js"></script>
</head>
<body onload="loadAdminRoomStatus()">

<div class="container wide-container">

    <h2>Admin Room Status Board</h2>

    <p>This room status board loads using AJAX without full page reload.</p>

    <button type="button" onclick="loadAdminRoomStatus()">Refresh Room Status</button>

    <div id="adminRoomStatusResult">
        <p>Loading room status...</p>
    </div>

    <div class="nav-links">
        <a class="btn" href="index.php?route=admin-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>