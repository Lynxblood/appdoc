<?php
require '../../../config/dbcon.php';
header('Content-Type: application/json');

$response = [
    "success" => false,
    "supporting_docs" => []
];

if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    // 1️⃣ Find the matching document ID
    $stmtDoc = $conn->prepare("SELECT document_id FROM documents WHERE event_id = ?");
    $stmtDoc->bind_param("i", $event_id);
    $stmtDoc->execute();
    $resultDoc = $stmtDoc->get_result();

    if ($resultDoc->num_rows > 0) {
        $docRow = $resultDoc->fetch_assoc();
        $document_id = $docRow['document_id'];
        $stmtDoc->close();

        // 2️⃣ Fetch existing proofs for this document
        $stmtProofs = $conn->prepare("
            SELECT 
                support_doc_id AS id,
                file_name,
                file_path,
                notes_summary,
                uploaded_at
            FROM supporting_documents
            WHERE document_id = ?
            ORDER BY uploaded_at DESC
        ");
        $stmtProofs->bind_param("i", $document_id);
        $stmtProofs->execute();
        $resultProofs = $stmtProofs->get_result();

        while ($row = $resultProofs->fetch_assoc()) {
            $response['supporting_docs'][] = $row;
        }

        $stmtProofs->close();
        $response["success"] = true;
    } else {
        $response["message"] = "No document found for this event.";
    }
} else {
    $response["message"] = "Missing event ID.";
}

echo json_encode($response);
?>
