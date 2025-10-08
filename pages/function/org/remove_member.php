<?php
// This file handles removing a user from an organization by setting their organization_id to NULL.
require '../../../config/dbcon.php'; // Use your existing DB connection
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id'];
    
    if ($user_id > 0) {
        // Query to set the user's organization_id to NULL
        // We use the 'users' table columns from your absorbed schema
        $stmt = $conn->prepare("UPDATE users SET organization_id = NULL WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Member successfully removed from the organization.';
            } else {
                $response['message'] = 'Member was already removed or user ID not found.';
            }
        } else {
            $response['message'] = 'Database error: ' . $conn->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'User ID is missing.';
    }
}

echo json_encode($response);
?>