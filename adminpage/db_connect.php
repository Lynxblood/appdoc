<?php
$servername = "localhost";
$username = "root"; // CHANGE THIS
$password = ""; // CHANGE THIS
$dbname = "stud_org_gemini"; // CHANGE THIS (Or whatever you name your database)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>