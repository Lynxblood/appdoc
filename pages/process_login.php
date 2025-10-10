<?php
include '../config/dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, password_hash, user_role, first_name, organization_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['organization_id'] = $user['organization_id'];

            header("Location: ../config/redirect.php");
            exit();
        } else {
            $_SESSION['message'] = 'Wrong Password!';
            $_SESSION['msgtype'] = "error";
            $_SESSION['havemsg'] = true;
            
            header("Location: ../index.php");
        }
    } else {
        $_SESSION['message'] = 'No user found with that email.';
        $_SESSION['msgtype'] = "error";
        $_SESSION['havemsg'] = true;
        
        header("Location: ../index.php");
    }

    $stmt->close();
    $conn->close();
}
?>