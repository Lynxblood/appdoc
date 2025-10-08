<?php
include '../../../config/dbcon.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$id     = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'endorsed';

// Simple update query
if ($id > 0 && $status !== '') {
    $sql = "UPDATE documents SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
