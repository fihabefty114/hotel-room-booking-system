<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container auth-container">

    <h2>Change Password</h2>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error">
            <?php echo $_SESSION["error"]; ?>
        </div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <form action="index.php?route=do-guest-change-password" method="POST">

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" >
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" >
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" >
        </div>

        <button type="submit">Change Password</button>
    </form>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-profile">Back to Profile</a>
    </div>

</div>

</body>
</html>