<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

if (isset($_POST['type']) && $_POST['type'] === 'current' && isset($_POST['document_id'])) {
    $doc_id = intval($_POST['document_id']);
    $stmt = $conn->prepare("SELECT content_html FROM documents WHERE document_id = ?");
    $stmt->bind_param("i", $doc_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "content_html" => $row['content_html']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Current document not found."]);
    }
    $stmt->close();
}
elseif (isset($_POST['type']) && $_POST['type'] === 'history' && isset($_POST['id'])) {
    $history_id = intval($_POST['id']);
    $stmt = $conn->prepare("SELECT old_content_html, timestamp FROM document_history WHERE history_id = ?");
    $stmt->bind_param("i", $history_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "history_timestamp" => $row['timestamp'],
            "content_html" => $row['old_content_html']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Document history entry not found."]);
    }
    $stmt->close();
}
else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?>
