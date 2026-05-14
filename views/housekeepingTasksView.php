<!DOCTYPE html>
<html>
<head>
    <title>Housekeeping Tasks</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Cleaning Tasks</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <h3>Dirty Rooms</h3>

    <?php if (count($dirtyRooms) === 0) { ?>
        <p>No dirty room found.</p>
    <?php } else { ?>
        <table class="data-table">
            <tr>
                <th>Room ID</th>
                <th>Room Number</th>
                <th>Floor</th>
                <th>Room Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($dirtyRooms as $room) { ?>
                <tr>
                    <td><?php echo $room["id"]; ?></td>
                    <td><?php echo htmlspecialchars($room["room_number"]); ?></td>
                    <td><?php echo $room["floor"]; ?></td>
                    <td><?php echo htmlspecialchars($room["room_type_name"]); ?></td>
                    <td>
                        <span class="status-badge">
                            <?php echo htmlspecialchars($room["status"]); ?>
                        </span>
                    </td>
                    <td>
                        <form action="index.php?route=do-housekeeping-take-task" method="POST">
                            <input type="hidden" name="room_id" value="<?php echo $room["id"]; ?>">
                            <button type="submit">Take Cleaning Task</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <hr>

    <h3>My Tasks</h3>

    <?php if (count($tasks) === 0) { ?>
        <p>No task assigned yet.</p>
    <?php } else { ?>
        <table class="data-table">
            <tr>
                <th>Task ID</th>
                <th>Room</th>
                <th>Floor</th>
                <th>Room Type</th>
                <th>Task Type</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Update</th>
            </tr>

            <?php foreach ($tasks as $task) { ?>
                <tr>
                    <td><?php echo $task["id"]; ?></td>
                    <td><?php echo htmlspecialchars($task["room_number"]); ?></td>
                    <td><?php echo $task["floor"]; ?></td>
                    <td><?php echo htmlspecialchars($task["room_type_name"]); ?></td>
                    <td><?php echo htmlspecialchars($task["task_type"]); ?></td>
                    <td>
                        <span class="status-badge">
                            <?php echo htmlspecialchars($task["status"]); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($task["notes"]); ?></td>
                    <td>
                        <?php if ($task["status"] !== "done") { ?>
                            <form action="index.php?route=do-housekeeping-update-task" method="POST">
                                <input type="hidden" name="task_id" value="<?php echo $task["id"]; ?>">

                                <select name="status">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="done">Done</option>
                                </select>

                                <button type="submit">Update</button>
                            </form>
                        <?php } else { ?>
                            Done
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=housekeeping-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>