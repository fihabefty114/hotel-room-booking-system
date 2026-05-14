<!DOCTYPE html>
<html>
<head>
    <title>Manage Guests</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Manage Guest Accounts</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Update</th>
        </tr>

        <?php foreach ($guests as $guest) { ?>
            <tr>
                <td><?php echo $guest["id"]; ?></td>
                <td><?php echo htmlspecialchars($guest["name"]); ?></td>
                <td><?php echo htmlspecialchars($guest["email"]); ?></td>
                <td><?php echo htmlspecialchars($guest["phone"]); ?></td>
                <td><?php if ($guest["is_active"] == 1) { echo "Active"; } else { echo "Inactive"; } ?></td>
                <td>
                    <form action="index.php?route=do-admin-update-guest-status" method="POST">
                        <input type="hidden" name="id" value="<?php echo $guest["id"]; ?>">
                        <select name="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div class="nav-links">
        <a class="btn" href="index.php?route=admin-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>