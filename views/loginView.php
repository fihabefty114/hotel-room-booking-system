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

    <form id="loginForm" action="index.php?route=do-login" method="POST" novalidate>

        <div id="loginJsError" class="alert-error" style="display:none;"></div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="nav-links">
        <p>New guest? <a href="index.php?route=register">Register here</a></p>
    </div>

    <hr>

</div>

<script>
document.getElementById("loginForm").addEventListener("submit", function(event) {


    var email = this.querySelector("[name='email']").value.trim();
    var password = this.querySelector("[name='password']").value.trim();
    var errorBox = document.getElementById("loginJsError");

    errorBox.style.display = "none";
    errorBox.innerHTML = "";

    if (email === "" || password === "") {
        event.preventDefault();

        alert("Empty field found");

        errorBox.style.display = "block";
        errorBox.innerHTML = "Email and password are required.";
        return;
    }

    if (!isValidEmail(email)) {
        event.preventDefault();

        alert("Invalid email");

        errorBox.style.display = "block";
        errorBox.innerHTML = "Please enter a valid email address.";
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