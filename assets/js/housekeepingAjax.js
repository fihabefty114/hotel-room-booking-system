function loadAdminRoomStatus() {
    var resultBox = document.getElementById("adminRoomStatusResult");

    if (!resultBox) {
        return;
    }

    resultBox.innerHTML = "<p>Loading room status...</p>";

    var request = new XMLHttpRequest();

    request.open("GET", "index.php?route=ajax-admin-room-status", true);

    request.onload = function () {
        if (request.status === 200) {
            try {
                var rooms = JSON.parse(request.responseText);
                showAdminRoomStatus(rooms, resultBox);
            } catch (error) {
                resultBox.innerHTML = "<p>Invalid JSON response.</p>";
            }
        } else {
            resultBox.innerHTML = "<p>AJAX request failed.</p>";
        }
    };

    request.onerror = function () {
        resultBox.innerHTML = "<p>Network error occurred.</p>";
    };

    request.send();
}

function showAdminRoomStatus(rooms, resultBox) {
    if (!Array.isArray(rooms) || rooms.length === 0) {
        resultBox.innerHTML = "<p>No room status found.</p>";
        return;
    }

    var output = "";

    output += "<table class='data-table'>";
    output += "<tr>";
    output += "<th>Room ID</th>";
    output += "<th>Room Number</th>";
    output += "<th>Floor</th>";
    output += "<th>Room Type</th>";
    output += "<th>Status</th>";
    output += "<th>Notes</th>";
    output += "</tr>";

    rooms.forEach(function (room) {
        output += "<tr>";
        output += "<td>" + room.id + "</td>";
        output += "<td>" + room.room_number + "</td>";
        output += "<td>" + room.floor + "</td>";
        output += "<td>" + room.room_type_name + "</td>";
        output += "<td><span class='status-badge'>" + room.status + "</span></td>";
        output += "<td>" + room.notes + "</td>";
        output += "</tr>";
    });

    output += "</table>";

    resultBox.innerHTML = output;
}