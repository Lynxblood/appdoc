<?php
require '../../../config/dbcon.php';
header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => ""
];

if (isset($_POST['event_id']) && isset($_FILES['proof_files'])) {

    $event_id = intval($_POST['event_id']);
    $notes_summary = isset($_POST['proof_notes']) ? trim($_POST['proof_notes']) : '';

    // 🟢 Step 1: Fetch the corresponding document_id from documents table
    $docQuery = $conn->prepare("SELECT document_id FROM documents WHERE event_id = ?");
    $docQuery->bind_param("i", $event_id);
    $docQuery->execute();
    $docResult = $docQuery->get_result();

    if ($docResult->num_rows === 0) {
        $response["message"] = "No matching document found for this event.";
        echo json_encode($response);
        exit;
    }

    $docRow = $docResult->fetch_assoc();
    $document_id = $docRow['document_id'];

    $docQuery->close();

    // 🟢 Step 2: Handle file uploads
    $fileCount = count($_FILES['proof_files']['name']);
    $filesUploaded = 0;
    $allowedfileExtensions = ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx', 'xls', 'xlsx'];

    $uploadFileDir = 'uploads/pdfs/';

    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0777, true);
    }

    for ($i = 0; $i < $fileCount; $i++) {

        $error = $_FILES['proof_files']['error'][$i];
        if ($error !== UPLOAD_ERR_OK) {
            $response["message"] .= "File " . ($i + 1) . " upload error (code $error). ";
            continue;
        }

        $fileTmpPath = $_FILES['proof_files']['tmp_name'][$i];
        $fileName = $_FILES['proof_files']['name'][$i];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedfileExtensions)) {
            $response["message"] .= "File '{$fileName}' is not allowed. ";
            continue;
        }

        // Unique name
        $newFileName = md5(time() . $fileName . $i) . '.' . $fileExtension;
        $destPath = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {

            // relative web path
            $filePath = '../function/org/uploads/pdfs/' . $newFileName;

            // 🟢 Step 3: Insert into supporting_documents
            $stmt = $conn->prepare("
                INSERT INTO supporting_documents (document_id, file_name, file_path, uploaded_at, notes_summary)
                VALUES (?, ?, ?, NOW(), ?)
            ");
            $stmt->bind_param("isss", $document_id, $fileName, $filePath, $notes_summary);

            if ($stmt->execute()) {
                $filesUploaded++;
            } else {
                $response["message"] .= "Database error for '{$fileName}': " . $stmt->error;
            }

            $stmt->close();

        } else {
            $response["message"] .= "Failed to move file '{$fileName}'. ";
        }
    }

    // 🟢 Step 4: Response
    if ($filesUploaded > 0) {
        $response["success"] = true;
        $response["message"] = "Successfully uploaded {$filesUploaded} file(s).";
    } else {
        if (empty($response["message"])) {
            $response["message"] = "No files uploaded.";
        }
    }

} else {
    $response["message"] = "Missing required data.";
}

echo json_encode($response);
?>
