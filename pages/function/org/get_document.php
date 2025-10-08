<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean(); // clear any previous output

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Fetch document details along with linked event_id
    $stmt = $conn->prepare("SELECT pdf_filename, content_html, event_id FROM documents WHERE document_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $document_data = $result->fetch_assoc();
    $stmt->close();

    if ($document_data) {
        $event_data = null;
        if (!empty($document_data['event_id'])) {
            $event_stmt = $conn->prepare("SELECT title, description, location, total_expenses, start_date, end_date FROM events WHERE event_id = ?");
            $event_stmt->bind_param("i", $document_data['event_id']);
            $event_stmt->execute();
            $event_result = $event_stmt->get_result();
            $event_data = $event_result->fetch_assoc();
            $event_stmt->close();
        }

        // Fetch comments
        $comments_stmt = $conn->prepare("
            SELECT c.comment_text, u.first_name, u.last_name, c.created_at 
            FROM comments c 
            JOIN users u ON c.user_id = u.user_id 
            WHERE c.document_id = ? 
            ORDER BY c.created_at ASC
        ");
        $comments_stmt->bind_param("i", $id);
        $comments_stmt->execute();
        $comments_result = $comments_stmt->get_result();
        $comments = [];
        while ($row = $comments_result->fetch_assoc()) {
            $comments[] = $row;
        }
        $comments_stmt->close();

        // Fetch supporting documents
        $supporting_stmt = $conn->prepare("
            SELECT support_doc_id as id,file_name, file_path, uploaded_at 
            FROM supporting_documents 
            WHERE document_id = ?
        ");
        $supporting_stmt->bind_param("i", $id);
        $supporting_stmt->execute();
        $supporting_result = $supporting_stmt->get_result();
        $supporting_docs = [];
        while ($row = $supporting_result->fetch_assoc()) {
            $supporting_docs[] = $row;
        }
        $supporting_stmt->close();

        echo json_encode([
            "success" => true,
            "filename" => $document_data['pdf_filename'],
            "content_html" => $document_data['content_html'],
            "event" => $event_data,
            "comments" => $comments,
            "supporting_docs" => $supporting_docs   // 👉 new
        ]);

    } else {
        echo json_encode(["success" => false, "message" => "Document not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Document ID is missing"]);
}
?>
