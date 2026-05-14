<!DOCTYPE html>
<html>
<head>
    <title>Housekeeping Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container auth-container">

    <h2>Housekeeping Profile</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <form action="index.php?route=do-housekeeping-profile-update" method="POST">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($profile["name"]); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($profile["email"]); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($profile["phone"]); ?>" required>
        </div>

        <div class="form-group">
            <label>ID Number</label>
            <input type="text" name="id_number" value="<?php echo htmlspecialchars($profile["id_number"]); ?>" required>
        </div>

        <button type="submit">Update Profile</button>
    </form>

    <div class="nav-links">
        <a class="btn" href="index.php?route=housekeeping-dashboard">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
