<?php
require '../config/dbcon.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'academic_organization' && $_SESSION['user_role'] !== 'non_academic_organization')) {
    header('Location: login.php');
    exit;
}

$organization_id = $_SESSION['organization_id'];
$application = null;
$page_title = 'Submit New Application';
$form_action = 'process.php?action=submit_new';

if (isset($_GET['app_id'])) {
    $app_id = $_GET['app_id'];
    $page_title = 'Revise Application';
    $form_action = 'process.php?action=resubmit_revision';
    
    $sql = "SELECT * FROM document_revisions WHERE application_id = ? ORDER BY revision_number DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $stmt->close();
}

$organization_name = '';
$sql = "SELECT organization_name FROM organizations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $organization_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $organization_name = $row['organization_name'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f0f2f5; }
        .container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 800px; margin: auto; }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; gap: 15px; }
        label { font-weight: bold; }
        input[type="text"], textarea, input[type="date"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 200px; }
        button { padding: 10px 20px; border: none; background-color: #007bff; color: white; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($page_title); ?></h2>
        <form action="<?php echo htmlspecialchars($form_action); ?>" method="POST">
            <?php if (isset($_GET['app_id'])): ?>
                <input type="hidden" name="app_id" value="<?php echo htmlspecialchars($_GET['app_id']); ?>">
            <?php else: ?>
                <input type="hidden" name="organization_id" value="<?php echo htmlspecialchars($organization_id); ?>">
            <?php endif; ?>

            <p>Organization: <strong><?php echo htmlspecialchars($organization_name); ?></strong></p>
            
            <label for="organization_logo">Organization Logo URL:</label>
            <input type="text" id="organization_logo" name="organization_logo" value="<?php echo htmlspecialchars($application['organization_logo'] ?? ''); ?>" required>
            
            <label for="letter_recipient">To:</label>
            <input type="text" id="letter_recipient" name="letter_recipient" value="<?php echo htmlspecialchars($application['letter_recipient'] ?? ''); ?>" required>
            
            <label for="letter_date">Date:</label>
            <input type="date" id="letter_date" name="letter_date" value="<?php echo htmlspecialchars($application['letter_date'] ?? date('Y-m-d')); ?>" required>
            
            <label for="letter_content">Letter Content:</label>
            <textarea id="letter_content" name="letter_content" required><?php echo htmlspecialchars($application['letter_content'] ?? ''); ?></textarea>
            
            <button type="submit">
                <?php echo isset($_GET['app_id']) ? 'Resubmit' : 'Submit'; ?> Application
            </button>
        </form>
    </div>
</body>
</html>