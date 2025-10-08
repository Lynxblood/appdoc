<?php
session_start();
include '../config/dbcon.php';

// Check for valid request and user session
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['academic_organization', 'non_academic_organization'])) {
    die("Invalid request.");
}

$user_id = $_SESSION['user_id'];
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$location = trim($_POST['location']);
$total_expenses = $_POST['total_expenses'] ? $_POST['total_expenses'] : null;

// Validate required fields
if (empty($title) || empty($description) || empty($start_date) || empty($end_date) || empty($location)) {
    die("Please fill out all required fields.");
}

// Get organization_id from the session
$sql_org = "SELECT organization_id FROM users WHERE user_id = ?";
$stmt_org = $conn->prepare($sql_org);
$stmt_org->bind_param("i", $user_id);
$stmt_org->execute();
$org_result = $stmt_org->get_result();
$organization_id = $org_result->fetch_assoc()['organization_id'];
$stmt_org->close();

if (empty($organization_id)) {
    die("Error: Unable to find organization for this user.");
}

// Prepare and execute the SQL query to insert the new event
$sql_insert = "INSERT INTO events (organization_id, title, description, start_date, end_date, location, total_expenses, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("issssss", $organization_id, $title, $description, $start_date, $end_date, $location, $total_expenses);

if ($stmt->execute()) {
    echo "Event created successfully!";
    header("Location: org_dashboard.php?event_created=success");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>