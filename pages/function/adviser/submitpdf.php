<?php
include '../../../config/dbcon.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$id     = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'revision';
$user_id = $_SESSION['user_id'] ?? null;

// Simple update query
if ($id > 0 && $status !== '' && $user_id) {
    // Start a transaction to ensure both updates and notifications happen together
    $conn->begin_transaction();

    try {
        // --- START OF NEW CODE ---
        // 1. Get the user's base signature code from the database
        $sql_base_code = "SELECT signature_base_code FROM users WHERE user_id = ?";
        $stmt_base_code = $conn->prepare($sql_base_code);
        $stmt_base_code->bind_param("i", $user_id);
        $stmt_base_code->execute();
        $result_base_code = $stmt_base_code->get_result();
        $user_info = $result_base_code->fetch_assoc();
        $stmt_base_code->close();

        // Check if the user has a base signature code
        $user_signature_base_code = $user_info['signature_base_code'] ?? 'N/A';

        // 2. Generate a new unique code for this specific approval
        $new_unique_code = bin2hex(random_bytes(5)); // Generates a 10-character hexadecimal string

        // 3. Combine the base code and the new unique code
        $full_signature_code = $user_signature_base_code . '-' . $new_unique_code;

        // 4. Update the document with the new signature (This requires a PDF library like TCPDF or FPDF)
        // This is a placeholder section. You will need a library to perform this action.
        // Example:
        // if ($status == 'approved') {
        //     // Add logic here to load the document and insert the e-signature text or image
        //     // at the location of the [ADVISER_SIGNATURE] or [DEAN_SIGNATURE] tag.
        //     // save_modified_document_with_signature($document_id, $full_signature_code);
        // }
        
        // --- END OF NEW CODE ---

        // 1. Update document status
        $sql = "UPDATE documents SET status = ? WHERE document_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error updating document status: " . $stmt->error);
        }
        $stmt->close();

        // 2. Add document history entry, now including the e-signature code
        $sql_history = "INSERT INTO document_history (document_id, modified_by_user_id, from_status, to_status, e_signature_code, reason, timestamp) VALUES (?, ?, 'submitted', ?, ?, NULL, NOW())";
        $stmt_history = $conn->prepare($sql_history);
        $stmt_history->bind_param("iiss", $id, $user_id, $status, $full_signature_code);

        if (!$stmt_history->execute()) {
            throw new Exception("Error inserting into document history: " . $stmt_history->error);
        }
        $stmt_history->close();

        // 3. Find the user ID of the Dean
        $sql_dean = "SELECT user_id FROM users WHERE user_role = 'dean'";
        $stmt_dean = $conn->prepare($sql_dean);
        $stmt_dean->execute();
        $result_dean = $stmt_dean->get_result();
        $dean = $result_dean->fetch_assoc();
        $stmt_dean->close();

        if ($dean) {
            $dean_user_id = $dean['user_id'];
            
            // 4. Get the document's title for the notification message
            $sql_doc = "SELECT pdf_filename FROM documents WHERE document_id = ?";
            $stmt_doc = $conn->prepare($sql_doc);
            $stmt_doc->bind_param("i", $id);
            $stmt_doc->execute();
            $document = $stmt_doc->get_result()->fetch_assoc();
            $stmt_doc->close();

            $message = "A new document titled '" . $document['pdf_filename'] . "' has been submitted for your review.";

            // 5. Insert the notification for the Dean
            $sql_notif = "INSERT INTO notifications (user_id, message, document_id, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
            $stmt_notif = $conn->prepare($sql_notif);
            $stmt_notif->bind_param("isi", $dean_user_id, $message, $id);
            
            if (!$stmt_notif->execute()) {
                throw new Exception("Error inserting notification: " . $stmt_notif->error);
            }
            $stmt_notif->close();
        }

        $conn->commit();
        echo "Record updated successfully";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request or user not authenticated";
}

$conn->close();
?>