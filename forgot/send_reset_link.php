<?php
// Include the PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mail/PHPMailer.php';
// You'll typically need to include the SMTP class as well if you use SMTP
require 'mail/SMTP.php';
require 'mail/Exception.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    
    $email = $_POST['email'];

    // 1. --- Security & Database Check (Conceptual) ---
    
    // In a real application:
    // a) Check if the $email exists in your 'users' table.
    // b) Generate a unique token, e.g., $token = bin2hex(random_bytes(32));
    // c) Store this $token in a 'password_resets' table along with the user ID and an expiry timestamp.
    
    // For this simple example, we'll use a placeholder token.
    $token = "UNIQUE_RESET_TOKEN_12345"; 
    $resetLink = "http://yourwebsite.com/reset_password.php?token=" . $token; // Replace with your actual domain/page

    // 2. --- PHPMailer Configuration ---

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.example.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;    
        $mail->Username = 'pdaosmb@gmail.com';  // Sender's email
        $mail->Password = 'wzve xetg zdmw fidq';  // SMTP password (App Password recommended)// SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('no-reply@yourwebsite.com', 'Your Website Name');
        $mail->addAddress($email);                                  // Add a recipient

        //Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Dear User,<br><br>
                         You requested a password reset. Click the link below to reset your password:<br>
                         <a href='$resetLink'>$resetLink</a><br><br>
                         If you did not request this, please ignore this email.";
        $mail->AltBody = "You requested a password reset. Copy and paste the following link into your browser: $resetLink";

        $mail->send();
        
        // Redirect back to the form with a success message
        header("Location: forgot.php?status=success");
        exit();

    } catch (Exception $e) {
        // If the email failed to send (e.g., SMTP error)
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        
        // Redirect back to the form with an error message
        header("Location: forgot.php?status=error");
        exit();
    }
} else {
    // If the file was accessed directly without POST data
    header("Location: forgot.php");
    exit();
}