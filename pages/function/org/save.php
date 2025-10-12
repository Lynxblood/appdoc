<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

// Required fields
if (!isset($_POST['filename']) || !isset($_POST['content_html']) || !isset($_POST['document_type'])) {
    $response["message"] = "Required data is missing.";
    echo json_encode($response);
    exit;
}

$filename = $_POST['filename'];
$content_html = $_POST['content_html'];
$document_type = $_POST['document_type'];
$docId = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : null;

$user_id = $_SESSION['user_id']; // Replace with actual logged-in user ID
$organization_id = $_SESSION['organization_id']; // Replace with actual logged-in user's organization ID

// Event details
$event_title = $_POST['event_title'] ?? null;
$event_description = $_POST['event_description'] ?? null;
$event_location = $_POST['event_location'] ?? null;
$event_expenses = !empty($_POST['event_expenses']) ? $_POST['event_expenses'] : 0;
$event_from_date = $_POST['event_from_date'] ?? null;
$event_from_time = $_POST['event_from_time'] ?? "00:00";
$event_to_date = $_POST['event_to_date'] ?? null;
$event_to_time = $_POST['event_to_time'] ?? "00:00";

// Build proper datetime strings for events table
$start_datetime = $event_from_date && $event_from_time ? $event_from_date . " " . $event_from_time : null;
$end_datetime = $event_to_date && $event_to_time ? $event_to_date . " " . $event_to_time : null;

if ($docId) {
    // --- UPDATE ---
    // First get the event_id linked to this document
    $event_id = null;
    $check = $conn->prepare("SELECT event_id FROM documents WHERE document_id = ?");
    $check->bind_param("i", $docId);
    $check->execute();
    $check->bind_result($event_id);
    $check->fetch();
    $check->close();

    if ($event_id) {
        // Update event
        $stmtEvent = $conn->prepare("UPDATE events 
            SET title = ?, description = ?, location = ?, total_expenses = ?, 
                start_date = ?, end_date = ?, updated_at = NOW(), updated_by = ? 
            WHERE event_id = ?");
        $stmtEvent->bind_param(
            "sssdssii",
            $event_title, $event_description, $event_location, $event_expenses,
            $start_datetime, $end_datetime, $user_id, $event_id
        );
        $stmtEvent->execute();
        $stmtEvent->close();
    }

    // Update document
    $stmt = $conn->prepare("UPDATE documents 
        SET pdf_filename = ?, content_html = ?, document_type = ?, updated_at = NOW(), updated_by = ? 
        WHERE document_id = ?");
    $stmt->bind_param("sssii", $filename, $content_html, $document_type, $user_id, $docId);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Document & Event updated successfully!";
    } else {
        $response["message"] = "Update failed: " . $stmt->error;
    }
    $stmt->close();

} else {
    // --- INSERT ---
    // Insert into events first
    $stmtEvent = $conn->prepare("INSERT INTO events 
        (organization_id, title, description, start_date, end_date, location, total_expenses, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmtEvent->bind_param(
        "isssssd",
        $organization_id, $event_title, $event_description, $start_datetime, $end_datetime, $event_location, $event_expenses
    );

    if ($stmtEvent->execute()) {
        $event_id = $stmtEvent->insert_id;
    } else {
        $response["message"] = "Event insertion failed: " . $stmtEvent->error;
        echo json_encode($response);
        exit;
    }
    $stmtEvent->close();

    // Insert into documents with event_id
    $stmt = $conn->prepare("INSERT INTO documents 
        (pdf_filename, content_html, document_type, user_id, organization_id, event_id, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sssiii", $filename, $content_html, $document_type, $user_id, $organization_id, $event_id);

    if ($stmt->execute()) {
        $docId = $conn->insert_id; // Get the new document_id
        $response["success"] = true;
        $response["message"] = "New document & event created successfully!";
    } else {
        $response["message"] = "Document insertion failed: " . $stmt->error;
    }
    $stmt->close();
}


// --- Supporting Document Upload Logic ---
// Execute this only if the document save/update was successful and a file was uploaded.
if ($response["success"] && $docId && !empty($_FILES['supporting_document']['name'][0])) {
    
    // Check for errors in the $_FILES array
    $fileCount = count($_FILES['supporting_document']['name']);
    $filesUploaded = 0;
    
    // Loop through each uploaded file
    for ($i = 0; $i < $fileCount; $i++) {
        
        // Check if this specific file has an upload error
        if ($_FILES['supporting_document']['error'][$i] !== UPLOAD_ERR_OK) {
            // Skip this file or log an error
            $response["message"] .= " | File Error (File " . ($i+1) . "): Upload error code " . $_FILES['supporting_document']['error'][$i] . ".";
            continue;
        }

        $fileTmpPath = $_FILES['supporting_document']['tmp_name'][$i];
        $fileName = $_FILES['supporting_document']['name'][$i];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('pdf');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            
            // Define upload directory relative to the save.php file's location
            $uploadFileDir = 'uploads/pdfs/';
            
            if (!is_dir($uploadFileDir)) {
                if (!mkdir($uploadFileDir, 0777, true)) {
                    $response["message"] .= " | File Upload Error: Target directory could not be created.";
                    break; // Stop all file processing if directory can't be made
                }
            }
            
            // Create a unique filename for storage
            $newFileName = md5(time() . $fileName . $i) . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Path to store in DB (relative path for web access)
                $filePath = '../function/org/uploads/pdfs/' . $newFileName; 
                
                // Insert file path into the supporting_documents table
                $stmtSupport = $conn->prepare("INSERT INTO supporting_documents (document_id, file_path, file_name) VALUES (?, ?, ?)");
                $stmtSupport->bind_param("iss", $docId, $filePath, $fileName); 
                
                if ($stmtSupport->execute()) {
                    $filesUploaded++;
                } else {
                    $response["message"] .= " | Database insertion failed for file '{$fileName}': " . $stmtSupport->error;
                }
                $stmtSupport->close();

            } else {
                $response["message"] .= " | File Upload Error: Failed to move uploaded file '{$fileName}' to destination.";
            }
        } else {
            $response["message"] .= " | File Upload Error: File '{$fileName}' must be PDF.";
        }
    }
    
    if ($filesUploaded > 0) {
         $response["message"] .= " | Successfully uploaded {$filesUploaded} supporting document(s)!";
    }
}


$conn->close();
echo json_encode($response);
?>