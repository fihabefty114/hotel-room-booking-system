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

    <form id="registerForm" action="index.php?route=do-register" method="POST" novalidate>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $oldName; ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" value="<?php echo $oldEmail; ?>">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $oldPhone; ?>">
        </div>

        <div class="form-group">
            <label>Nationality</label>
            <input type="text" name="nationality" value="<?php echo $oldNationality; ?>">
        </div>

        <div class="form-group">
            <label>ID Number</label>
            <input type="text" name="id_number" value="<?php echo $oldIdNumber; ?>">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password">
        </div>

        <button type="submit">Register</button>
    </form>

    <div class="nav-links">
        <p>Already have an account? <a href="index.php?route=login">Login here</a></p>
    </div>

</div>

<script>
document.getElementById("registerForm").addEventListener("submit", function(event) {
    var name = this.querySelector("[name='name']").value.trim();
    var email = this.querySelector("[name='email']").value.trim();
    var phone = this.querySelector("[name='phone']").value.trim();
    var nationality = this.querySelector("[name='nationality']").value.trim();
    var idNumber = this.querySelector("[name='id_number']").value.trim();
    var password = this.querySelector("[name='password']").value.trim();
    var confirmPassword = this.querySelector("[name='confirm_password']").value.trim();

    if (
        name === "" ||
        email === "" ||
        phone === "" ||
        nationality === "" ||
        idNumber === "" ||
        password === "" ||
        confirmPassword === ""
    ) {
        event.preventDefault();
        alert("All fields are required.");
        return;
    }

    if (!isValidEmail(email)) {
        event.preventDefault();
        alert("Please enter a valid email address.");
        return;
    }

    if (password.length < 6) {
        event.preventDefault();
        alert("Password must be at least 6 characters.");
        return;
    }

    if (password !== confirmPassword) {
        event.preventDefault();
        alert("Password and confirm password do not match.");
        return;
    }
});

function isValidEmail(email) {
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}
</script>

</body>
</html>

