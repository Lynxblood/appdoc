<?php
require '../../../config/dbcon.php';

header('Content-Type: application/json');

if (!isset($_GET['document_id'])) {
    echo json_encode([]);
    exit;
}

$document_id = $_GET['document_id'];
$current_user_id = $_SESSION['user_id'];

// 1️⃣ Find the organization linked to the document
$sql = "SELECT organization_id FROM documents WHERE document_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $document_id);
$stmt->execute();
$result = $stmt->get_result();
$doc = $result->fetch_assoc();
$stmt->close();

if (!$doc) {
    echo json_encode([]);
    exit;
}

$org_id = $doc['organization_id'];

// 2️⃣ Fetch all members of that org except current user
$query = $conn->prepare("
    SELECT user_id, first_name, last_name
    FROM users
    WHERE organization_id = ? AND user_id != ?
");
$query->bind_param("ii", $org_id, $current_user_id);
$query->execute();
$res = $query->get_result();
$members = $res->fetch_all(MYSQLI_ASSOC);
$query->close();

echo json_encode($members);
?>
