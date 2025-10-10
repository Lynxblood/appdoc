<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/DataTables/datatables.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/alertifyjs/css/alertify.min.css">
    <script src="../assets/alertifyjs/alertify.min.js"></script>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/pdao-logo.png">
</head>
<body>
<div class="loader"></div>
    <div class="container-fluid formcontent">
        <div class="row justify-content-start ">
            <!-- <div class="col col-md-8"></div> -->
            <div class="col col-md-4 ps-5 pe-2 samplelogin d-flex align-items-center position-relative">
            <a href="../index.php" class="p-3 text-center position-absolute top-0 end-0 fs-3 forgot"><i class="bi bi-arrow-left"></i></a>
                <form action="" method="post" class="w-100 p-4 d-flex flex-column gap-3 ">
                    <h1 class="text-light">FORGOT</h1>
                    <p class="lead text-light fs-6 fw-bold">Don't have account?&nbsp;<a href="../public/register.php" class="forgot" style="text-decoration:none;">Register Here</a></p>
                    <div class="">
                        <label for="email" class="form-label text-light">Email</label>
                        <input type="Email" class="form-control" id="resetemail" name="resetemail" placeholder="sample@gmail.com">
                    </div>
                    <input type="submit" class="btn btn-success mt-3 fw-bold" name="resetbtn" value="Reset Password">
                    <div class="text-center text-light">
                        <p class=" p-0 m-0">Didn't recieve email?</p>
                        <a class="text-decoration-none text-light p-0 m-0" id="resendBtn" onclick="timerRes(this)"><span>Resend</span></a> <span class="" id="resendTimer"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/jquery/jquery-3.7.1.min.js"></script>
    <script src="../assets/DataTables/datatables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/app.js"></script>
    <script>
        // const email =document.getElementById('email');
        // email.focus();
        // email.select();
        function timerRes(sendEmail){
            // sendEmail.classList.add('d-none');
            // const resetemail = document.getElementById('resetemail');
            const resendBtn = document.getElementById('resendBtn');
            const resendTimer = document.getElementById('resendTimer');
            resendTimer.classList.remove('d-none');
            var cooldown = 2000 * 60
            var countDownDate = new Date().getTime();

            var x = setInterval(function() {

                var now = new Date().getTime();

                var distance = (countDownDate+cooldown) - now;

                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                resendTimer.innerHTML = "Resend " + minutes + "m " + seconds + "s ";

                if (distance < 0) {
                    clearInterval(x);
                    resendTimer.innerHTML ="0m 0s";
                    resendTimer.classList.add('d-none');
                    resendBtn.classList.remove('d-none');
                }else{
                    resendBtn.classList.add('d-none');
                }
            }, 1000);
        }
    </script>
    <?php 
    
        $conn = mysqli_connect("localhost", "root", "", "u692369044_morepdao");
        // $conn = mysqli_connect("localhost", "u692369044_adminpdao", "PDAOsmb@2024", "u692369044_morepdao");
        
                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\SMTP;
                use PHPMailer\PHPMailer\Exception;

                require 'mail/Exception.php';
                require 'mail/PHPMailer.php';
                require 'mail/SMTP.php';

        if(isset($_POST['resetbtn'])){
            $resetemail = mysqli_real_escape_string($conn, $_POST['resetemail']);
            $checkemail = mysqli_query($conn, "SELECT *, CONCAT(fname,' ',mname,' ', lname) as fullname FROM registrant WHERE email='$resetemail' LIMIT 1");
            if(mysqli_num_rows($checkemail)==0){
                echo "<script>alertify.error('User not found!!');</script>";
            }else{
                $resetemail = mysqli_real_escape_string($conn, $_POST['resetemail']);
                $result = mysqli_fetch_assoc($checkemail);
    
                // Instantiation and passing `true` enables exceptions
                $mail = new PHPMailer(true);
    
                try {
                    //Server settings
                    $mail->isSMTP();                                            // Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                    $mail->Username   = 'pdaosmb@gmail.com';                     // SMTP username
                    $mail->Password   = 'ngxl oudo poic ajai';                               // SMTP password
                    $mail->SMTPSecure = 'tls';       // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
                    //Recipients
                    $mail->setFrom('your_email@gmail.com', 'PDAO San Miguel Bulacan');
                    $mail->addAddress($resetemail);     // Add a recipient
    
                    $code = substr(str_shuffle('1234567890QWERTYUIOPASDFGHJKLZXCVBNM'), 0, 10);
    
                    // Content <a href="http://localhost:3000/forgot/confirm_code.php?code=' . $code . '">here </a>
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body    = '
                    <div style="text-align:center;">
                        <div class="header" style="color:#fff;background:#82CD47;padding:20px 10px;"><h1 style="font-weight:bold;">PDAO San Miguel Bulacan</h1></div>
                        <div class="body" style="background:#fff;padding:5vh 5vw 2vh 5vw;display:grid;place-items:center;">
                            <h1 style="margin-bottom:3vh;display:grid;place-items:center;">Important Notice Regarding Reseting Password to your Account</h1>
                            <p style="text-align:left;">
                                <span style="font-size:15px;">Dear '.$result['fname'].',</span><br>
                                <br>
                                We recieved a request to reset your password for your PWD registration account. If you initiated this request, please click the link below to create a new password:<br>
                                <a href="http://localhost:3000/forgot/confirm_code.php?code='.$code.'&'.'reid='.$result['registrant_id'].'"><button style="text-decoration:none;color:#000;text-align:left;margin:10px 0;padding:8px 4px;border-radius:5px;box-shadow:1px 2px 3px gray;background:#F0FF42;border:none;">Click here to Reset Password</button></a><br>
                                For your reference, your account information is as follows:<br>
                                <br>
                                1. Full Name: &nbsp;<span style="text-transform:capitalize;">'.$result['fullname'].'</span><br>
                                2. Email Address: &nbsp;<a style="text-decoration:none;color:#379237;">'.$result['email'].'</a><br>
                                <br>
                                The link provided will serve as ONE TIME USE for password reset, Please be sure to use and make your easy to remember password.<br>
                                Thank you for your time and Do not reply to this, as it serves as Notification only!.<br>
                                <br>
                                Sincerely,<br>
                                PDAO San Miguel<br>
                            </p>
                        </div>
                        <div class="foot" style="color:#fff;background:#82CD47;padding:5px;"><h2>Visit us at the PDAO of San Miguel Bulacan</h2></div>
                    </div>';
    
                    // $conn = new mySqli('localhost', 'root', '', 'pdao_pims');
    
                    if ($conn->connect_error) {
                        die('Could not connect to the database.');
                    }
    
                    $verifyQuery = $conn->query("SELECT * FROM registrant WHERE email = '$resetemail'");
    
                    if ($verifyQuery->num_rows) {
                        $codeQuery = $conn->query("UPDATE registrant SET Code = '$code' WHERE email = '$resetemail'");
    
                        $mail->send();
                        echo "<script>alertify.success('Email sent Successfully!');</script>";
                        echo '<script>timerRes();</script>';
                    }
                    $conn->close();
                } catch (Exception $e) {
                    echo "<script>alertify.error('Something went wrong!!');</script>";
                }
            }
        }
    ?>
</body>
</html>