<?php
require '../../../config/dbcon.php';

// Check if the user is logged in as an adviser
if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== "adviser") {
    // Redirect to prevent unauthorized access
    header("location: ../../logout.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

// Check if a file was uploaded
if (isset($_FILES['signatureImage']) && $_FILES['signatureImage']['error'] === UPLOAD_ERR_OK) {
    $file_tmp_path = $_FILES['signatureImage']['tmp_name'];
    $file_name = $_FILES['signatureImage']['name'];
    $file_size = $_FILES['signatureImage']['size'];
    $file_type = $_FILES['signatureImage']['type'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Define allowed file types and max size
    $allowed_extensions = ['png', 'jpg', 'jpeg'];
    $max_file_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<script>alert('Invalid file type. Only PNG, JPG, and JPEG are allowed.'); window.history.back();</script>";
        exit();
    }

    if ($file_size > $max_file_size) {
        echo "<script>alert('File size exceeds the limit of 5MB.'); window.history.back();</script>";
        exit();
    }

    // Create a unique filename to prevent conflicts
    $new_file_name = 'signature_' . $user_id . '.' . $file_extension;
    $upload_dir = '../../../img/esig/';
    $upload_path = $upload_dir . $new_file_name;

    // Create the upload directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move the uploaded file
    if (move_uploaded_file($file_tmp_path, $upload_path)) {
        // Update the database with the new file path
        $sql = "UPDATE users SET e_signature_path = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $upload_path, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('E-signature uploaded successfully!'); window.location.href='../../adviser_pages/dashboard.php';</script>";
        } else {
            echo "<script>alert('Failed to update database: " . $stmt->error . "'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Failed to move the uploaded file.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('No file uploaded or an error occurred.'); window.history.back();</script>";
}
$conn->close();
?>