<?php
require '../../../config/dbcon.php';

$current_user_id = $_SESSION['user_id'];
$document_id = $_POST['document_id'];
$user_to_grant_id = $_POST['user_to_grant_id'];
$access_level = $_POST['access_level']; // 'view', 'edit', or 'comment'

// 1️⃣ Check if current user can grant access
$check_authority_sql = "
    SELECT u.user_role, u.rank_id, u.organization_id AS user_org_id,
           d.user_id AS document_owner_id, d.organization_id AS doc_org_id
    FROM users u
    JOIN documents d ON d.document_id = ?
    WHERE u.user_id = ?
";
$stmt = $conn->prepare($check_authority_sql);
$stmt->bind_param("ii", $document_id, $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$auth_data = $result->fetch_assoc();
$stmt->close();

$can_grant = false;
if ($auth_data) {
    $role = $auth_data['user_role'];
    $rank = $auth_data['rank_id'];
    $user_org = $auth_data['user_org_id'];
    $doc_org = $auth_data['doc_org_id'];

    if (($role === 'adviser' && $user_org === $doc_org) ||
        ($role === 'academic_organization' && $rank == 5 && $user_org === $doc_org)) {
        $can_grant = true;
    }
}

// 2️⃣ Grant Access if authorized
if ($can_grant) {
    $sql = "
        INSERT INTO document_access (document_id, user_id, access_level, granted_by_user_id)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            access_level = VALUES(access_level),
            granted_by_user_id = VALUES(granted_by_user_id),
            granted_at = CURRENT_TIMESTAMP()
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $document_id, $user_to_grant_id, $access_level, $current_user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Access granted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'denied', 'message' => 'You do not have permission to grant access for this document.']);
}
?>
