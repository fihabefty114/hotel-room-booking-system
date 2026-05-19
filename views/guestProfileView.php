<!DOCTYPE html>
<html>
<head>
    <title>Guest Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h2>Guest Profile</h2>

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

    <div class="profile-layout">

        <div class="profile-picture-box">
            <?php
                $profilePic = "";

                if (isset($guest["profile_pic"])) {
                    $profilePic = $guest["profile_pic"];
                }

                if ($profilePic !== "") {
            ?>
                    <img src="<?php echo htmlspecialchars($profilePic); ?>" class="profile-picture" alt="Profile Picture">
            <?php } else { ?>
                    <div class="no-profile-picture">No Profile Picture</div>
            <?php } ?>

            <form action="index.php?route=do-guest-upload-profile-picture" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Upload Profile Picture</label>
                    <input type="file" name="profile_pic" >
                </div>

                <button type="submit">Upload Picture</button>
            </form>
        </div>

        <div class="profile-info-box">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($guest["name"]); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($guest["email"]); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($guest["phone"]); ?></p>
            <p><strong>Nationality:</strong> <?php echo htmlspecialchars($guest["nationality"]); ?></p>
            <p><strong>ID Number:</strong> <?php echo htmlspecialchars($guest["id_number"]); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($guest["role"]); ?></p>
            <p><strong>Loyalty Balance:</strong> <?php echo $loyaltyBalance; ?> points</p>

            <div class="nav-links">
                <a class="btn" href="index.php?route=guest-edit-profile">Edit Profile</a>
                <a class="btn" href="index.php?route=guest-change-password">Change Password</a>
            </div>
        </div>

    </div>

    <hr>

    <h3>Loyalty Points History</h3>

    <?php if (count($loyaltyHistory) === 0) { ?>
        <p>No loyalty points history found.</p>
    <?php } else { ?>
        <table class="data-table">
            <tr>
                <th>Booking ID</th>
                <th>Points Earned</th>
                <th>Points Used</th>
                <th>Balance</th>
                <th>Date</th>
            </tr>

            <?php foreach ($loyaltyHistory as $item) { ?>
                <tr>
                    <td><?php echo $item["booking_id"]; ?></td>
                    <td><?php echo $item["points_earned"]; ?></td>
                    <td><?php echo $item["points_used"]; ?></td>
                    <td><?php echo $item["balance"]; ?></td>
                    <td><?php echo $item["created_at"]; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=guest-dashboard">Back to Dashboard</a>
        <a class="btn btn-danger" href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>