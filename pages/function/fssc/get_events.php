<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');

// Fetch events with organization logo and approved documents
$sql = "SELECT e.event_id, e.title, e.description, e.start_date, e.end_date, e.location,
               CONCAT('../../', o.logo) AS imageUrl,
               (
                   SELECT GROUP_CONCAT(
                       CONCAT(d.document_id, '::', d.document_type, '::', REPLACE(d.content_html, '|', '')) 
                       SEPARATOR '|'
                   )
                   FROM documents d
                   WHERE d.event_id = e.event_id 
                     AND d.status = 'approved_fssc'
               ) AS documents
        FROM events e
        LEFT JOIN organizations o ON e.organization_id = o.organization_id
        WHERE EXISTS (
            SELECT 1 FROM documents d2 
            WHERE d2.event_id = e.event_id AND d2.status = 'approved_fssc'
        )";

$result = $conn->query($sql);

$events = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Parse documents into array of objects
        $docs = [];
        if (!empty($row['documents'])) {
            $docParts = explode('|', $row['documents']);
            foreach ($docParts as $part) {
                list($docId, $docType, $docHtml) = explode('::', $part);
                $docs[] = [
                    'document_id'   => $docId,
                    'document_type' => $docType,
                    'content_html'  => $docHtml
                ];
            }
        }
        $row['documents'] = $docs;
        $events[] = $row;
    }
}

echo json_encode($events);
$conn->close();
