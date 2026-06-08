<?php
// process_booking.php
header('Content-Type: application/json'); // Set headers for seamless JSON decoding inside jQuery [cite: 330]
require_once 'db_connect.php';

$response = ["status" => "error", "message" => "Invalid Request Method Context."];

if ($_SERVER["REQUEST_METHOD"] == "POST") { [cite: 29]

    // 1. Capture and Sanitize values defensively [cite: 29, 31]
    $bus_id          = isset($_POST['bus_id']) ? trim($_POST['bus_id']) : ""; [cite: 29, 30]
    $passenger_name  = isset($_POST['passenger_name']) ? trim($_POST['passenger_name']) : ""; [cite: 29, 30]
    $passenger_phone = isset($_POST['passenger_phone']) ? trim($_POST['passenger_phone']) : ""; [cite: 29, 30]

    // 2. Deep Server-Side Validation (Never trust client-side scripts explicitly!)
    if (empty($bus_id) || empty($passenger_name) || empty($passenger_phone)) { [cite: 30]
        $response["message"] = "All connection parameters must be satisfied.";
        echo json_encode($response);
        exit;
    }

    // Apply strict HTML sanitization before database assignment to circumvent potential Cross-Site Scripting (XSS) [cite: 31]
    $passenger_name  = htmlspecialchars($passenger_name); [cite: 31]
    $passenger_phone = htmlspecialchars($passenger_phone); [cite: 31]

    try {
        // Begin Transaction to combine updates safely
        $pdo->beginTransaction();

        // 3. Check for seat availability in real time using a prepared statement [cite: 54, 59]
        $checkSql = "SELECT available_seats, total_seats FROM buses WHERE id = ? FOR UPDATE";
        $stmt = $pdo->prepare($checkSql); [cite: 59]
        $stmt->execute([$bus_id]); [cite: 60]
        $bus = $stmt->fetch(); [cite: 60]

        if (!$bus) {
            $response["message"] = "The requested route does not exist.";
            echo json_encode($response);
            $pdo->rollBack();
            exit;
        }

        if ($bus['available_seats'] <= 0) {
            $response["message"] = "Booking failed! Seats are entirely filled on this trip schedule.";
            echo json_encode($response);
            $pdo->rollBack();
            exit;
        }

        // Calculate current allocated assignment slot row number
        $assigned_seat = $bus['total_seats'] - $bus['available_seats'] + 1;

        // 4. Perform insertion operation via Prepared Statements template to eliminate SQLi injections [cite: 54, 56]
        $insertSql = "INSERT INTO tickets (bus_id, passenger_name, passenger_phone, booked_seat_no) 
                      VALUES (:bus_id, :name, :phone, :seat)";
        $insertStmt = $pdo->prepare($insertSql); [cite: 56]
        $insertStmt->execute([ [cite: 57]
            ':bus_id' => $bus_id,
            ':name'   => $passenger_name,
            ':phone'  => $passenger_phone,
            ':seat'   => $assigned_seat
        ]); [cite: 57]

        // 5. Update remaining available seats using target criteria conditional clauses [cite: 62, 63]
        $updateSql = "UPDATE buses SET available_seats = available_seats - 1 WHERE id = :bus_id"; [cite: 63]
        $updateStmt = $pdo->prepare($updateSql); [cite: 64]
        $updateStmt->execute([':bus_id' => $bus_id]); [cite: 64]

        // All steps successfully run without exceptions; commit records safely to storage
        $pdo->commit();

        $response["status"] = "success";
        $response["message"] = "Ticket successfully compiled! Seat assigned: " . $assigned_seat;

    } catch (\Exception $e) {
        // Undo changes if anything fails within the execution block
        $pdo->rollBack();
        $response["message"] = "Transactional engine failure occurred safely.";
    }
}

// Return state payload
echo json_encode($response);
?>