<?php
include '../../../config/dbcon.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$id     = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'draft';
$userId = $_SESSION['user_id'];

// --- Get organization_id ---
$stmt_org = $conn->prepare("SELECT organization_id FROM users WHERE user_id = ?");
$stmt_org->bind_param("i", $userId);
$stmt_org->execute();
$result_org = $stmt_org->get_result();
$organization_id = $result_org->fetch_assoc()['organization_id'];
$stmt_org->close();

// --- Get user full name for activity log ---
$user_name = "Unknown User";
$stmt_user = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM users WHERE user_id = ?");
$stmt_user->bind_param("i", $userId);
$stmt_user->execute();
$stmt_user->bind_result($user_name);
$stmt_user->fetch();
$stmt_user->close();

// --- Get document filename (for logging) ---
$filename = "Untitled Document";
$stmt_doc = $conn->prepare("SELECT pdf_filename FROM documents WHERE document_id = ?");
$stmt_doc->bind_param("i", $id);
$stmt_doc->execute();
$stmt_doc->bind_result($filename);
$stmt_doc->fetch();
$stmt_doc->close();

// --- Update document status ---
if ($id > 0 && $status !== '') {
    $sql = "UPDATE documents SET status = ? WHERE document_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
    
        // If the document is submitted, record history, notify adviser, and log activity
        if ($status == 'submitted') {
            // --- Insert into document_history ---
            $sql_history = "INSERT INTO document_history (document_id, from_status, to_status, modified_by_user_id, timestamp) 
                            VALUES (?, ?, ?, ?, NOW())";
            $stmt_history = $conn->prepare($sql_history);
            $from_status = 'draft';
            $stmt_history->bind_param("issi", $id, $from_status, $status, $userId);
            $stmt_history->execute();
            $stmt_history->close();

            // --- Notify adviser ---
            $sql_adviser = "SELECT user_id FROM users WHERE user_role = 'adviser' AND organization_id = ?";
            $stmt_adviser = $conn->prepare($sql_adviser);
            $stmt_adviser->bind_param("i", $organization_id);
            $stmt_adviser->execute();
            $adviser_result = $stmt_adviser->get_result();
            $adviser = $adviser_result->fetch_assoc();
            $adviser_id = $adviser['user_id'] ?? null;
            $stmt_adviser->close();

            if ($adviser_id) {
                $message = "A new document has been submitted for your approval.";
                $sql_notification = "INSERT INTO notifications (user_id, document_id, message, is_read, created_at) 
                                     VALUES (?, ?, ?, 0, NOW())";
                $stmt_notification = $conn->prepare($sql_notification);
                $stmt_notification->bind_param("iis", $adviser_id, $id, $message);
                $stmt_notification->execute();
                $stmt_notification->close();
            }

            // --- Log to org_recent_activities ---
            $activity_type = "Document Submitted";
            $description = "{$user_name} submitted document '{$filename}' for review.";
            $stmt_activity = $conn->prepare("
                INSERT INTO org_recent_activities 
                (organization_id, user_id, document_id, activity_type, description, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt_activity->bind_param("iiiss", $organization_id, $userId, $id, $activity_type, $description);
            $stmt_activity->execute();
            $stmt_activity->close();
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
?>
