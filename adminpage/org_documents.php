<?php
include 'db_connect.php';

$organization_id = null;
$organization_name = "Organization Documents";
$documents = [];
$error_message = "";

// Helper function to safely fetch organization details
function get_organization_details($conn, $org_id) {
    $sql = "SELECT organization_id, name FROM organizations WHERE organization_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $org_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Helper function to fetch all documents for a given organization ID
function get_documents_by_organization($conn, $organization_id) {
    $sql = "SELECT 
                d.document_id,
                d.document_type,
                d.status,
                d.created_at,
                d.updated_at,
                u.first_name,
                u.last_name
            FROM 
                documents d
            JOIN 
                users u ON d.user_id = u.user_id
            WHERE 
                d.organization_id = ?
            AND
                d.is_archived = 0
            ORDER BY 
                d.updated_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $organization_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


if (isset($_GET['org_id']) && is_numeric($_GET['org_id'])) {
    $organization_id = (int)$_GET['org_id'];
    
    $org_details = get_organization_details($conn, $organization_id);
    
    if ($org_details) {
        $organization_name = htmlspecialchars($org_details['name']);
        $documents = get_documents_by_organization($conn, $organization_id);
    } else {
        $error_message = "Error: Organization not found.";
    }
} else {
    $error_message = "Error: Invalid organization ID provided.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $organization_name; ?> Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4 text-success"><?php echo $organization_name; ?>'s Documents 📋</h2>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php elseif (count($documents) > 0): ?>
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white">
                    Document List (Total: <?php echo count($documents); ?>)
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Document Type</th>
                                <th>Status</th>
                                <th>Submitted By</th>
                                <th>Created At</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td><?php echo $doc['document_id']; ?></td>
                                    <td>**<?php echo htmlspecialchars($doc['document_type']); ?>**</td>
                                    <td>
                                        <?php 
                                            $status_class = match($doc['status']) {
                                                'approved', 'approved_fssc' => 'badge bg-success',
                                                'pending', 'submitted' => 'badge bg-warning text-dark',
                                                'rejected', 'revision' => 'badge bg-danger',
                                                default => 'badge bg-secondary',
                                            };
                                        ?>
                                        <span class="<?php echo $status_class; ?>"><?php echo ucfirst($doc['status']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($doc['created_at'])); ?></td>
                                    <td><?php echo date('M d, Y h:i A', strtotime($doc['updated_at'])); ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-success">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                This organization (<?php echo $organization_name; ?>) has no documents on file.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>