<!DOCTYPE html>
<html>
<head>
    <title><?php echo $dashboardTitle; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2><?php echo $dashboardTitle; ?></h2>

    <div class="info-box">
        <p><strong>Welcome:</strong> <?php echo htmlspecialchars($_SESSION["name"]); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION["role"]); ?></p>
    </div>

    <p>This dashboard will be implemented by the assigned group member.</p>

    <div class="nav-links">
        <a class="btn btn-danger" href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>