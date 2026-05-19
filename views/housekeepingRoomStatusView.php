<!DOCTYPE html>
<html>
<head>
    <title>Housekeeping Room Status</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Room Status Board</h2>

    <button type="button" onclick="loadHousekeepingRoomStatus()">Refresh Room Status</button>

    <div id="housekeepingRoomStatusResult">
        <p>Loading...</p>
    </div>

    <div class="nav-links">
        <a class="btn" href="index.php?route=housekeeping-dashboard">Back to Dashboard</a>
    </div>

</div>

<script src="assets/js/housekeepingAjax.js?v=1"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadHousekeepingRoomStatus();
});
</script>

</body>
</html>