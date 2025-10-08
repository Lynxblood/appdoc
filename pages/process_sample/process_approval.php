<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}
include '../config/dbcon.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request method.");
}

$user_id = $_SESSION['user_id'];
$document_id = $_POST['document_id'];
$action = $_POST['action'];
$reason = $_POST['reason'] ?? null;
$user_role = $_SESSION['user_role'];

// Get current document status for history log
$stmt = $conn->prepare("SELECT status FROM documents WHERE document_id = ?");
$stmt->bind_param("i", $document_id);
$stmt->execute();
$current_status = $stmt->get_result()->fetch_assoc()['status'];
$stmt->close();

if (!$current_status) {
    die("Document not found.");
}

// Validation and security checks
// Ensure the user has the correct role for this action
$valid_action = false;
switch ($user_role) {
    case 'adviser':
        if ($current_status == 'submitted' && in_array($action, ['pending', 'reject', 'revision'])) {
            $valid_action = true;
        }
        break;
    case 'dean':
        if ($current_status == 'pending' && in_array($action, ['endorsed', 'reject', 'revision'])) {
            $valid_action = true;
        }
        break;
    case 'fssc':
        if ($current_status == 'endorsed' && in_array($action, ['approved', 'reject', 'revision'])) {
            $valid_action = true;
        }
        break;
}

if (!$valid_action) {
    die("You do not have permission to perform this action or the document status is incorrect.");
}

// Update the document status
$sql_update = "UPDATE documents SET status = ?, updated_at = NOW() WHERE document_id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $action, $document_id);
$stmt_update->execute();
$stmt_update->close();

// Log the status change in history
$sql_history = "INSERT INTO document_history (document_id, from_status, to_status, modified_by_user_id, reason, timestamp) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt_history = $conn->prepare($sql_history);
$stmt_history->bind_param("issis", $document_id, $current_status, $action, $user_id, $reason);
$stmt_history->execute();
$stmt_history->close();

// Send a notification to the next user or the organization
$recipient_id = null;
$message = "";

if ($action == 'approved' || $action == 'endorsed' || $action == 'pending') {
    // Determine the next approver to notify
    $next_role = '';
    if ($action == 'pending') {
        $next_role = 'dean';
    } else if ($action == 'endorsed') {
        $next_role = 'fssc';
    }

    if (!empty($next_role)) {
        // Find the next user to notify based on their role
        $sql_next_user = "SELECT user_id FROM users WHERE user_role = ?";
        $stmt_next_user = $conn->prepare($sql_next_user);
        $stmt_next_user->bind_param("s", $next_role);
        $stmt_next_user->execute();
        $result_next_user = $stmt_next_user->get_result();
        if ($result_next_user->num_rows > 0) {
            $recipient_id = $result_next_user->fetch_assoc()['user_id'];
            $message = "A document needs your approval. Document ID: " . $document_id;
        }
        $stmt_next_user->close();
    }
} else if ($action == 'reject' || $action == 'revision') {
    // Notify the original organization
    $sql_org_user = "SELECT u.user_id FROM users u JOIN documents d ON u.organization_id = d.organization_id WHERE d.document_id = ? AND u.user_role IN ('academic_organization', 'non_academic_organization')";
    $stmt_org_user = $conn->prepare($sql_org_user);
    $stmt_org_user->bind_param("i", $document_id);
    $stmt_org_user->execute();
    $recipient_id = $stmt_org_user->get_result()->fetch_assoc()['user_id'];
    $stmt_org_user->close();

    $message = "Your document has been " . $action . " by the " . $user_role . ". Reason: " . htmlspecialchars($reason);
}

if ($recipient_id) {
    $sql_notification = "INSERT INTO notifications (user_id, document_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
    $stmt_notification = $conn->prepare($sql_notification);
    $stmt_notification->bind_param("iis", $recipient_id, $document_id, $message);
    $stmt_notification->execute();
    $stmt_notification->close();
}

// Redirect back to the approver dashboard
header("Location: approver_dashboard.php");
exit();
?>