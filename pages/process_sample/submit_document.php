<?php
session_start();
include '../config/dbcon.php';

// Check if the user is logged in and is an organization user
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['academic_organization', 'non_academic_organization'])) {
    die("Unauthorized access.");
}

// Check if a document ID is provided in the URL
$document_id = $_GET['id'] ?? 0;
if ($document_id == 0) {
    die("Document ID not provided.");
}

$user_id = $_SESSION['user_id'];

// --- Step 1: Check if the document exists and belongs to the user's organization ---
$stmt = $conn->prepare("SELECT status, organization_id FROM documents WHERE document_id = ?");
$stmt->bind_param("i", $document_id);
$stmt->execute();
$result = $stmt->get_result();
$document = $result->fetch_assoc();
$stmt->close();

if (!$document || $document['organization_id'] != $_SESSION['organization_id']) {
    die("Document not found or you do not have permission to submit it.");
}

// --- Step 2: Check if the document is in 'draft' status ---
if ($document['status'] != 'draft') {
    die("This document is already submitted or has a different status.");
}

// --- Step 3: Update the document status to 'submitted' ---
$new_status = 'submitted';
$sql_update = "UPDATE documents SET status = ?, updated_at = NOW() WHERE document_id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $new_status, $document_id);

if (!$stmt_update->execute()) {
    echo "Error updating document status: " . $stmt_update->error;
    $stmt_update->close();
    $conn->close();
    exit();
}
$stmt_update->close();

// --- Step 4: Log the status change in the document_history table ---
$from_status = 'draft';
$sql_history = "INSERT INTO document_history (document_id, from_status, to_status, modified_by_user_id, reason, timestamp) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt_history = $conn->prepare($sql_history);
$reason = "Document submitted for approval.";
$stmt_history->bind_param("issis", $document_id, $from_status, $new_status, $user_id, $reason);
$stmt_history->execute();
$stmt_history->close();

// --- Step 5: Send a notification to the assigned adviser ---
// Find the adviser for this organization
$adviser_id = null;
$sql_adviser = "SELECT user_id FROM users WHERE user_role = 'adviser' AND organization_id = ?";
$stmt_adviser = $conn->prepare($sql_adviser);
$stmt_adviser->bind_param("i", $document['organization_id']);
$stmt_adviser->execute();
$result_adviser = $stmt_adviser->get_result();
if ($result_adviser->num_rows > 0) {
    $adviser_id = $result_adviser->fetch_assoc()['user_id'];
}
$stmt_adviser->close();

if ($adviser_id) {
    $message = "A new document has been submitted for your approval. Document ID: " . $document_id;
    $sql_notification = "INSERT INTO notifications (user_id, document_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
    $stmt_notification = $conn->prepare($sql_notification);
    $stmt_notification->bind_param("iis", $adviser_id, $document_id, $message);
    $stmt_notification->execute();
    $stmt_notification->close();
} else {
    // Optional: Log or display a message if no adviser is found for the organization
    // This could be a setup issue that needs attention
}

$conn->close();

// --- Step 6: Redirect back to the organization's dashboard with a success message ---
header("Location: org_dashboard.php?status=submitted");
exit();
?>