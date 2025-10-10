<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>

    <h2>Forgot Password</h2>
    <p>Enter your email address to receive a password reset link.</p>

    <?php
    // Display any messages (e.g., success or error from send_reset_link.php)
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') {
            echo '<p style="color: green;">A password reset link has been sent to your email address.</p>';
        } elseif ($_GET['status'] == 'error') {
            echo '<p style="color: red;">Error: Could not send the reset link. Please try again.</p>';
        }
    }
    ?>

    <form action="send_reset_link.php" method="POST">
        <label for="email">Email Address:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Reset Password">
    </form>

</body>
</html>