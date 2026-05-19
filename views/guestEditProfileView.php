<!DOCTYPE html>
<html>
<head>
    <title>Edit Guest Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container auth-container">

    <h2>Edit Profile</h2>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error">
            <?php echo $_SESSION["error"]; ?>
        </div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <form action="index.php?route=do-guest-update-profile" method="POST">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($guest["name"]); ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($guest["email"]); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($guest["phone"]); ?>" >
        </div>

        <div class="form-group">
            <label>Nationality</label>
            <input type="text" name="nationality" value="<?php echo htmlspecialchars($guest["nationality"]); ?>" >
        </div>

        <div class="form-group">
            <label>ID Number</label>
            <input type="text" name="id_number" value="<?php echo htmlspecialchars($guest["id_number"]); ?>" >
        </div>

        <button type="submit">Update Profile</button>
    </form>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-profile">Back to Profile</a>
    </div>

</div>

</body>
</html>