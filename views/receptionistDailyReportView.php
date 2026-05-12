<!DOCTYPE html>
<html>
<head>
    <title>Daily Operations Report</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2>Daily Operations Report</h2>

    <div class="booking-card">
        <p><strong>Total Arrivals Today:</strong> <?php echo $report["arrivals"]; ?></p>
        <p><strong>Total Departures Today:</strong> <?php echo $report["departures"]; ?></p>
        <p><strong>Revenue Collected Today:</strong> <?php echo $report["revenue"]; ?> BDT</p>
        <p><strong>Occupied Rooms:</strong> <?php echo $report["occupied_rooms"]; ?></p>
        <p><strong>Available Rooms:</strong> <?php echo $report["available_rooms"]; ?></p>
    </div>

    <div class="nav-links no-print">
        <button type="button" onclick="window.print()">Print Report</button>
        <a class="btn" href="index.php?route=receptionist-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
