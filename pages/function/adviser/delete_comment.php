<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

// Check for authentication and required data
if (!isset($_SESSION['user_id']) || !isset($_POST['comment_id'])) {
    $response["message"] = "Authentication failed or required data is missing.";
    echo json_encode($response);
    exit;
}

$comment_id = intval($_POST['comment_id']);
$user_id = $_SESSION['user_id'];

// Prepare and execute the query to delete the comment
// The critical part is the WHERE clause which ensures the comment belongs to the user
$stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ? AND user_id = ?");
$stmt->bind_param("ii", $comment_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response["success"] = true;
        $response["message"] = "Comment deleted successfully.";
    } else {
        $response["success"] = false;
        $response["message"] = "Comment not found or you do not have permission to delete it.";
    }
} else {
    $response["message"] = "Deletion failed: " . $stmt->error;
}

$stmt->close();
echo json_encode($response);
$conn->close();
?>