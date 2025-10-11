<?php 
require '../config/dbcon.php';

// Start session to handle success message redirect
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$error_message = ""; // Variable to hold any server-side errors

// --- PHP Password Update Logic ---
if (isset($_POST['sub_newpass'])) {
    
    // Check if the required code and email parameters are present in the URL
    if (!empty($_GET['code']) && !empty($_GET['email'])) {
        
        $code = mysqli_real_escape_string($conn, $_GET['code']);
        $email = mysqli_real_escape_string($conn, $_GET['email']);
        $newpass = mysqli_real_escape_string($conn, $_POST['newpass']);
        $conpass = mysqli_real_escape_string($conn, $_POST['conpass']);

        if ($newpass === $conpass) {
            
            // Check if the reset code and email combination is valid
            // IMPORTANT: Updated SELECT query to use the correct password column name ('password_hash' is assumed from your update query)
            $check_user = mysqli_query($conn, "SELECT password_hash FROM users WHERE reset_code='$code' AND email='$email' LIMIT 1");

            if (mysqli_num_rows($check_user) > 0) {
                
                // Hash the new password and clear the reset code
                $password_hash = password_hash($newpass, PASSWORD_DEFAULT);
                $update_sql = mysqli_query($conn, "UPDATE users SET password_hash='$password_hash', reset_code=NULL WHERE email='$email'");

                if ($update_sql) {
                    // Set session message for successful password change
                    $_SESSION['message'] = "Password updated successfully! You can now log in.";
                    $_SESSION['msgtype'] = "success";
                    $_SESSION['havemsg'] = true;
                    
                    // Redirect to the login page
                    header('Location: ../index.php');
                    exit();
                } else {
                    $error_message = "Failed to update password! Database error occurred.";
                }
            } else {
                $error_message = "Invalid or expired reset link. Please request a new link.";
            }
        } else {
            $error_message = "Passwords do not match! Please ensure both fields are identical.";
        }
    } else {
        $error_message = "The reset link is incomplete or invalid. Please check your email.";
    }
}
// --- END PHP Password Update Logic ---

// Check if required GET parameters are missing on initial load
if (empty($_GET['code']) || empty($_GET['email'])) {
    $error_message = "Access Denied: Reset token is missing. Please use the link provided in your email.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* CSS uses the color #355f2e for branding */
        :root {
            --primary-color: #355f2e;
            --secondary-color: #274821; /* Darker shade for hover */
            --shadow-color: rgba(53, 95, 46, 0.2);
        }

        /* General Setup */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
            min-height: 100vh;
        }

        /* Card Container (Responsive) */
        .container {
            width: 90%;
            max-width: 450px;
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Headings and Text */
        h2 {
            color: #333;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .instruction-text {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        /* Form Styling */
        form {
            text-align: left;
        }
        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--shadow-color);
        }

        /* Submit Button Styling */
        .submit-button {
            width: 100%;
            padding: 12px 20px;
            background-color: var(--primary-color);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 5px; 
            margin-bottom: 15px;
        }

        .submit-button:hover {
            background-color: var(--secondary-color);
        }
        
        /* Error Message Display */
        .error-message {
            background-color: #ffebee; 
            color: #D32F2F; /* Dark red text */
            border: 1px solid #F44336;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-weight: 500;
            text-align: left;
        }

        /* Back Link Styling */
        .back-link-container {
            text-align: center;
        }

        .back-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: color 0.3s;
            display: inline-block;
        }

        .back-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <h2>Reset Your Password</h2>
        <p class="instruction-text">Enter and confirm a new password for your account.</p>

        <?php 
        // Display server-side error message if set
        if (!empty($error_message)) {
            echo '<div class="error-message">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>

        <!-- If an error occurred preventing parameter access, the form should not be shown -->
        <?php if (empty($error_message) || $error_message !== "Access Denied: Reset token is missing. Please use the link provided in your email."): ?>
            <form action="" method="post">
                
                <div class="form-group">
                    <label for="newpass">New Password:</label>
                    <input type="password" name="newpass" id="newpass" required>
                </div>

                <div class="form-group">
                    <label for="conpass">Confirm Password:</label>
                    <input type="password" name="conpass" id="conpass" required>
                </div>

                <button type="submit" name="sub_newpass" class="submit-button">
                    Set New Password
                </button>
            </form>
        <?php endif; ?>

        <div class="back-link-container">
            <!-- Link back to the main login page -->
            <a href="../index.php" class="back-link">← Back to Login</a>
        </div>

    </div>

</body>
</html>
