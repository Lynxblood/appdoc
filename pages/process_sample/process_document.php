<?php
include '../config/dbcon.php';

// Basic validation
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id'])) {
    die("Invalid request.");
}

$user_id = $_SESSION['user_id'];
$title = trim($_POST['title']);
$document_type = $_POST['document_type'];
$content_html = $_POST['content_html'];
$action = $_POST['action'];

if (empty($title) || empty($document_type) || empty($content_html)) {
    die("Please fill out all required fields.");
}

$status = ($action == 'submit') ? 'submitted' : 'draft';

// Get organization_id from the user's session
$stmt_org = $conn->prepare("SELECT organization_id FROM users WHERE user_id = ?");
$stmt_org->bind_param("i", $user_id);
$stmt_org->execute();
$result_org = $stmt_org->get_result();
$organization_id = $result_org->fetch_assoc()['organization_id'];
$stmt_org->close();

// Insert the new document
$sql = "INSERT INTO documents (title, document_type, organization_id, status, user_id, content_html, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisis", $title, $document_type, $organization_id, $status, $user_id,$content_html);

if ($stmt->execute()) {
    $document_id = $conn->insert_id;
    echo "Document created successfully!";

    // If the document is submitted, create a history record and notification
    if ($status == 'submitted') {
        // Log the history
        $sql_history = "INSERT INTO document_history (document_id, from_status, to_status, modified_by_user_id, timestamp) VALUES (?, ?, ?, ?, NOW())";
        $stmt_history = $conn->prepare($sql_history);
        $from_status = 'draft';
        $stmt_history->bind_param("issi", $document_id, $from_status, $status, $user_id);
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
            $message = "A new document '" . htmlspecialchars($title) . "' has been submitted for your approval.";
            $sql_notification = "INSERT INTO notifications (user_id, document_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
            $stmt_notification = $conn->prepare($sql_notification);
            $stmt_notification->bind_param("iis", $adviser_id, $document_id, $message);
            $stmt_notification->execute();
            $stmt_notification->close();
        }
    }

    header("Location: org_dashboard.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>