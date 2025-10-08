<?php
require '../../config/dbcon.php'; // Adjust the path as needed

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

// Check for required data and a valid file upload
if (!isset($_POST['organization_id']) || !isset($_FILES['logoFile'])) {
    $response["message"] = "Required data or file is missing.";
    echo json_encode($response);
    exit;
}

$organization_id = intval($_POST['organization_id']);
$file = $_FILES['logoFile'];

// Check for upload errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    $response["message"] = "File upload error: " . $file['error'];
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
$upload_dir = '../../img/logo/'; // Adjust the path as needed
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generate a unique filename to prevent overwrites
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
        $response["message"] = "Logo updated successfully!";
    } else {
        $response["message"] = "Failed to update database: " . $stmt->error;
        // You might want to delete the uploaded file here if the DB update fails
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