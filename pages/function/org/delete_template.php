<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

if (!isset($_POST['id'])) {
    $response["message"] = "Required data is missing.";
    echo json_encode($response);
    exit;
}

$template_id = intval($_POST['id']);

$stmt = $conn->prepare("DELETE FROM templates WHERE template_id = ?");
$stmt->bind_param("i", $template_id);
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response["success"] = true;
        $response["message"] = "Template deleted successfully.";
    } else {
        $response["message"] = "Template not found.";
    }
} else {
    $response["message"] = "Failed to delete template: " . $stmt->error;
}
$stmt->close();
$conn->close();
echo json_encode($response);
exit;
?>