<?php
require '../../../config/dbcon.php';
header('Content-Type: application/json');
ob_clean();

$response = ["success" => false, "message" => "Invalid request"];

if (isset($_POST['event_id'])) {
    $event_id = intval($_POST['event_id']);

    // 🟢 Fetch event details
    $stmt = $conn->prepare("SELECT event_id, title, description, location, start_date, total_expenses FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $eventResult = $stmt->get_result();

    if ($event = $eventResult->fetch_assoc()) {

        // 🟢 Fetch related document_id
        $docStmt = $conn->prepare("SELECT document_id FROM documents WHERE event_id = ?");
        $docStmt->bind_param("i", $event_id);
        $docStmt->execute();
        $docResult = $docStmt->get_result();
        $document_id = $docResult->num_rows > 0 ? $docResult->fetch_assoc()['document_id'] : null;
        $docStmt->close();

        // 🟢 Fetch uploaded proofs if document exists
        $proofs = [];
        if ($document_id) {
            $proofStmt = $conn->prepare("SELECT support_doc_id AS id, file_name, file_path, notes_summary, uploaded_at FROM supporting_documents WHERE document_id = ?");
            $proofStmt->bind_param("i", $document_id);
            $proofStmt->execute();
            $proofs = $proofStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $proofStmt->close();
        }

        $response = [
            "success" => true,
            "event" => $event,
            "proofs" => $proofs
        ];
    } else {
        $response["message"] = "Event not found.";
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
