<?php
session_start();
// Check if the user is logged in and is an organization user
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['academic_organization', 'non_academic_organization'])) {
    header("Location: login.php");
    exit();
}
include '../config/dbcon.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Event</title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        .form-group { margin-bottom: 1em; }
        label { display: block; margin-bottom: 0.5em; font-weight: bold; }
        input[type="text"], input[type="datetime-local"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .container { max-width: 800px; margin: auto; }
        button { padding: 10px 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Event</h2>
        <form action="process_event.php" method="post">
            <div class="form-group">
                <label for="title">Event Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Event Description:</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date and Time:</label>
                <input type="datetime-local" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date and Time:</label>
                <input type="datetime-local" id="end_date" name="end_date" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="total_expenses">Total Expenses (Optional):</label>
                <input type="text" id="total_expenses" name="total_expenses">
            </div>
            <button type="submit">Create Event</button>
        </form>
    </div>
</body>
</html>