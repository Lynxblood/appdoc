<?php
require '../../../config/dbcon.php'; // adjust path as needed

if (isset($_POST['document_id'])) {
    $document_id = intval($_POST['document_id']);

    // 1. Get current document
    $current_stmt = $conn->prepare("SELECT pdf_filename, document_id FROM documents WHERE document_id = ?");
    $current_stmt->bind_param("i", $document_id);
    $current_stmt->execute();
    $current_result = $current_stmt->get_result();
    $current_doc = $current_result->fetch_assoc();
    $current_stmt->close();

    // 2. Get history
    $stmt = $conn->prepare("
        SELECT dh.history_id, d.pdf_filename, dh.timestamp
        FROM document_history dh 
        JOIN documents d ON dh.document_id = d.document_id 
        WHERE dh.document_id = ? 
        ORDER BY dh.timestamp DESC
    ");
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0 && !$current_doc) {
        echo '<option value="">-- No Document --</option>';
    } else {
        echo '<option value="">-- Select document --</option>';

        // current document first
        if ($current_doc) {
            echo '<option value="Current_' . htmlspecialchars($current_doc['document_id']) . '">Current - ' . htmlspecialchars($current_doc['pdf_filename']) . '</option>';
        }

        // history documents
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['history_id']) . '">'
                  . htmlspecialchars($row['pdf_filename']) 
                 . ' (' . htmlspecialchars(FormatDateTime(($row['timestamp']))) . ')</option>';
        }
    }
    $stmt->close();
}
