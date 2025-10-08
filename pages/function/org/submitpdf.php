<?php
include '../../../config/dbcon.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$id     = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'draft';
$userId = $_SESSION['user_id'];

// Get organization_id from the user's session
$stmt_org = $conn->prepare("SELECT organization_id FROM users WHERE user_id = ?");
$stmt_org->bind_param("i", $userId);
$stmt_org->execute();
$result_org = $stmt_org->get_result();
$organization_id = $result_org->fetch_assoc()['organization_id'];
$stmt_org->close();


// Simple update query
if ($id > 0 && $status !== '') {
    $sql = "UPDATE documents SET status = ? WHERE document_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
    
        // If the document is submitted, create a history record and notification
        if ($status == 'submitted') {
            // Log the history
            $sql_history = "INSERT INTO document_history (document_id, from_status, to_status, modified_by_user_id, timestamp) VALUES (?, ?, ?, ?, NOW())";
            $stmt_history = $conn->prepare($sql_history);
            $from_status = 'draft';
            $stmt_history->bind_param("issi", $id, $from_status, $status, $userId);
            $stmt_history->execute();
            $stmt_history->close();
    
            // Notify the adviser
            $sql_adviser = "SELECT user_id FROM users WHERE user_role = 'adviser' AND organization_id = ?";
            $stmt_adviser = $conn->prepare($sql_adviser);
            $stmt_adviser->bind_param("i", $organization_id);
            $stmt_adviser->execute();
            $adviser_id = $stmt_adviser->get_result()->fetch_assoc()['user_id'];
            $stmt_adviser->close();
    
            if ($adviser_id) {
                $message = "A new document has been submitted for your approval.";
                $sql_notification = "INSERT INTO notifications (user_id, document_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
                $stmt_notification = $conn->prepare($sql_notification);
                $stmt_notification->bind_param("iis", $adviser_id, $id, $message);
                $stmt_notification->execute();
                $stmt_notification->close();
            }
        }
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
