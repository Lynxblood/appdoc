<?php
// PHP for fetching organizations and their document summaries
include 'db_connect.php';

// Fetch all organizations
$sql_orgs = "SELECT organization_id, name, type FROM organizations ORDER BY name ASC";
$result_orgs = $conn->query($sql_orgs);
$organizations = $result_orgs->fetch_all(MYSQLI_ASSOC);

// Document counts (Example: Approved Documents)
$sql_doc_counts = "SELECT organization_id, status, COUNT(*) as count FROM documents GROUP BY organization_id, status";
$result_doc_counts = $conn->query($sql_doc_counts);

$doc_summary = [];
while ($row = $result_doc_counts->fetch_assoc()) {
    $org_id = $row['organization_id'];
    if (!isset($doc_summary[$org_id])) {
        $doc_summary[$org_id] = [
            'total' => 0,
            'approved' => 0,
            'pending' => 0
        ];
    }
    $doc_summary[$org_id]['total'] += $row['count'];
    if ($row['status'] == 'approved') {
        $doc_summary[$org_id]['approved'] = $row['count'];
    } elseif ($row['status'] == 'pending') {
        $doc_summary[$org_id]['pending'] = $row['count'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizations & Documents | Stud Org System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4 text-success">Organizations & Document Summary 📂</h2>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow border-success">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        Registered Organizations
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createOrgModal">
                            + Create New Organization
                        </button>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if (count($organizations) > 0): ?>
                            <?php foreach ($organizations as $org): ?>
                                <?php
                                $id = $org['organization_id'];
                                $summary = $doc_summary[$id] ?? ['total' => 0, 'approved' => 0, 'pending' => 0];
                                $type_badge = $org['type'] == 'academic' ? 'primary' : 'info';
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold text-success"><?php echo htmlspecialchars($org['name']); ?> 
                                            <span class="badge bg-<?php echo $type_badge; ?> ms-2"><?php echo ucfirst(htmlspecialchars($org['type'])); ?></span>
                                        </div>
                                        Total Documents: **<?php echo $summary['total']; ?>**
                                    </div>
                                    <div>
                                        <span class="badge bg-success me-2">Approved: **<?php echo $summary['approved']; ?>**</span>
                                        <span class="badge bg-warning text-dark me-2">Pending: **<?php echo $summary['pending']; ?>**</span>
                                        
                                        <a href="org_documents.php?org_id=<?php echo $id; ?>" class="btn btn-sm btn-outline-success">View Documents</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No organizations registered.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="createOrgModal" tabindex="-1" aria-labelledby="createOrgModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="createOrgModalLabel">Create New Organization</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="organizations.php" method="POST">
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="orgName" class="form-label">Organization Name</label>
                      <input type="text" class="form-control" id="orgName" name="org_name" required>
                  </div>
                  <div class="mb-3">
                      <label for="orgType" class="form-label">Organization Type</label>
                      <select class="form-select" id="orgType" name="org_type" required>
                          <option value="academic">Academic</option>
                          <option value="non_academic">Non-Academic</option>
                      </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="create_org" class="btn btn-success">Create Organization</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// PHP for handling form submission (inside organizations.php or a separate handler)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_org'])) {
    $org_name = $conn->real_escape_string($_POST['org_name']);
    $org_type = $conn->real_escape_string($_POST['org_type']);

    // INSERT query
    $sql_insert = "INSERT INTO organizations (name, type) VALUES ('$org_name', '$org_type')";

    if ($conn->query($sql_insert) === TRUE) {
        // Success: Redirect to refresh the page and see the new organization
        header("Location: organizations.php?status=success");
        exit();
    } else {
        // Error: You would typically show a user-friendly error message here
        $error = "Error: " . $sql_insert . "<br>" . $conn->error;
        // In a real app, you'd log this and show a generic error to the user.
    }
}
$conn->close();
?>