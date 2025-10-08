<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

// Check for required POST data
if (!isset($_POST['content_html'])) {
    $response["message"] = "Required data is missing.";
    echo json_encode($response);
    exit;
}

$content_html = $_POST['content_html'];
$docId = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : null;

if (!isset($_SESSION['user_id'])) {
    $response["message"] = "Authentication required.";
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'] ?? "Someone";

// Determine if it's an update
if ($docId) {
    // Get current content for history
    $stmt_old = $conn->prepare("SELECT user_id, content_html FROM documents WHERE document_id = ?");
    $stmt_old->bind_param("i", $docId);
    $stmt_old->execute();
    $old_content_result = $stmt_old->get_result();
    $doc_row = $old_content_result->fetch_assoc();
    $stmt_old->close();

    if (!$doc_row) {
        $response["message"] = "Document not found.";
        echo json_encode($response);
        exit;
    }

    $document_owner_id = $doc_row['user_id'];
    $old_content_html = $doc_row['content_html'] ?? '';

    // Update document
    $stmt = $conn->prepare("UPDATE documents SET content_html = ?, status = 'revision', updated_at = NOW() WHERE document_id = ?");
    $stmt->bind_param("si", $content_html, $docId);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Document updated successfully!";

        // Save history
        $reason = "Commented for revision.";
        $stmt_history = $conn->prepare("INSERT INTO document_history (document_id, from_status, to_status, reason, modified_by_user_id, old_content_html, timestamp) VALUES (?, 'submitted', 'revision', ?, ?, ?, NOW())");
        $stmt_history->bind_param("isis", $docId, $reason, $user_id, $old_content_html);
        $stmt_history->execute();
        $stmt_history->close();

        // === NEW: Insert notification for document owner ===
        if ($document_owner_id && $document_owner_id != $user_id) {
            $notif_message = "$first_name updated your document and marked it for revision.";
            $sql_notification = "INSERT INTO notifications (user_id, document_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
            $stmt_notif = $conn->prepare($sql_notification);
            $stmt_notif->bind_param("iis", $document_owner_id, $docId, $notif_message);
            $stmt_notif->execute();
            $stmt_notif->close();
        }

    } else {
        $response["message"] = "Failed to update document: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
exit;
