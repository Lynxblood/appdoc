<?php
require '../../../config/dbcon.php'; // Adjust the path as needed
// Ensure session is started for access to $_SESSION['organization_id']
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
// ob_clean() is used to prevent any pre-output from dbcon.php or other includes
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

// --- Security: Use organization_id from SESSION, not POST ---
if (empty($_SESSION['organization_id'])) {
    $response["message"] = "Organization ID not found in session. Unauthorized request.";
    echo json_encode($response);
    exit;
}

$organization_id = intval($_SESSION['organization_id']);
$file = $_FILES['logoFile'] ?? null;
// -----------------------------------------------------------

// Check for a valid file upload
if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    $response["message"] = "File upload error or file missing.";
    if ($file) {
        $response["message"] .= " Error code: " . $file['error'];
    }
    echo json_encode($response);
    exit;
}

// Check file type (accepts common image formats)
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowed_types)) {
    $response["message"] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
    echo json_encode($response);
    exit;
}

// Define the upload directory
$upload_dir = '../../../img/logo/'; // Adjust the path as needed
if (!is_dir($upload_dir)) {
    // Attempt to create directory if it doesn't exist
    if (!mkdir($upload_dir, 0777, true)) {
        $response["message"] = "Failed to create upload directory.";
        echo json_encode($response);
        exit;
    }
}

// Generate a unique filename to prevent overwrites, using the organization ID as a prefix
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = $organization_id . '_' . uniqid() . '.' . $extension;
$destination_path = $upload_dir . $filename;

if (move_uploaded_file($file['tmp_name'], $destination_path)) {
    // File was uploaded successfully, now update the database
    $logo_url = 'img/logo/' . $filename; // Relative URL for database storage
    $stmt = $conn->prepare("UPDATE organizations SET logo = ? WHERE organization_id = ?");
    $stmt->bind_param("si", $logo_url, $organization_id);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Organization Logo updated successfully!";
    } else {
        $response["message"] = "Failed to update database: " . $stmt->error;
        // Delete the uploaded file if the DB update fails
        unlink($destination_path); 
    }
    $stmt->close();
} else {
    $response["message"] = "Failed to move uploaded file.";
}

$conn->close();
echo json_encode($response);
exit;
?>