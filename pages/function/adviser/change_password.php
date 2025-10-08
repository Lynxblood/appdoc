<?php
// Set the content type to JSON
header('Content-Type: application/json');

// 1. Include Database Connection
require '../../../config/dbcon.php'; 

// Function to handle JSON response
function sendResponse($success, $message, $data = []) {
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit();
}

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request method.");
}

// 2. Validate and Sanitize Input
$user_id = $_POST['user_id'] ?? 0;
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_new_password = $_POST['confirm_new_password'] ?? '';

// Basic input checks
if (empty($user_id) || empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
    sendResponse(false, "All fields are required.");
}

if ($new_password !== $confirm_new_password) {
    sendResponse(false, "New passwords do not match.");
}

// Add a minimum length check for the new password
if (strlen($new_password) < 8) {
    sendResponse(false, "New password must be at least 8 characters long.");
}

$user_id = (int)$user_id; // Cast to integer for security

// Check if the user is authorized to change the password for this ID
if ($user_id !== (int)$_SESSION['user_id']) {
    // This prevents one user from attempting to change another user's password
    sendResponse(false, "Authorization error. Cannot perform action.");
}

try {
    // 3. Fetch current password hash from the database
    // Assumes the user table is named 'users' and the password column is 'password'
    $sql_fetch = "SELECT password_hash FROM users WHERE user_id = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $user_id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();

    if ($result->num_rows === 0) {
        $stmt_fetch->close();
        sendResponse(false, "User not found.");
    }

    $user = $result->fetch_assoc();
    $hashed_password = $user['password_hash'];
    $stmt_fetch->close();

    // 4. Verify the current password
    if (!password_verify($current_password, $hashed_password)) {
        sendResponse(false, "The current password you entered is incorrect.");
    }

    // 5. Hash the new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // 6. Update the password in the database
    $sql_update = "UPDATE users SET password_hash = ? WHERE user_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $new_hashed_password, $user_id);

    if ($stmt_update->execute()) {
        $stmt_update->close();
        // Successful update
        sendResponse(true, "Password changed successfully! Please login with your new password next time.");
    } else {
        $stmt_update->close();
        sendResponse(false, "Database error: Failed to update password.");
    }

} catch (Exception $e) {
    sendResponse(false, "An unexpected server error occurred: " . $e->getMessage());
}

?>