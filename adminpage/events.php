<?php
// PHP for fetching all events with organization names
include 'db_connect.php';

$sql_events = "SELECT 
    e.title, 
    e.description, 
    e.start_date, 
    e.end_date, 
    e.location, 
    o.name AS organization_name 
FROM 
    events e
JOIN 
    organizations o ON e.organization_id = o.organization_id
WHERE
    e.end_date >= NOW()  -- Filter for upcoming events
ORDER BY 
    e.start_date ASC";

$result_events = $conn->query($sql_events);
$events = $result_events->fetch_all(MYSQLI_ASSOC);

// Group events by organization for better display
$events_by_org = [];
foreach ($events as $event) {
    $org_name = $event['organization_name'];
    if (!isset($events_by_org[$org_name])) {
        $events_by_org[$org_name] = [];
    }
    $events_by_org[$org_name][] = $event;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | Stud Org System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4 text-success">Upcoming Organization Events 📅</h2>

        <?php if (count($events_by_org) > 0): ?>
            <?php foreach ($events_by_org as $org_name => $org_events): ?>
                <h4 class="mt-4 mb-3 text-secondary"><?php echo htmlspecialchars($org_name); ?></h4>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
                    <?php foreach ($org_events as $event): ?>
                        <div class="col">
                            <div class="card h-100 border-success shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-success"><?php echo htmlspecialchars($event['title']); ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($event['location']); ?></h6>
                                    <p class="card-text small"><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?></p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item small">
                                        **Start:** **<?php echo date('M d, Y h:i A', strtotime($event['start_date'])); ?>**
                                    </li>
                                    <li class="list-group-item small">
                                        **End:** **<?php echo date('M d, Y h:i A', strtotime($event['end_date'])); ?>**
                                    </li>
                                </ul>
                                <div class="card-footer bg-light border-top-0">
                                    <a href="#" class="btn btn-sm btn-outline-success">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">No upcoming events found.</div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>