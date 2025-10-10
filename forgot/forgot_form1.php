<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/alertifyjs/css/alertify.min.css">
    <script src="../assets/alertifyjs/alertify.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <link rel="stylesheet" href="../assets/output.css">
    <!-- <link rel="icon" type="image/x-icon" href="../assets/img/pdao-logo.png"> -->
</head>
<?php 
    require '../config/dbcon.php';
?>
<body>
    
    <div class="loader"></div>
    <div class="parent flex flex-col sm:flex-row py-4 h-[100vh] items-center justify-center sm:justify-start text-gray-900">
        <div class="bg-filter"></div>
        <div class="card glassbg p-6 sm:p-8 mx-2 sm:ms-16 border rounded-xl relative">
            <a href="../index.php" class="backbtn absolute top-0 right-0 p-5">
                <div class="sr-only">Back to mainpage</div>
                <svg class="w-8 h-8 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>
            </a>
            <div class="container flex items-center">
                <img src="../assets/img/pdao-logo-new.jpg" class="rounded-full w-12" alt="">
                <h1 class="text-3xl ms-[20px] ">PDAO</h1>
            </div>
            <div class="container mt-4">
                <h1 class="text-3xl font-bold ">Forgot password</h1>
                <p class="mt-3">Reset your password in seconds. Have an account? <a href="../public/newlogin.php" ><span class="text-[--greener] font-bold">Login</span></a></p>
            </div>
            <div class="row">
                <form class="text-start mt-9" action="" method="post">
                    <div class="flex justify-around items-center sm:gap-4 flex-col sm:flex-row">
                        <div class="mb-5 w-full">
                            <label for="resetemail" class="block mb-2 text-sm font-medium">Email</label>
                            <input name="resetemail" type="email" id="resetemail" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[--greener] focus:border-[--greener] block w-full p-2.5 " placeholder="sample@gmail.com" required />
                        </div>
                    </div>
                    
                    <div class="flex mb-2 gap-1">
                        <p class="text-xs text-[--greener] font-bold">Didn't recieve email?</p>
                        <button class="text-xs text-[--greener]" id="resendBtn" onclick="timerRes(this)"><span>Resend</span></button> <span class="" id="resendTimer"></span>
                    </div>
                    <button type="submit" name="resetbtn" class="pdao-greenbtn w-full sm:w-auto">Reset Password</button>
                </form>
            </div>
        </div>
        </div>
    </div>


    <script src="../assets/jquery/jquery-3.7.1.min.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <!-- <script src="https://apis.google.com/js/api:client.js"></script> -->
    <script src="../assets/newapp.js"></script>
    <script>
        // const email =document.getElementById('email');
        // email.focus();
        // email.select();
        // function timerRes(sendEmail){
        //     // sendEmail.classList.add('d-none');
        //     // const resetemail = document.getElementById('resetemail');
        //     const resendBtn = document.getElementById('resendBtn');
        //     const resendTimer = document.getElementById('resendTimer');
        //     resendTimer.classList.remove('d-none');
        //     var cooldown = 2000 * 60
        //     var countDownDate = new Date().getTime();

        //     var x = setInterval(function() {

        //         var now = new Date().getTime();

        //         var distance = (countDownDate+cooldown) - now;

        //         var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        //         var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        //         resendTimer.innerHTML = "Resend " + minutes + "m " + seconds + "s ";

        //         if (distance < 0) {
        //             clearInterval(x);
        //             resendTimer.innerHTML ="0m 0s";
        //             resendTimer.classList.add('hidden');
        //             resendBtn.classList.remove('hidden');
        //         }else{
        //             resendBtn.classList.add('hidden');
        //         }
        //     }, 1000);
        // }
    </script>
    <?php 
    
        // $conn = mysqli_connect("localhost", "root", "", "morepdao");
        
        //         use PHPMailer\PHPMailer\PHPMailer;
        //         use PHPMailer\PHPMailer\SMTP;
        //         use PHPMailer\PHPMailer\Exception;

        //         require 'mail/Exception.php';
        //         require 'mail/PHPMailer.php';
        //         require 'mail/SMTP.php';

        // if(isset($_POST['resetbtn'])){
        //     $resetemail = mysqli_real_escape_string($conn, $_POST['resetemail']);
        //     $checkemail = mysqli_query($conn, "SELECT * FROM user_account WHERE email='$resetemail' LIMIT 1");
        //     if(mysqli_num_rows($checkemail)==0){
        //         echo "<script>alertify.error('User not found!!');</script>";
        //     }else{
        //         $resetemail = mysqli_real_escape_string($conn, $_POST['resetemail']);
        //         $result = mysqli_fetch_assoc($checkemail);
    
        //         // Instantiation and passing `true` enables exceptions
        //         $mail = new PHPMailer(true);
    
        //         try {
        //             //Server settings
        //             $mail->isSMTP();                                            // Send using SMTP
        //             $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        //             $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        //             $mail->Username   = 'pdaosmb@gmail.com';                     // SMTP username
        //             $mail->Password   = 'ngxl oudo poic ajai';                               // SMTP password
        //             $mail->SMTPSecure = 'tls';       // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        //             $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
        //             //Recipients
        //             $mail->setFrom('your_email@gmail.com', 'PDAO San Miguel Bulacan');
        //             $mail->addAddress($resetemail);     // Add a recipient
    
        //             $code = substr(str_shuffle('1234567890QWERTYUIOPASDFGHJKLZXCVBNM'), 0, 10);
    
        //             // Content <a href="http://localhost:3000/forgot/confirm_code.php?code=' . $code . '">here </a>
        //             $mail->isHTML(true);
        //             $mail->Subject = 'Password Reset Request';
        //             $mail->Body    = '
        //             <div style="text-align:center;">
        //                 <div class="header" style="color:#fff;background:#82CD47;padding:20px 10px;"><h1 style="font-weight:bold;">PDAO San Miguel Bulacan</h1></div>
        //                 <div class="body" style="background:#fff;padding:5vh 5vw 2vh 5vw;display:grid;place-items:center;">
        //                     <h1 style="margin-bottom:3vh;display:grid;place-items:center;">Important Notice Regarding Reseting Password to your Account</h1>
        //                     <p style="text-align:left;">
        //                         <span style="font-size:15px;">Dear user,</span><br>
        //                         <br>
        //                         We recieved a request to reset your password for your PWD registration account. If you initiated this request, please click the link below to create a new password:<br>
        //                         <a href="http://localhost:3000/forgot/confirm_code.php?code='.$code.'&'.'reid='.$result['user_id'].'"><button style="text-decoration:none;color:#000;text-align:left;margin:10px 0;padding:8px 4px;border-radius:5px;box-shadow:1px 2px 3px gray;background:#F0FF42;border:none;">Click here to Reset Password</button></a><br>
        //                         For your reference, your account information is as follows:<br>
        //                         <br>
        //                         1. Email Address: &nbsp;<a style="text-decoration:none;color:#379237;">'.$result['email'].'</a><br>
        //                         <br>
        //                         The link provided will serve as ONE TIME USE for password reset, Please be sure to use and make your easy to remember password.<br>
        //                         Thank you for your time and Do not reply to this, as it serves as Notification only!.<br>
        //                         <br>
        //                         Sincerely,<br>
        //                         PDAO San Miguel<br>
        //                     </p>
        //                 </div>
        //                 <div class="foot" style="color:#fff;background:#82CD47;padding:5px;"><h2>Visit us at the PDAO of San Miguel Bulacan</h2></div>
        //             </div>';
    
        //             // $conn = new mySqli('localhost', 'root', '', 'pdao_pims');
    
        //             if ($conn->connect_error) {
        //                 die('Could not connect to the database.');
        //             }
    
        //             $verifyQuery = $conn->query("SELECT * FROM user_account WHERE email = '$resetemail'");
    
        //             if ($verifyQuery->num_rows) {
        //                 $codeQuery = $conn->query("UPDATE user_account SET code = '$code' WHERE email = '$resetemail'");
    
        //                 $mail->send();
        //                 echo "<script>alertify.success('Email sent Successfully!');</script>";
        //                 echo '<script>timerRes();</script>';
        //             }
        //             $conn->close();
        //         } catch (Exception $e) {
        //             echo "<script>console.log('"+$e+"');</script>";
        //         }
        //     }
        // }

        $conn = new mysqli("localhost", "u692369044_pdaostaff", "Pdao@123", "u692369044_pimspdao");
        
        // $conn = mysqli_connect("localhost", "root", "", "u692369044_morepdao");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\SMTP;
            use PHPMailer\PHPMailer\Exception;

            require 'mail/Exception.php';
            require 'mail/PHPMailer.php';
            require 'mail/SMTP.php';

            if (isset($_POST['resetbtn'])) {
                $resetemail = mysqli_real_escape_string($conn, $_POST['resetemail']);
                // $checkemail = mysqli_query($conn, "SELECT * FROM user_account WHERE email='$resetemail' LIMIT 1");
                $checkemail = mysqli_query($conn, "SELECT COALESCE(rep.representative_fname, pwd.pwd_fname) AS name, ua.* FROM user_account ua LEFT JOIN representative rep ON ua.user_id = rep.user_id LEFT JOIN pwd ON ua.user_id = pwd.user_id WHERE ua.email = '$resetemail';");

                if (mysqli_num_rows($checkemail) == 0) {
                    echo "<script>alertify.error('User not found!!');</script>";
                } else {
                    $result = mysqli_fetch_assoc($checkemail);

                    // Instantiate PHPMailer
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'pdaosmb@gmail.com';  // Sender's email
                        $mail->Password = 'wzve xetg zdmw fidq';  // SMTP password (App Password recommended)
                        $mail->SMTPSecure = 'tls';  
                        $mail->Port = 587;

                        // Recipients
                        $mail->setFrom('pdaosmb@gmail.com', 'PDAO San Miguel Bulacan');
                        $mail->addAddress($resetemail);

                        $code = substr(str_shuffle('1234567890QWERTYUIOPASDFGHJKLZXCVBNM'), 0, 10);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Request';
                        $mail->Body    = '
                                    <div style="text-align:center;">
                                        <div class="header" style="color:#fff;background:#82CD47;padding:20px 10px;"><h1 style="font-weight:bold;">PDAO San Miguel Bulacan</h1></div>
                                        <div class="body" style="background:#fff;padding:5vh 5vw 2vh 5vw;display:grid;place-items:center;">
                                            <h1 style="margin-bottom:3vh;display:grid;place-items:center;">Important Notice Regarding Reseting Password to your Account</h1>
                                            <p style="text-align:left;">
                                                <span style="font-size:15px;">Dear '.$result['name'].',</span><br>
                                                <br>
                                                We recieved a request to reset your password for your PWD registration account. If you initiated this request, please click the link below to create a new password:<br>
                                                <a href="https://smbpdao.com/forgot/confirm_code1.php?cqwd='.$code.'&'.'reid='.$result['user_id'].'"><button style="text-decoration:none;color:#000;text-align:left;margin:10px 0;padding:8px 4px;border-radius:5px;box-shadow:1px 2px 3px gray;background:#F0FF42;border:none;">Click here to Reset Password</button></a><br>
                                                For your reference, your account information is as follows:<br>
                                                <br>
                                                1. Email Address: &nbsp;<a style="text-decoration:none;color:#379237;">'.$result['email'].'</a><br>
                                                <br>
                                                The link provided will serve as ONE TIME USE for password reset, Please be sure to use and make your easy to remember password.<br>
                                                Thank you for your time and Do not reply to this, as it serves as Notification only!.<br>
                                                <br>
                                                Sincerely,<br>
                                                PDAO San Miguel<br>
                                                pdaosmb@gmail.com
                                            </p>
                                        </div>
                                        <div class="foot" style="color:#fff;background:#82CD47;padding:5px;"><h2>Visit us at the PDAO of San Miguel Bulacan</h2></div>
                                    </div>';

                        // Update code in database
                        if ($conn->query("UPDATE user_account SET code = '$code' WHERE email = '$resetemail'")) {
                            $mail->send();
                            echo "<script>alertify.success('Email sent Successfully!');</script>";
                            // echo '<script>timerRes();</script>';
                        } else {
                            echo "<script>alertify.error('Failed to update the reset code in the database.');</script>";
                        }
                    } catch (Exception $e) {
                        // This will properly encode the exception message to be safely logged in JavaScript
                        echo "<script>console.log('".addslashes($e->getMessage())."');</script>";
                    }
                    

                    $conn->close();
                }
            }

    ?>
</body>
</html>