function searchAvailableRooms() {
    var checkinDate = document.getElementById("checkin_date").value;
    var checkoutDate = document.getElementById("checkout_date").value;
    var numGuests = document.getElementById("num_guests").value;

    var messageBox = document.getElementById("roomSearchMessage");
    var resultBox = document.getElementById("roomSearchResult");

    messageBox.innerHTML = "";
    resultBox.innerHTML = "";

    if (checkinDate === "" || checkoutDate === "" || numGuests === "") {
        messageBox.innerHTML = "<div class='alert-error'>All fields are required.</div>";
        resultBox.innerHTML = "<p>No result.</p>";
        return;
    }

    if (checkoutDate <= checkinDate) {
        messageBox.innerHTML = "<div class='alert-error'>Check-out date must be after check-in date.</div>";
        resultBox.innerHTML = "<p>No result.</p>";
        return;
    }

    if (parseInt(numGuests) <= 0) {
        messageBox.innerHTML = "<div class='alert-error'>Number of guests must be greater than 0.</div>";
        resultBox.innerHTML = "<p>No result.</p>";
        return;
    }

    resultBox.innerHTML = "<p>Searching available rooms...</p>";

    var requestData = {
        checkin_date: checkinDate,
        checkout_date: checkoutDate,
        num_guests: numGuests
    };

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
            if (xhttp.status === 200) {
                var response = JSON.parse(xhttp.responseText);

                if (response.status === "success") {
                    showRoomResults(response.rooms, checkinDate, checkoutDate, numGuests);
                } else {
                    messageBox.innerHTML = "<div class='alert-error'>" + response.message + "</div>";
                    resultBox.innerHTML = "<p>No room found.</p>";
                }
            } else {
                messageBox.innerHTML = "<div class='alert-error'>AJAX request failed.</div>";
                resultBox.innerHTML = "<p>No result.</p>";
            }
        }
    };

    xhttp.open("POST", "index.php?route=ajax-search-rooms", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send(JSON.stringify(requestData));
}

function showRoomResults(rooms, checkinDate, checkoutDate, numGuests) {
    var resultBox = document.getElementById("roomSearchResult");

    if (rooms.length === 0) {
        resultBox.innerHTML = "<p>No available room found for the selected dates.</p>";
        return;
    }

    var nights = calculateNightCount(checkinDate, checkoutDate);

    var output = "";

    for (var i = 0; i < rooms.length; i++) {
        var pricePerNight = parseFloat(rooms[i].display_price);
        var totalPrice = pricePerNight * nights;
        totalPrice = totalPrice.toFixed(2);

        output += "<div class='room-card'>";

        output += "<h3>" + rooms[i].name + "</h3>";
        output += "<p><strong>Description:</strong> " + rooms[i].description + "</p>";
        output += "<p><strong>Capacity:</strong> " + rooms[i].max_capacity + " guests</p>";
        output += "<p><strong>Available Rooms:</strong> " + rooms[i].available_rooms + "</p>";

        if (rooms[i].seasonal_label !== "") {
            output += "<p><strong>Seasonal Pricing:</strong> " + rooms[i].seasonal_label + "</p>";
        }

        output += "<p><strong>Price Per Night:</strong> " + pricePerNight + " BDT</p>";
        output += "<p><strong>Total Nights:</strong> " + nights + "</p>";
        output += "<p><strong>Estimated Total Price:</strong> " + totalPrice + " BDT</p>";

        output += "<p><strong>Amenities:</strong> ";

        if (rooms[i].amenities && rooms[i].amenities.length > 0) {
            for (var j = 0; j < rooms[i].amenities.length; j++) {
                output += rooms[i].amenities[j];

                if (j < rooms[i].amenities.length - 1) {
                    output += ", ";
                }
            }
        } else {
            output += "No amenities listed";
        }

        output += "</p>";

        output += "<p><strong>Selected Dates:</strong> " + checkinDate + " to " + checkoutDate + "</p>";
        output += "<p><strong>Guests:</strong> " + numGuests + "</p>";

        var bookingUrl = "index.php?route=guest-book-room" +
            "&room_type_id=" + rooms[i].id +
            "&checkin_date=" + checkinDate +
            "&checkout_date=" + checkoutDate +
            "&num_guests=" + numGuests;
        var detailUrl = "index.php?route=guest-room-type-details" +
    "&room_type_id=" + rooms[i].id +
    "&checkin_date=" + checkinDate +
    "&checkout_date=" + checkoutDate +
    "&num_guests=" + numGuests;

output += "<a class='btn' href='" + detailUrl + "'>View Details</a> ";
output += "<a class='btn' href='" + bookingUrl + "'>Book This Room</a>";

        output += "</div>";
    }

    resultBox.innerHTML = output;
}

function calculateNightCount(checkinDate, checkoutDate) {
    var checkin = new Date(checkinDate);
    var checkout = new Date(checkoutDate);

    var difference = checkout.getTime() - checkin.getTime();
    var nights = difference / (1000 * 60 * 60 * 24);

    return nights;
}