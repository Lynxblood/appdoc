<?php
session_start();
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred.", 
    "comments" => []
];

if (!isset($_SESSION['user_id'])) {
    $response["message"] = "Authentication failed.";
    echo json_encode($response);
    exit;
}

if (!isset($_POST['document_id']) || !isset($_POST['comment_text'])) {
    $response["message"] = "Required data is missing.";
    echo json_encode($response);
    exit;
}

$document_id = intval($_POST['document_id']);
$user_id = $_SESSION['user_id'];
$comment_text = $_POST['comment_text'];

if (empty(trim($comment_text))) {
    $response["message"] = "Comment cannot be empty.";
    echo json_encode($response);
    exit;
}

// First, get the document owner's ID
$stmt_owner = $conn->prepare("SELECT user_id FROM documents WHERE document_id = ?");
$stmt_owner->bind_param("i", $document_id);
$stmt_owner->execute();
$document_owner_id = $stmt_owner->get_result()->fetch_assoc()['user_id'];
$stmt_owner->close();

if (!$document_owner_id) {
    $response["message"] = "Document not found.";
    echo json_encode($response);
    exit;
}

// Insert the new comment
$stmt = $conn->prepare("INSERT INTO comments (document_id, user_id, comment_text) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $document_id, $user_id, $comment_text);

if ($stmt->execute()) {
    $stmt->close();

    // === NEW: Add Notification Logic ===
    $commenter_name = $_SESSION['first_name'];
    $message = "A new comment has been added to your document by " . htmlspecialchars($commenter_name) . ".";

    $sql_notification = "INSERT INTO notifications (user_id, document_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
    $stmt_notification = $conn->prepare($sql_notification);
    $stmt_notification->bind_param("iis", $document_owner_id, $document_id, $message);
    $stmt_notification->execute();
    $stmt_notification->close();
    
    $response["success"] = true;
    $response["message"] = "Comment added successfully.";

    // Fetch comments for the document
    $stmt = $conn->prepare("SELECT comments.comment_id, comments.comment_text, comments.user_id, users.first_name, users.last_name, comments.created_at
                        FROM comments
                        JOIN users ON comments.user_id = users.user_id
                        WHERE comments.document_id = ?");
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    $stmt->close();

    $response["comments"] = $comments;
    $response["current_user_id"] = $user_id;
    

} else {
    $response["message"] = "Failed to add comment: " . $stmt->error;
}

echo json_encode($response);
$conn->close();
?>