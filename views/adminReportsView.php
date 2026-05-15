<!DOCTYPE html>
<html>
<head>
    <title>Admin Reports</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2>Hotel Report</h2>

    <div class="booking-card">
        <p><strong>Total Revenue:</strong> <?php echo $report["total_revenue"]; ?> BDT</p>
        <p><strong>Paid Bills:</strong> <?php echo $report["paid_bills"]; ?></p>
        <p><strong>Pending Bills:</strong> <?php echo $report["pending_bills"]; ?></p>
        <p><strong>Confirmed Bookings:</strong> <?php echo $report["confirmed_bookings"]; ?></p>
        <p><strong>Checked-in Bookings:</strong> <?php echo $report["checked_in_bookings"]; ?></p>
        <p><strong>Checked-out Bookings:</strong> <?php echo $report["checked_out_bookings"]; ?></p>
        <p><strong>Cancelled Bookings:</strong> <?php echo $report["cancelled_bookings"]; ?></p>
        <p><strong>Total Service Requests:</strong> <?php echo $report["service_requests"]; ?></p>
        <p><strong>Total Maintenance Reports:</strong> <?php echo $report["maintenance_reports"]; ?></p>
    </div>

    <div class="nav-links no-print">
        <button type="button" onclick="window.print()">Print / Save as PDF</button>
        <a class="btn" href="index.php?route=admin-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>