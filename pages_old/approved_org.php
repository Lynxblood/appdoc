<?php
$conn = new mysqli("localhost", "root", "", "orgs_db");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Update the organization to be approved
    $sql = "UPDATE organizations SET approved = 1 WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the list of pending organizations after approval
        header("Location: org.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>