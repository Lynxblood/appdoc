<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
    $response["message"] = "Authentication failed or required data is missing.";
    echo json_encode($response);
    exit;
}

$document_id = intval($_POST['id']);
$user_id = $_SESSION['user_id'];

// Fetch user info for logging
$stmt_user = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) AS full_name, organization_id FROM users WHERE user_id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_info = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

$full_name = $user_info['full_name'];
$organization_id = $user_info['organization_id'];

// Fetch document filename before updating
$stmt_doc = $conn->prepare("SELECT pdf_filename FROM documents WHERE document_id = ? AND user_id = ?");
$stmt_doc->bind_param("ii", $document_id, $user_id);
$stmt_doc->execute();
$doc_result = $stmt_doc->get_result();
$document = $doc_result->fetch_assoc();
$stmt_doc->close();

if (!$document) {
    $response["message"] = "Document not found or you do not have permission to delete it.";
    echo json_encode($response);
    exit;
}

$filename = $document['pdf_filename'];

// Update document as archived (soft delete)
$stmt = $conn->prepare("UPDATE documents SET is_archived = 1 WHERE document_id = ? AND user_id = ?");
$stmt->bind_param("ii", $document_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        // Log organization activity
        $description = "$full_name deleted the document '$filename'.";
        $sql_activity = "INSERT INTO org_recent_activities (organization_id, user_id, document_id, activity_type, description) 
                         VALUES (?, ?, ?, 'Document Deleted', ?)";
        $stmt_activity = $conn->prepare($sql_activity);
        $stmt_activity->bind_param("iiis", $organization_id, $user_id, $document_id, $description);
        $stmt_activity->execute();
        $stmt_activity->close();

        $response["success"] = true;
        $response["message"] = "Document deleted successfully.";
    } else {
        $response["success"] = false;
        $response["message"] = "No document was updated.";
    }
} else {
    $response["message"] = "Archiving failed: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
