<?php
include '../../config/dbcon.php'; // Adjust the path as needed

// Fetch all organizations to display in the table and the dropdown
$sql = "SELECT organization_id, name, logo FROM organizations";
$result = $conn->query($sql);
$organizations = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Organizations</title>
    <link rel="stylesheet" href="../../assets/alertifyjs/css/alertify.min.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <script src="../../assets/alertifyjs/alertify.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Update Organization Logo</h2>
        
        <form id="logoUpdateForm" action="process_logo_update.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="organization_id" class="form-label">Select Organization</label>
                <select class="form-select" id="organization_id" name="organization_id" required>
                    <option value="">-- Choose Organization --</option>
                    <?php foreach ($organizations as $org): ?>
                        <option value="<?php echo htmlspecialchars($org['organization_id']); ?>">
                            <?php echo htmlspecialchars($org['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="logoFile" class="form-label">Upload New Logo</label>
                <input type="file" class="form-control" id="logoFile" name="logoFile" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Logo</button>
        </form>

        <hr>

        <h3 class="mt-5">Current Organizations</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Current Logo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($organizations as $org): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($org['organization_id']); ?></td>
                        <td><?php echo htmlspecialchars($org['name']); ?></td>
                        <td>
                            <?php if ($org['logo']): ?>
                                <img src="<?php echo htmlspecialchars($org['logo']); ?>" alt="<?php echo htmlspecialchars($org['name']); ?> Logo" style="height: 50px;">
                            <?php else: ?>
                                No Logo
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#logoUpdateForm').on('submit', function(e) {
                e.preventDefault();
                
                // Use FormData to handle file uploads via AJAX
                const formData = new FormData(this);

                $.ajax({
                    url: 'process_logo_update.php',
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevents jQuery from processing the data
                    contentType: false, // Prevents jQuery from setting the content type
                    success: function(response) {
                        if (response.success) {
                            alertify.success(response.message);
                            setTimeout(() => {
                                window.location.reload(); // Reload to show the new logo
                            }, 1000);
                        } else {
                            alertify.error(response.message);
                        }
                    },
                    error: function() {
                        alertify.error("An error occurred. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>