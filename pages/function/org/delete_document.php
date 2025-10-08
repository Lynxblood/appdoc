<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

// Check if the user is authenticated and has the required permissions
if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
    $response["message"] = "Authentication failed or required data is missing.";
    echo json_encode($response);
    exit;
}

$document_id = intval($_POST['id']);
$user_id = $_SESSION['user_id'];

// Prepare and execute the query to delete the document
// IMPORTANT: Add a WHERE clause to ensure only the owner can delete their document
$stmt = $conn->prepare("DELETE FROM documents WHERE document_id = ? AND user_id = ?");
$stmt->bind_param("ii", $document_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response["success"] = true;
        $response["message"] = "Document deleted successfully.";
    } else {
        $response["success"] = false;
        $response["message"] = "Document not found or you do not have permission to delete it.";
    }
} else {
    $response["message"] = "Deletion failed: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>