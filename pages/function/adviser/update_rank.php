<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

// Check for required POST data
if (!isset($_POST['user_id']) || !isset($_POST['rank_id'])) {
    $response["message"] = "Required data is missing.";
    echo json_encode($response);
    exit;
}

// Get and sanitize input
$user_id = intval($_POST['user_id']);
$rank_id = intval($_POST['rank_id']);

// Validate user role to ensure only an adviser can perform this action
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'adviser') {
    $response["message"] = "Unauthorized action.";
    echo json_encode($response);
    exit;
}

// Prepare and execute the update query
$stmt = $conn->prepare("UPDATE users SET rank_id = ? WHERE user_id = ?");
$stmt->bind_param("ii", $rank_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response["success"] = true;
        $response["message"] = "Rank updated successfully.";
    } else {
        $response["message"] = "Rank was already assigned or user not found.";
    }
} else {
    $response["message"] = "Database error: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);