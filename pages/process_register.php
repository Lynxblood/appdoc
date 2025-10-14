<?php
include '../config/dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user_role = $_POST['user_role'];
    
    $organization_id = NULL; 
    if ($user_role == 'academic_organization' || $user_role == 'non_academic_organization' || $user_role == 'adviser') {
        $organization_id = $_POST['organization_id'] ?? NULL;
        if (empty($organization_id)) {
            die("Organization must be selected for this user role.");
        }
    }

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($user_role)) {
        die("Please fill out all required fields.");
    }

    $sql_check = "SELECT user_id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
        $_SESSION['message'] = 'Email already registered. Please use a different email';
        $_SESSION['msgtype'] = "error";
        $_SESSION['havemsg'] = true;
        header("Location: register.php");
        exit();
    }
    $stmt_check->close();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // --- Modified Code to create a shorter base signature code ---
    
    // Generate a shorter, unique identifier.
    // This creates a 5-character random string (a-z, 0-9)
    $short_random_string = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 5);
    
    // Combine with a user-specific detail like the first few letters of their email
    $user_initials = substr(str_replace(['@', '.'], '', $email), 0, 3);
    
    // The final base code is a combination of these elements
    $signature_base_code = strtoupper($user_initials . $short_random_string);

    // --- End of Modified Code ---

    $sql_insert = "INSERT INTO users (first_name, last_name, email, password_hash, user_role, organization_id, signature_base_code, rank_id) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
    $stmt_insert = $conn->prepare($sql_insert);
    if ($stmt_insert === false) {
        $_SESSION['message'] = 'Error preparing statement:' . $conn->error;
        $_SESSION['msgtype'] = "error";
        $_SESSION['havemsg'] = true;
        header("Location: register.php");
        exit();
    }
    
    $stmt_insert->bind_param("sssssis", $first_name, $last_name, $email, $password_hash, $user_role, $organization_id, $signature_base_code);

    if ($stmt_insert->execute()) {
        $_SESSION['message'] = 'Registration successful';
        $_SESSION['msgtype'] = "success";
        $_SESSION['havemsg'] = true;
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['message'] = 'Registration error:' . $stmt_insert->error;
        $_SESSION['msgtype'] = "error";
        $_SESSION['havemsg'] = true;
        header("Location: register.php");
        exit();
    }
    
    $stmt_insert->close();
    $conn->close();
} else {
    header("Location: register.php");
    exit();
}
?>