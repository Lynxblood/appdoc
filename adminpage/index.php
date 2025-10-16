<?php
// PHP for fetching dashboard data
include 'db_connect.php';

// Total Organizations
$sql_orgs = "SELECT COUNT(*) AS total_orgs FROM organizations";
$result_orgs = $conn->query($sql_orgs);
$total_orgs = $result_orgs->fetch_assoc()['total_orgs'];

// Total Documents
$sql_docs = "SELECT COUNT(*) AS total_docs FROM documents";
$result_docs = $conn->query($sql_docs);
$total_docs = $result_docs->fetch_assoc()['total_docs'];

// Recent Activities (Limit to 5)
$sql_activities = "SELECT
    o.name AS organization_name,
    ura.description,
    ura.created_at
FROM
    org_recent_activities ura
JOIN
    organizations o ON ura.organization_id = o.organization_id
ORDER BY
    ura.created_at DESC
LIMIT 5";
$result_activities = $conn->query($sql_activities);
$recent_activities = $result_activities->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Stud Org System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4 text-success">Dashboard Summary 📊</h2>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Organizations</h5>
                        <h1 class="display-4"><?php echo $total_orgs; ?></h1>
                        <p class="card-text">Academic & Non-Academic</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Documents</h5>
                        <h1 class="display-4"><?php echo $total_docs; ?></h1>
                        <p class="card-text">Draft, Pending, Approved</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-success shadow">
                    <div class="card-body">
                        <h5 class="card-title text-success">Upcoming Events</h5>
                        <h1 class="display-4">...</h1>
                        <p class="card-text">Check the Events page</p>
                    </div>
                </div>
            </div>

             <div class="col-md-6 col-lg-3">
                <div class="card border-success shadow">
                    <div class="card-body">
                        <h5 class="card-title text-success">Users Online</h5>
                        <h1 class="display-4">...</h1>
                        <p class="card-text">Live User Count</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="row">
            <div class="col-12">
                <div class="card shadow border-success">
                    <div class="card-header bg-success text-white">
                        Recent Activities
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if (count($recent_activities) > 0): ?>
                            <?php foreach ($recent_activities as $activity): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><?php echo htmlspecialchars($activity['organization_name']); ?></div>
                                        <?php echo htmlspecialchars($activity['description']); ?>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill"><?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No recent activity recorded.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>