<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

if (!isset($_POST['template_name']) || !isset($_POST['content_html'])) {
    $response["message"] = "Required data is missing.";
    echo json_encode($response);
    exit;
}

$template_name = $_POST['template_name'];
$content_html = $_POST['content_html'];
$template_id = intval($_POST['template_id'] ?? 0);

if ($template_id > 0) {
    // Update existing template
    $stmt = $conn->prepare("UPDATE templates SET template_name = ?, content_html = ? WHERE template_id = ?");
    $stmt->bind_param("ssi", $template_name, $content_html, $template_id);
    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Template updated successfully!";
    } else {
        $response["message"] = "Failed to update template.";
    }
} else {
    // Insert new template
    $stmt = $conn->prepare("INSERT INTO templates (template_name, content_html) VALUES (?, ?)");
    $stmt->bind_param("ss", $template_name, $content_html);
    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Template created successfully!";
    } else {
        $response["message"] = "Failed to create template.";
    }
}

$stmt->close();
$conn->close();
echo json_encode($response);
exit;
?>