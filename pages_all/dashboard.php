<?php
require '../config/dbcon.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];
$applications = [];

$sql = "SELECT 
            ac.*, 
            o.organization_name
        FROM accreditation_applications ac
        JOIN organizations o ON ac.organization_id = o.id";

// Filter based on user role and current step
switch ($user_role) {
    case 'academic_organization':
    case 'non_academic_organization':
        $sql .= " WHERE ac.organization_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['organization_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        $stmt->close();
        break;
    case 'osas':
        $sql .= " WHERE ac.status = 'submitted' AND ac.current_step = 'submitted'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        break;
    case 'dean':
        $sql .= " WHERE ac.status = 'reviewed' AND ac.current_step = 'reviewed'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        break;
    case 'program_chair':
        $sql .= " WHERE ac.status = 'endorsed' AND ac.current_step = 'endorsed'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        break;
    case 'vice_pres_academic_affairs':
        $sql .= " WHERE ac.status = 'approved' AND ac.current_step = 'approved'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        break;
    default:
        $applications = [];
        break;
}

function getActionButtons($role, $status, $app_id) {
    if ($role === 'osas' && $status === 'submitted') {
        return '<form action="process.php" method="POST" class="action-form">
                    <input type="hidden" name="app_id" value="' . htmlspecialchars($app_id) . '">
                    <button type="submit" name="action" value="approve">Endorse</button>
                    <a href="view_document.php?app_id=' . htmlspecialchars($app_id) . '">Request Revision</a>
                </form>';
    }
    if ($role === 'dean' && $status === 'reviewed') {
        return '<form action="process.php" method="POST" class="action-form">
                    <input type="hidden" name="app_id" value="' . htmlspecialchars($app_id) . '">
                    <button type="submit" name="action" value="approve">Approve</button>
                    <a href="view_document.php?app_id=' . htmlspecialchars($app_id) . '">Request Revision</a>
                </form>';
    }
    if ($role === 'program_chair' && $status === 'endorsed') {
        return '<form action="process.php" method="POST" class="action-form">
                    <input type="hidden" name="app_id" value="' . htmlspecialchars($app_id) . '">
                    <button type="submit" name="action" value="approve">Approve</button>
                    <a href="view_document.php?app_id=' . htmlspecialchars($app_id) . '">Request Revision</a>
                </form>';
    }
    if ($role === 'vice_pres_academic_affairs' && $status === 'approved') {
        return '<form action="process.php" method="POST" class="action-form">
                    <input type="hidden" name="app_id" value="' . htmlspecialchars($app_id) . '">
                    <button type="submit" name="action" value="approve">Accredit</button>
                    <button type="submit" name="action" value="reject">Reject</button>
                </form>';
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; }
        .table-container { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .action-form { display: inline; }
        .links { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Role: <?php echo htmlspecialchars($user_role); ?></p>
        <a href="logout.php">Logout</a>
    </div>

    <div class="links">
        <?php if ($user_role === 'academic_organization' || $user_role === 'non_academic_organization'): ?>
            <a href="submit_application.php">Submit New Accreditation Application</a>
        <?php endif; ?>
    </div>

    <div class="table-container">
        <h2>Accreditation Applications</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Organization Name</th>
                    <th>Status</th>
                    <th>Current Step</th>
                    <th>Date Submitted</th>
                    <th>Actions</th>
                    <th>Document</th>
                    <th>History</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($applications)): ?>
                    <tr><td colspan="8">No applications to display.</td></tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($app['id']); ?></td>
                        <td><?php echo htmlspecialchars($app['organization_name']); ?></td>
                        <td><?php echo htmlspecialchars($app['status']); ?></td>
                        <td><?php echo htmlspecialchars($app['current_step']); ?></td>
                        <td><?php echo htmlspecialchars($app['date_submitted']); ?></td>
                        <td>
                            <?php 
                                if ($app['status'] === 'revision_requested' && ($user_role === 'academic_organization' || $user_role === 'non_academic_organization')): 
                            ?>
                                <a href="submit_application.php?app_id=<?php echo htmlspecialchars($app['id']); ?>">Revise and Resubmit</a>
                            <?php else: ?>
                                <?php echo getActionButtons($user_role, $app['status'], $app['id']); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($app['revision_id'])): ?>
                                <a href="view_document.php?app_id=<?php echo htmlspecialchars($app['id']); ?>">View Document</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="view_document.php?app_id=<?php echo htmlspecialchars($app['id']); ?>&history=true">View History</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>