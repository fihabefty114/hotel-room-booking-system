<?php
if (!isset($roomTypes)) {
    $roomTypes = array();
}

if (!isset($pricingList)) {
    $pricingList = array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seasonal Pricing</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container wide-container">

    <h2>Manage Seasonal Pricing</h2>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert-success"><?php echo $_SESSION["success"]; ?></div>
        <?php unset($_SESSION["success"]); ?>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert-error"><?php echo $_SESSION["error"]; ?></div>
        <?php unset($_SESSION["error"]); ?>
    <?php } ?>

    <div class="booking-card">
        <h3>Add Seasonal Pricing</h3>

        <form id="createSeasonalPricingForm" action="index.php?route=do-admin-create-seasonal-pricing" method="POST" novalidate>
            <div class="form-group">
                <label>Room Type</label>
                <select name="room_type_id">
                    <option value="">Select Room Type</option>

                    <?php foreach ($roomTypes as $type) { ?>
                        <option value="<?php echo htmlspecialchars($type["id"]); ?>">
                            <?php echo htmlspecialchars($type["name"]); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Label</label>
                <input type="text" name="label">
            </div>

            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date">
            </div>

            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date">
            </div>

            <div class="form-group">
                <label>Price Per Night</label>
                <input type="number" name="price_per_night" min="1">
            </div>

            <button type="submit">Add Pricing</button>
        </form>
    </div>

    <h3>Pricing List</h3>

    <?php foreach ($pricingList as $item) { ?>
        <div class="booking-card">
            <form class="updateSeasonalPricingForm" action="index.php?route=do-admin-update-seasonal-pricing" method="POST" novalidate>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item["id"]); ?>">

                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type_id">
                        <option value="">Select Room Type</option>

                        <?php foreach ($roomTypes as $type) { ?>
                            <option value="<?php echo htmlspecialchars($type["id"]); ?>" 
                                <?php if ($type["id"] == $item["room_type_id"]) { echo "selected"; } ?>>
                                <?php echo htmlspecialchars($type["name"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Label</label>
                    <input type="text" name="label" value="<?php echo htmlspecialchars($item["label"]); ?>">
                </div>

                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="<?php echo htmlspecialchars($item["start_date"]); ?>">
                </div>

                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="<?php echo htmlspecialchars($item["end_date"]); ?>">
                </div>

                <div class="form-group">
                    <label>Price Per Night</label>
                    <input type="number" name="price_per_night" min="1" value="<?php echo htmlspecialchars($item["price_per_night"]); ?>">
                </div>

                <button type="submit">Update</button>
            </form>

            <form action="index.php?route=do-admin-delete-seasonal-pricing" method="POST" onsubmit="return confirm('Delete pricing?');">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item["id"]); ?>">
                <button type="submit" class="btn-danger">Delete</button>
            </form>
        </div>
    <?php } ?>

    <div class="nav-links">
        <a class="btn" href="index.php?route=admin-dashboard">Back to Dashboard</a>
    </div>

</div>

<script>
document.getElementById("createSeasonalPricingForm").addEventListener("submit", function(event) {
    var roomTypeId = this.querySelector("[name='room_type_id']").value.trim();
    var label = this.querySelector("[name='label']").value.trim();
    var startDate = this.querySelector("[name='start_date']").value.trim();
    var endDate = this.querySelector("[name='end_date']").value.trim();
    var pricePerNight = this.querySelector("[name='price_per_night']").value.trim();

    if (roomTypeId === "" || label === "" || startDate === "" || endDate === "" || pricePerNight === "") {
        event.preventDefault();
        alert("All seasonal pricing fields are required.");
        return;
    }

    if (!isValidDate(startDate)) {
        event.preventDefault();
        alert("Start date is invalid.");
        return;
    }

    if (!isValidDate(endDate)) {
        event.preventDefault();
        alert("End date is invalid.");
        return;
    }

    if (endDate <= startDate) {
        event.preventDefault();
        alert("End date must be after start date.");
        return;
    }

    if (isNaN(pricePerNight) || Number(pricePerNight) <= 0) {
        event.preventDefault();
        alert("Price per night must be greater than 0.");
        return;
    }
});

var updateForms = document.querySelectorAll(".updateSeasonalPricingForm");

updateForms.forEach(function(form) {
    form.addEventListener("submit", function(event) {
        var roomTypeId = form.querySelector("[name='room_type_id']").value.trim();
        var label = form.querySelector("[name='label']").value.trim();
        var startDate = form.querySelector("[name='start_date']").value.trim();
        var endDate = form.querySelector("[name='end_date']").value.trim();
        var pricePerNight = form.querySelector("[name='price_per_night']").value.trim();

        if (roomTypeId === "" || label === "" || startDate === "" || endDate === "" || pricePerNight === "") {
            event.preventDefault();
            alert("All update fields are required.");
            return;
        }

        if (!isValidDate(startDate)) {
            event.preventDefault();
            alert("Start date is invalid.");
            return;
        }

        if (!isValidDate(endDate)) {
            event.preventDefault();
            alert("End date is invalid.");
            return;
        }

        if (endDate <= startDate) {
            event.preventDefault();
            alert("End date must be after start date.");
            return;
        }

        if (isNaN(pricePerNight) || Number(pricePerNight) <= 0) {
            event.preventDefault();
            alert("Price per night must be greater than 0.");
            return;
        }
    });
});

function isValidDate(value) {
    if (value === "") {
        return false;
    }

    var date = new Date(value);

    return !isNaN(date.getTime());
}
</script>

</body>
</html>