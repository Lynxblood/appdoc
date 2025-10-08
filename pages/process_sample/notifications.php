<?php
include '../config/dbcon.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all notifications for the logged-in user, ordered by creation date
$sql_notifications = "SELECT n.message, n.created_at, n.is_read, d.document_id, d.pdf_filename FROM notifications n JOIN documents d ON n.document_id = d.document_id WHERE n.user_id = ? ORDER BY n.created_at DESC";
$stmt = $conn->prepare($sql_notifications);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notifications_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Notifications</title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        .notification-item { border: 1px solid #ccc; padding: 1em; margin-bottom: 1em; border-radius: 8px; }
        .notification-item.unread { background-color: #e6f7ff; border-color: #91d5ff; }
        .notification-message { margin: 0 0 0.5em 0; }
        .notification-date { font-size: 0.8em; color: #666; }
        a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <h2>Your Notifications</h2>
    <a href="
    <?php 
    // Redirect to the appropriate dashboard based on user role
    if ($_SESSION['user_role'] == 'academic_organization' || $_SESSION['user_role'] == 'non_academic_organization') {
        echo 'org_dashboard.php';
    } else {
        echo 'approver_dashboard.php';
    }
    ?>
    ">Go to Dashboard</a>
    
    <?php if ($notifications_result->num_rows > 0): ?>
        <?php while ($row = $notifications_result->fetch_assoc()): ?>
            <div class="notification-item <?php echo ($row['is_read'] == 0) ? 'unread' : ''; ?>">
                <p class="notification-message">
                    <?php echo htmlspecialchars($row['message']); ?>
                </p>
                <p class="notification-date">
                    Received on: <?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?>
                </p>
                <a href="view_document.php?id=<?php echo $row['document_id']; ?>">View Document: <?php echo htmlspecialchars($row['pdf_filename']); ?></a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You have no new notifications.</p>
    <?php endif; ?>

</body>
</html>

<?php
// Mark all notifications as read after they've been displayed
$sql_mark_read = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
$stmt_mark_read = $conn->prepare($sql_mark_read);
$stmt_mark_read->bind_param("i", $user_id);
$stmt_mark_read->execute();
$stmt_mark_read->close();

$conn->close();
?>