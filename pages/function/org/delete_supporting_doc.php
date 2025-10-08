<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = ["success" => false, "message" => "Invalid request"];

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("SELECT file_path FROM supporting_documents WHERE support_doc_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($file_path);
    
    if ($stmt->fetch()) {
        $stmt->close();

        // ✅ Strip leading ../function/org/ if it exists
        $normalizedPath = preg_replace('#^(\.\./)*function/org/#', '', $file_path);

        // Build the real path relative to this file
        $fullPath = __DIR__ . $normalizedPath;

        // Delete DB entry
        $del = $conn->prepare("DELETE FROM supporting_documents WHERE support_doc_id = ?");
        $del->bind_param("i", $id);
        if ($del->execute()) {
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $response = ["success" => true, "message" => "Supporting document deleted", "file_path" => $normalizedPath];
        } else {
            $response["message"] = "Database delete failed";
        }
        $del->close();
    } else {
        $response["message"] = "File not found in database";
    }
}

$conn->close();
echo json_encode($response);
?>
