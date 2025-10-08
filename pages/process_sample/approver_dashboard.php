<?php
include '../config/dbcon.php';
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['adviser', 'dean', 'osas', 'fssc', 'vice_pres_academic_affairs'])) {
    header("Location: login.php");
    exit();
}


$user_role = $_SESSION['user_role'];
$required_status = '';
switch ($user_role) {
    case 'adviser':
        $required_status = 'submitted';
        break;
    case 'dean':
        $required_status = 'pending'; // Assuming 'pending' is the status after adviser approval
        break;
    case 'fssc':
        $required_status = 'endorsed'; // Assuming 'endorsed' is the status after dean approval
        break;
    case 'osas':
        $required_status = 'approved_fssc'; // A new status for this example
        break;
    case 'vice_pres_academic_affairs':
        $required_status = 'approved_osas'; // A new status for this example
        break;
}

$documents_query = $conn->prepare("SELECT document_id, pdf_filename, status, created_at FROM documents WHERE status = ? ORDER BY created_at DESC");
$documents_query->bind_param("s", $required_status);
$documents_query->execute();
$documents_result = $documents_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucwords(str_replace('_', ' ', $user_role)); ?> Dashboard</title>
</head>
<body>
    <h2><?php echo ucwords(str_replace('_', ' ', $user_role)); ?> Dashboard</h2>
    <p>Welcome, User <?php echo $_SESSION['user_id']; ?>!</p>
    <a href="logout.php">Logout</a>

    <h3>Documents Awaiting Your Approval</h3>
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
                            <a href="view_document.php?id=<?php echo $row['document_id']; ?>">View and Action</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No documents are currently awaiting your approval.</p>
    <?php endif; ?>
    <?php $documents_query->close(); $conn->close(); ?>
</body>
</html>