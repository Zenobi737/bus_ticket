<?php
// Include database connection to seed choice dropdown elements initially
require_once 'db_connect.php';

// Fetch active bus trips to supply into the booking form
$stmt = $pdo->query("SELECT id, bus_number, route_from, route_to, price_tzs FROM buses WHERE available_seats > 0");
$buses = $stmt->fetchAll();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mzumbe Bus Booking System</title>
    <style type="text/css">
        body { font-family: Arial, Helvetica, sans-serif; font-size: 16px; margin: 30px; background-color: #f9f9f9; } /* Font Stack fallback example */
        h1, h2 { color: rgb(0, 102, 51); } /* Mzumbe Brand Colors */
        .container { width: 85%; margin: 0 auto; background: #fff; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], select { width: 100%; padding: 8px; box-sizing: border-box; }
        input[type="submit"] { background-color: rgb(0, 102, 51); color: #fff; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; }
        input[type="submit"]:hover { background-color: #FFCC00; color: #000; } /* Mzumbe Gold hover accent */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success-msg { color: green; font-weight: bold; }
        .error-msg { color: red; font-weight: bold; }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" type="text/javascript"></script>
</head>
<body>

<div class="container">
    <h1>Mzumbe University Bus Transport Portal</h1>
    
    <h2>Available Bus Schedules</h2>
    <button id="refreshBtn">Refresh Schedule</button>
    <table>
        <thead>
            <tr>
                <th>Bus No.</th>
                <th>From</th>
                <th>To</th>
                <th>Departure</th>
                <th>Available Seats</th>
                <th>Price (TZS)</th>
            </tr>
        </thead>
        <tbody id="scheduleTableBody">
            </tbody>
    </table>

    <hr style="margin: 40px 0;" />

    <h2>Book Your Ticket</h2>
    <div id="formFeedback"></div>
    
    [cite_start]<form id="ticketForm" action="process_booking.php" method="post"> [cite: 28]
        <div class="form-group">
            <label for="bus_id">Select Route:</label>
            <select name="bus_id" id="bus_id">
                <option value="">-- Choose a Bus Route --</option>
                [cite_start]<?php foreach ($buses as $bus): ?> [cite: 18]
                    <option value="<?php echo $bus['id']; ?>">
                        <?php echo htmlspecialchars($bus['bus_number'] . " (" . $bus['route_from'] . " to " . $bus['route_to'] . ") - " . $bus['price_tzs'] . " TZS"); [cite_start]?> [cite: 31]
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="passenger_name">Full Name:</label>
            <input type="text" name="passenger_name" id="passenger_name" />
        </div>
        
        <div class="form-group">
            <label for="passenger_phone">Phone Number:</label>
            <input type="text" name="passenger_phone" id="passenger_phone" />
        </div>
        
        <div class="form-group">
            <input type="submit" value="Confirm Booking" id="submitBtn" />
        </div>
    </form>
</div>

<script type="text/javascript">
// Ensure elements are fully loaded before interaction binding
[cite_start]$(document).ready(function() { [cite: 316, 317]

    // 1. Function to fetch live schedules via AJAX $.get() 
    function loadSchedules() {
        $.get("fetch_schedules.php", function(data, status) { [cite: 326]
            $("#scheduleTableBody").html(data); [cite: 326]
        }).fail(function() {
            alert("Failed to load live schedules."); [cite: 326]
        });
    }

    // Initial load on page view
    loadSchedules();

    // Re-load on manual refresh button trigger
    $("#refreshBtn").click(function() { [cite: 321]
        loadSchedules();
    });

    // 2. Handle Asynchronous Form Submission via AJAX $.post()
    $("#ticketForm").submit(function(event) { [cite: 328]
        event.preventDefault(); // Stop traditional page refresh/flickering [cite: 267, 328]
        
        // Client-side Validation (Behavioral Layer check)
        let name = $("#passenger_name").val();
        let phone = $("#passenger_phone").val();
        let selectedBus = $("#bus_id").val();
        
        if(name === "" || phone === "" || selectedBus === "") {
            $("#formFeedback").html("<p class='error-msg'>All fields are required before booking.</p>");
            return false;
        }

        // Capture elements safely using serialize() 
        let formData = $(this).serialize(); [cite: 328]

        // Dispatch background POST request 
        $.post("process_booking.php", formData, function(response) { [cite: 328]
            if (response.status === "success") {
                $("#formFeedback").html("<p class='success-msg'>" + response.message + "</p>");
                // Reset form input values upon successful operation
                $("#ticketForm")[0].reset();
                // Refresh data states automatically
                loadSchedules();
            } else {
                $("#formFeedback").html("<p class='error-msg'>" + response.message + "</p>");
            }
        }, "json").fail(function() { [cite: 330]
            $("#formFeedback").html("<p class='error-msg'>An error occurred on the back-end engine.</p>");
        });
    });
});
</script>

</body>
</html>