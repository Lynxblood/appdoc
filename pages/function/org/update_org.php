<?php
// This file handles updating the organization's profile details.
require '../../../config/dbcon.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['organization_id'])) {
    $org_id = (int)$_POST['organization_id'];
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');
    
    // Basic validation
    if (empty($name) || empty($type) || empty($org_id)) {
        $response['message'] = 'Organization Name, Type, and ID are required.';
        echo json_encode($response);
        exit;
    }

    // Update organizations table using CORRECT columns: name and type
    $stmt = $conn->prepare("UPDATE organizations SET name = ?, type = ? WHERE organization_id = ?");
    $stmt->bind_param("ssi", $name, $type, $org_id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Organization profile updated successfully.';
    } else {
        $response['message'] = 'Database error: ' . $conn->error;
    }
    $stmt->close();
}

echo json_encode($response);
?>