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
            <div style="font-family: \'Inter\', Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #F9FAFB; padding: 40px 20px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center">
                            <table width="600" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
                                
                                <!-- Header -->
                                <tr>
                                    <td align="left" style="background-color: #0F4C3C; padding: 25px 40px;">
                                        <h1 style="color: #ffffff; font-weight: 700; margin: 0; font-size: 26px;">
                                            Password Reset Request
                                        </h1>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding: 40px 40px 30px 40px; text-align: left;">
                                        <p style="margin: 0 0 15px 0; font-size: 16px;">Hello,</p>
                                        
                                        <p style="margin: 0 0 30px 0; font-size: 16px; color: #555555;">
                                            We\'ve received a request to change the password for your account. To proceed and complete the reset process, please click the button below:
                                        </p>
                                        
                                        <table border="0" cellspacing="0" cellpadding="0" style="margin: 30px 0; width: 100%;">
                                            <tr>
                                                <td align="center">
                                                    <a href="' . $useURL . 'forgot/confirm_code.php?code=' . $code . '&email=' . urlencode($user["email"]) . '"
                                                    target="_blank" 
                                                    style="display: inline-block; padding: 10px 24px; font-size: 18px; color: #ffffff; background-color: #6BBA70; border-radius: 8px; text-decoration: none; font-weight: bold;">
                                                        Set New Password
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin-top: 30px; font-size: 16px;">
                                            Thank you,<br><strong style="color: #0F4C3C;">Bulacan Agricultural State College Support</strong>
                                        </p>

                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 40px; background-color: #E8F4E8; border-radius: 8px; border: 1px solid #DCE4DC;">
                                            <tr>
                                                <td style="padding: 20px; text-align: left;">
                                                    <p style="margin: 0 0 5px 0; font-size: 14px; color: #0F4C3C; font-weight: 700;">Security Notice</p>
                                                    <p style="margin: 0; font-size: 13px; color: #555555;">
                                                        This link is for one-time use and will expire shortly. If you did not request this password reset, please ignore this email. Your password will remain unchanged.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center" style="background-color: #0F4C3C; color: #ffffff; padding: 15px 40px; font-size: 12px; opacity: 0.8;">
                                        <p style="margin: 0;">Bulacan Agricultural State College | Secure Communication</p>
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
