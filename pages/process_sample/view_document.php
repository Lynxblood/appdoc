<?php
include '../config/dbcon.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$document_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch document details
$stmt = $conn->prepare("SELECT d.*, u.user_role AS creator_role FROM documents d JOIN users u ON d.organization_id = u.organization_id WHERE d.document_id = ?");
$stmt->bind_param("i", $document_id);
$stmt->execute();
$document = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$document) {
    die("Document not found.");
}

// Fetch document history
$history_query = $conn->prepare("SELECT dh.*, u.first_name, u.last_name FROM document_history dh JOIN users u ON dh.modified_by_user_id = u.user_id WHERE dh.document_id = ? ORDER BY dh.timestamp DESC");
$history_query->bind_param("i", $document_id);
$history_query->execute();
$history_result = $history_query->get_result();
$history = $history_result->fetch_all(MYSQLI_ASSOC);
$history_query->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($document['pdf_filename']); ?></title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        .document-content { border: 1px solid #ccc; padding: 1em; background: #f9f9f9; }
        .history-section { margin-top: 2em; }
        .history-item { border-left: 3px solid #007bff; padding-left: 1em; margin-bottom: 1em; }
        .actions-form { margin-top: 2em; }
        .actions-form button { padding: 10px; margin-right: 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h2><?php echo htmlspecialchars($document['pdf_filename']); ?></h2>
    <p><strong>Status:</strong> <?php echo htmlspecialchars(ucwords($document['status'])); ?></p>
    <div class="document-content">
        <?php echo $document['content_html']; ?>
    </div>

    <div class="actions-form">
        <?php
        // Only show actions to the current approver
        $show_actions = false;
        if ($user_role == 'adviser' && $document['status'] == 'submitted') {
            $show_actions = true;
            $actions = ['pending', 'reject', 'revision'];
        } else if ($user_role == 'dean' && $document['status'] == 'pending') {
            $show_actions = true;
            $actions = ['endorsed', 'reject', 'revision'];
        } else if ($user_role == 'fssc' && $document['status'] == 'endorsed') {
            $show_actions = true;
            $actions = ['approved', 'reject', 'revision'];
        }
        
        if ($show_actions):
        ?>
            <h3>Take Action on this Document</h3>
            <form action="process_approval.php" method="post">
                <input type="hidden" name="document_id" value="<?php echo $document_id; ?>">
                <label for="reason">Reason (for rejection/revision):</label><br>
                <textarea id="reason" name="reason" rows="4" cols="50"></textarea><br><br>
                <?php foreach ($actions as $action): ?>
                    <button type="submit" name="action" value="<?php echo $action; ?>"><?php echo ucwords($action); ?></button>
                <?php endforeach; ?>
            </form>
        <?php endif; ?>
    </div>

    <div class="history-section">
        <h3>Document History</h3>
        <?php if (!empty($history)): ?>
            <?php foreach ($history as $item): ?>
                <div class="history-item">
                    <strong>Status changed from "<?php echo ucwords($item['from_status']); ?>" to "<?php echo ucwords($item['to_status']); ?>"</strong>
                    <br>
                    By: <?php echo htmlspecialchars($item['first_name'] . ' ' . $item['last_name']); ?> on <?php echo htmlspecialchars($item['timestamp']); ?>
                    <?php if (!empty($item['reason'])): ?>
                        <br>
                        Reason: <em><?php echo htmlspecialchars($item['reason']); ?></em>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No history available for this document.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>