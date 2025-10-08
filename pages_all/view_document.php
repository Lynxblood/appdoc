<?php
require '../config/dbcon.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$app_id = $_GET['app_id'] ?? null;
$view_history = isset($_GET['history']);
$revision_id = $_GET['revision_id'] ?? null;
$user_role = $_SESSION['user_role'];

if (!$app_id) {
    die("Application ID not provided.");
}

$is_approver_who_can_edit = false;
$sql = "SELECT current_step FROM accreditation_applications WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $app_id);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();
$current_step = $application['current_step'] ?? null;
$stmt->close();

if (($user_role === 'osas' && $current_step === 'submitted') || 
    ($user_role === 'program_chair' && $current_step === 'reviewed') || 
    ($user_role === 'dean' && $current_step === 'endorsed')) {
    $is_approver_who_can_edit = true;
}
var_dump($is_approver_who_can_edit);
var_dump($user_role);
var_dump($current_step);
$document = null;
if ($view_history && $revision_id) {
    $sql = "SELECT * FROM document_revisions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $revision_id);
} else {
    $sql = "SELECT * FROM document_revisions WHERE application_id = ? ORDER BY revision_number DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
}
$stmt->execute();
$result = $stmt->get_result();
$document = $result->fetch_assoc();
$stmt->close();

if (!$document) {
    die("Document not found for this application.");
}

$revisions = [];
if ($view_history) {
    $sql = "SELECT id, revision_number, created_at, submitted_by_user_id, revision_type FROM document_revisions WHERE application_id = ? ORDER BY revision_number DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $revisions[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Document</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .document-container { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #ddd; background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .logo-container { text-align: center; margin-bottom: 20px; }
        .logo-container img { max-width: 150px; height: auto; }
        .letter-header, .letter-body { margin-bottom: 20px; }
        .letter-recipient, .letter-date { margin: 5px 0; }
        h1, h2 { text-align: center; }
        pre { white-space: pre-wrap; word-wrap: break-word; font-family: sans-serif; }
        .revision-history { margin-top: 40px; }
        .revision-history h3 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        .revision-history ul { list-style-type: none; padding: 0; }
        .revision-history li { margin-bottom: 10px; border: 1px solid #ccc; padding: 10px; border-radius: 5px; }
        .back-link { margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>
    <a href="dashboard.php" class="back-link">&larr; Back to Dashboard</a>
    
    <?php if ($view_history): ?>
        <div class="revision-history">
            <h2>Document History for Application #<?php echo htmlspecialchars($app_id); ?></h2>
            <?php if ($revision_id): ?>
                <h3>Viewing Revision #<?php echo htmlspecialchars($document['revision_number']); ?></h3>
                <div class="document-container">
                    <div class="logo-container">
                        <img src="<?php echo htmlspecialchars($document['organization_logo']); ?>" alt="Organization Logo">
                    </div>
                    <div class="letter-header">
                        <p class="letter-recipient"><strong>To:</strong> <?php echo nl2br(htmlspecialchars($document['letter_recipient'])); ?></p>
                        <p class="letter-date"><strong>Date:</strong> <?php echo htmlspecialchars($document['letter_date']); ?></p>
                    </div>
                    <div class="letter-body">
                        <pre><?php echo htmlspecialchars($document['letter_content']); ?></pre>
                    </div>
                </div>
            <?php endif; ?>
            <h3>All Revisions</h3>
            <ul>
                <?php foreach ($revisions as $rev): ?>
                    <li>
                        <strong>Revision #<?php echo htmlspecialchars($rev['revision_number']); ?></strong>
                        <br>
                        Created at: <?php echo htmlspecialchars($rev['created_at']); ?>
                        <br>
                        <a href="view_document.php?app_id=<?php echo htmlspecialchars($app_id); ?>&history=true&revision_id=<?php echo htmlspecialchars($rev['id']); ?>">View this version</a>
                        <a href="view_document.php?app_id=<?php echo htmlspecialchars($app_id);?>">Edit</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <h2>Document for Application #<?php echo htmlspecialchars($app_id); ?></h2>
        <?php if ($is_approver_who_can_edit): ?>
            <div class="container">
                <h3>Make Revisions</h3>
                <form action="process.php?action=request_revision" method="POST">
                    <input type="hidden" name="app_id" value="<?php echo htmlspecialchars($app_id); ?>">
                    <label for="organization_logo">Organization Logo URL:</label>
                    <input type="text" id="organization_logo" name="organization_logo" value="<?php echo htmlspecialchars($document['organization_logo'] ?? ''); ?>" required>
                    <label for="letter_recipient">To:</label>
                    <input type="text" id="letter_recipient" name="letter_recipient" value="<?php echo htmlspecialchars($document['letter_recipient'] ?? ''); ?>" required>
                    <label for="letter_date">Date:</label>
                    <input type="date" id="letter_date" name="letter_date" value="<?php echo htmlspecialchars($document['letter_date'] ?? date('Y-m-d')); ?>" required>
                    <label for="letter_content">Letter Content:</label>
                    <textarea id="letter_content" name="letter_content" required><?php echo htmlspecialchars($document['letter_content'] ?? ''); ?></textarea>
                    <button type="submit">Submit Revision</button>
                </form>
            </div>
        <?php else: ?>
            <div class="document-container">
                <div class="logo-container">
                    <img src="<?php echo htmlspecialchars($document['organization_logo']); ?>" alt="Organization Logo">
                </div>
                <div class="letter-header">
                    <p class="letter-recipient"><strong>To:</strong> <?php echo nl2br(htmlspecialchars($document['letter_recipient'])); ?></p>
                    <p class="letter-date"><strong>Date:</strong> <?php echo htmlspecialchars($document['letter_date']); ?></p>
                </div>
                <div class="letter-body">
                    <pre><?php echo htmlspecialchars($document['letter_content']); ?></pre>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>