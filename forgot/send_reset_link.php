<?php
include '../config/dbcon.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mail/Exception.php';
require 'mail/PHPMailer.php';
require 'mail/SMTP.php';

if (isset($_POST['resetbtn'])) {
    $resetemail = mysqli_real_escape_string($conn, $_POST['resetemail']);

    // Query the users table
    $checkemail = $conn->query("SELECT user_id, email FROM users WHERE email = '$resetemail' LIMIT 1");

    if ($checkemail->num_rows === 0) {
        echo "<script>alertify.error('User not found!');</script>";
        exit;
    }

    $user = $checkemail->fetch_assoc();

    // Generate reset code
    $code = substr(str_shuffle('1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);

    // Update reset code in the database
    if (!$conn->query("UPDATE users SET reset_code = '$code' WHERE email = '$resetemail'")) {
        echo "<script>alertify.error('Failed to update reset code in database.');</script>";
        exit;
    }

    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bascdocument@gmail.com';  // Sender email
        $mail->Password = 'czih audl hjdu nbaj';    // App password (not your real Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('bascdocument@gmail.com', 'BASC Document');
        $mail->addAddress($user['email']);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = '
        <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center">
                    <table width="600" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        
                        <tr>
                            <td align="center" style="background-color: #4CAF50; padding: 30px 20px;">
                                <h1 style="color: #ffffff; font-weight: bold; margin: 0; font-size: 24px;">Password Reset Request</h1>
                            </td>
                        </tr>
                        
                        <tr>
                            <td style="padding: 40px 30px 30px 30px; text-align: left;">
                                <p style="margin: 0 0 15px 0;">Hello,</p>
                                <p style="margin: 0 0 25px 0;">We received a request to reset your password. Click the button below to set a new one:</p>
                                
                                <table border="0" cellspacing="0" cellpadding="0" style="margin: 25px 0; width: 100%;">
                                    <tr>
                                        <td align="left">
                                            <a href="'. $useURL .'forgot/confirm_code.php?code='.$code.'&email='.urlencode($user['email']).'"
                                               target="_blank" 
                                               style="display: inline-block; padding: 12px 25px; font-size: 16px; color: #ffffff; background-color: #FFC107; border-radius: 5px; text-decoration: none; font-weight: bold;">
                                                Reset Password
                                            </a>
                                        </td>
                                    </tr>
                                </table>
    
                                <p style="margin: 25px 0 15px 0;"><small>This link is valid for one-time use only.</small></p>
                                <p style="margin: 0;">Thank you,<br><strong>BASC Document</strong></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="center" style="background-color: #4CAF50; color: #ffffff; padding: 20px; border-top: 1px solid #e0e0e0;">
                                <p style="margin: 0; font-size: 14px;">Visit us at the Bulacan Agricultural State College</p>
                            </td>
                        </tr>
    
                    </table>
                </td>
            </tr>
        </table>
        </div>';

        // Send the email
        $mail->send();
        header('Location: forgot.php?status=success'); 
        exit;

    } catch (Exception $e) {
        
        header('Location: forgot.php?status=error');
        // Log the error to the console for debugging
        error_log("PHPMailer Error: " . $e->getMessage());
        exit;
    }

    $conn->close();
}
?>
