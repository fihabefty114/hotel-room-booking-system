<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Room Status</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/receptionistAjax.js"></script>
</head>
<body onload="loadRoomStatus()">

<div class="container wide-container">

    <h2>Room Status Board</h2>


    <button type="button" onclick="loadRoomStatus()">Refresh Room Status</button>

    <div id="roomStatusResult">
        <p>Loading room status...</p>
    </div>

    <div class="nav-links">
        <a class="btn" href="index.php?route=receptionist-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>