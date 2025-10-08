<?php
$conn = new mysqli("localhost", "root", "", "orgs_db"); // adjust if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orgName = $_POST['org_name'];
$imageName = basename($_FILES["org_logo"]["name"]);
$targetDir = "../img";
$targetPath = $targetDir . $imageName;

if (move_uploaded_file($_FILES["org_logo"]["tmp_name"], $targetPath)) {
    $stmt = $conn->prepare("INSERT INTO organizations (name, logo_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $orgName, $targetPath);

    if ($stmt->execute()) {
        echo "<script>
            alert('Organization created! Please wait for approval.');
            window.location.href = '../index.php'; 
        </script>";
    }else {
        echo "Database error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Image upload failed.";
}

$conn->close();
?>