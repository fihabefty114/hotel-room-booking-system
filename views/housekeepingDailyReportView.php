<!DOCTYPE html>
<html>
<head>
    <title>Housekeeping Daily Report</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2>Housekeeping Daily Report</h2>

    <div class="booking-card">
        <p><strong>Completed Tasks Today:</strong> <?php echo $report["completed_tasks_today"]; ?></p>
        <p><strong>In-progress Tasks:</strong> <?php echo $report["in_progress_tasks"]; ?></p>
        <p><strong>Dirty Rooms:</strong> <?php echo $report["dirty_rooms"]; ?></p>
        <p><strong>Available Rooms:</strong> <?php echo $report["available_rooms"]; ?></p>
        <p><strong>Maintenance Reports Today:</strong> <?php echo $report["maintenance_reports_today"]; ?></p>
    </div>

    <div class="nav-links no-print">
        <button type="button" onclick="window.print()">Print Report</button>
        <a class="btn" href="index.php?route=housekeeping-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
