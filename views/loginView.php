<!DOCTYPE html>
<html>
<head>
    <title>Login - Hotel Room Booking System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container auth-container">

    <h2>Hotel Room Booking System</h2>
    <h3>Login</h3>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success">
            <?php echo $_SESSION["success"]; ?>
        </div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error">
            <?php echo $_SESSION["error"]; ?>
        </div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <form action="index.php?route=do-login" method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="nav-links">
        <p>New guest? <a href="index.php?route=register">Register here</a></p>
    </div>

    <hr>

    <div class="info-box">
        <h3>Demo Guest Login</h3>
        <p>Email: guest@gmail.com</p>
        <p>Password: guest123</p>
    </div>

</div>

</body>
</html>