function loadHousekeepingRoomStatus() {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
            if (xhttp.status === 200) {
                var rooms = JSON.parse(xhttp.responseText);

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

                for (var i = 0; i < rooms.length; i++) {
                    output += "<tr>";
                    output += "<td>" + rooms[i].id + "</td>";
                    output += "<td>" + rooms[i].room_number + "</td>";
                    output += "<td>" + rooms[i].floor + "</td>";
                    output += "<td>" + rooms[i].room_type_name + "</td>";
                    output += "<td><span class='status-badge'>" + rooms[i].status + "</span></td>";

                    if (rooms[i].notes === null) {
                        output += "<td></td>";
                    } else {
                        output += "<td>" + rooms[i].notes + "</td>";
                    }

                    output += "</tr>";
                }

                output += "</table>";

                document.getElementById("housekeepingRoomStatusResult").innerHTML = output;
            } else {
                document.getElementById("housekeepingRoomStatusResult").innerHTML = "<p>AJAX request failed.</p>";
            }
        }
    };

    xhttp.open("GET", "index.php?route=ajax-housekeeping-room-status", true);
    xhttp.send();
}
