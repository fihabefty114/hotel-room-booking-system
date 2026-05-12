<!DOCTYPE html>
<html>
<head>
    <title>Receptionist Service Requests</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Manage Guest Service Requests</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <?php if (count($requests) === 0) { ?>
        <p>No pending or in-progress service requests.</p>
    <?php } else { ?>

        <table class="data-table">
            <tr>
                <th>Guest</th>
                <th>Room</th>
                <th>Service</th>
                <th>Description</th>
                <th>Status</th>
                <th>Update</th>
            </tr>

            <?php foreach ($requests as $request) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($request["guest_name"]); ?></td>
                    <td><?php echo htmlspecialchars($request["room_number"]); ?></td>
                    <td><?php echo htmlspecialchars($request["service_type"]); ?></td>
                    <td><?php echo htmlspecialchars($request["description"]); ?></td>
                    <td><?php echo htmlspecialchars($request["status"]); ?></td>
                    <td>
                        <form action="index.php?route=do-update-service-request-status" method="POST">
                            <input type="hidden" name="request_id" value="<?php echo $request["id"]; ?>">

                            <select name="status">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>

                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=receptionist-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
