<!DOCTYPE html>
<html>
<head>
    <title>Guest Registration</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container auth-container">

    <h2>Guest Registration</h2>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error">
            <?php echo $_SESSION["error"]; ?>
        </div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <?php
        $oldName = "";
        $oldEmail = "";
        $oldPhone = "";
        $oldNationality = "";
        $oldIdNumber = "";

        if (isset($_SESSION["old_name"])) {
            $oldName = $_SESSION["old_name"];
        }

        if (isset($_SESSION["old_email"])) {
            $oldEmail = $_SESSION["old_email"];
        }

        if (isset($_SESSION["old_phone"])) {
            $oldPhone = $_SESSION["old_phone"];
        }

        if (isset($_SESSION["old_nationality"])) {
            $oldNationality = $_SESSION["old_nationality"];
        }

        if (isset($_SESSION["old_id_number"])) {
            $oldIdNumber = $_SESSION["old_id_number"];
        }
    ?>

    <form action="index.php?route=do-register" method="POST">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $oldName; ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $oldEmail; ?>" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $oldPhone; ?>" required>
        </div>

        <div class="form-group">
            <label>Nationality</label>
            <input type="text" name="nationality" value="<?php echo $oldNationality; ?>" required>
        </div>

        <div class="form-group">
            <label>ID Number</label>
            <input type="text" name="id_number" value="<?php echo $oldIdNumber; ?>" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit">Register</button>
    </form>

    <div class="nav-links">
        <p>Already have an account? <a href="index.php?route=login">Login here</a></p>
    </div>

</div>

</body>
</html>