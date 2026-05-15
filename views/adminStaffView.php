<!DOCTYPE html>
<html>
<head>
    <title>Manage Staff</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Manage Staff Accounts</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="booking-card">
        <h3>Create Staff</h3>

        <form action="index.php?route=do-admin-create-staff" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <input type="text" name="nationality" placeholder="Nationality" required>
            <input type="text" name="id_number" placeholder="ID Number" required>

            <select name="role">
                <option value="receptionist">Receptionist</option>
                <option value="housekeeping">Housekeeping</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Create Staff</button>
        </form>
    </div>

    <h3>Staff List</h3>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Update Info</th>
        </tr>

        <?php foreach ($staffList as $staff) { ?>
            <tr>
                <td><?php echo $staff["id"]; ?></td>
                <td><?php echo htmlspecialchars($staff["email"]); ?></td>
                <td>
                    <form action="index.php?route=do-admin-update-staff" method="POST">
                        <input type="hidden" name="id" value="<?php echo $staff["id"]; ?>">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($staff["name"]); ?>">
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($staff["phone"]); ?>">
                        <input type="text" name="nationality" value="<?php echo htmlspecialchars($staff["nationality"]); ?>">
                        <input type="text" name="id_number" value="<?php echo htmlspecialchars($staff["id_number"]); ?>">

                        <select name="role">
                            <option value="admin" <?php if ($staff["role"] === "admin") { echo "selected"; } ?>>Admin</option>
                            <option value="receptionist" <?php if ($staff["role"] === "receptionist") { echo "selected"; } ?>>Receptionist</option>
                            <option value="housekeeping" <?php if ($staff["role"] === "housekeeping") { echo "selected"; } ?>>Housekeeping</option>
                        </select>

                        <select name="is_active">
                            <option value="1" <?php if ($staff["is_active"] == 1) { echo "selected"; } ?>>Active</option>
                            <option value="0" <?php if ($staff["is_active"] == 0) { echo "selected"; } ?>>Inactive</option>
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