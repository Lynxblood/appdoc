<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    $stmt = $conn->prepare("SELECT template_name, content_html FROM templates WHERE template_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "template_name" => $row['template_name'],
            "content_html" => $row['content_html']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Template not found."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
$conn->close();
?>