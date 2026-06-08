<?php
// fetch_schedules.php
require_once 'db_connect.php';

// Prepare data mapping [cite: 59]
$stmt = $pdo->prepare("SELECT bus_number, route_from, route_to, departure_time, available_seats, price_tzs FROM buses");
$stmt->execute(); [cite: 60]
$results = $stmt->fetchAll(); [cite: 60]

if (count($results) > 0) {
    foreach ($results as $row) { [cite: 61]
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['bus_number']) . "</td>"; [cite: 31, 61]
        echo "<td>" . htmlspecialchars($row['route_from']) . "</td>"; [cite: 31, 61]
        echo "<td>" . htmlspecialchars($row['route_to']) . "</td>"; [cite: 31, 61]
        echo "<td>" . htmlspecialchars($row['departure_time']) . "</td>"; [cite: 31, 61]
        echo "<td>" . htmlspecialchars($row['available_seats']) . "</td>"; [cite: 31, 61]
        echo "<td>" . htmlspecialchars(number_format($row['price_tzs'], 2)) . " TZS</td>"; [cite: 31, 61]
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No schedules configured yet.</td></tr>";
}
?>