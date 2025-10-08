<?php
$conn = new mysqli("localhost", "root", "", "orgs_db");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Delete the organization from the pending list if rejected
    $sql = "DELETE FROM organizations WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the list of pending organizations after rejection
        header("Location: org.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>