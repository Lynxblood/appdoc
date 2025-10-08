<?php
include '../config/dbcon.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'academic_organization' && $_SESSION['user_role'] != 'non_academic_organization')) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];
$organization_id_query = $conn->prepare("SELECT organization_id FROM users WHERE user_id = ?");
$organization_id_query->bind_param("i", $user_id);
$organization_id_query->execute();
$organization_id_result = $organization_id_query->get_result();
$organization_id = $organization_id_result->fetch_assoc()['organization_id'];
$organization_id_query->close();

$documents_query = $conn->prepare("SELECT document_id, pdf_filename, status, created_at FROM documents WHERE organization_id = ? ORDER BY created_at DESC");
$documents_query->bind_param("i", $organization_id);
$documents_query->execute();
$documents_result = $documents_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organization Dashboard</title>
</head>
<body>
    <h2>Organization Dashboard</h2>
    <p>Welcome, User <?php echo $_SESSION['user_id']; ?>!</p>
    <a href="create_document.php">Create New Document</a> | <a href="logout.php">Logout</a>

    <h3>Your Documents</h3>
    <?php if ($documents_result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $documents_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['pdf_filename']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <a href="view_document.php?id=<?php echo $row['document_id']; ?>">View</a>
                            <?php if ($row['status'] == 'draft'): ?>
                                <a href="submit_document.php?id=<?php echo $row['document_id']; ?>">Submit for Approval</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not created any documents yet.</p>
    <?php endif; ?>
    <?php $documents_query->close(); $conn->close(); ?>
</body>
</html>