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

    // Fetch document details
    $stmt = $conn->prepare("SELECT pdf_filename, content_html FROM documents WHERE document_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $document_data = $result->fetch_assoc();
    $stmt->close();

    if ($document_data) {
        // Fetch comments for the document, ordered by creation date
        $comments_stmt = $conn->prepare("SELECT c.comment_text, c.user_id, u.first_name, u.last_name, c.created_at, c.comment_id FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.document_id = ? ORDER BY c.created_at ASC");
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
            "comments" => $comments,
            "current_user_id" => $_SESSION['user_id'],
            "supporting_docs" => $supporting_docs 
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Document not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Document ID is missing"]);
}
?>