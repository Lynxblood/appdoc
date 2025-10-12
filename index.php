<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
	<link rel="icon"  href="img/logo/logo_osas.png"><!-- sample icon -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/externalCSS/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="assets/alertifyjs/css/alertify.min.css"> <!-- added by me -->
    <script src="assets/alertifyjs/alertify.min.js"></script> <!-- added by me -->
    
</head>
<?php
    include 'config/dbcon.php';
?>
<body>
    <div class="container-fluid">
        <div class="row row-main">
            <div class="col-md-6 bg-white p-0">
                <div class="card-left card">
                    <div class="card-body p-0 overflow-x-hidden">
                        <!-- <div class="card-inner-left card p-0">
                            <div class="card-body"> -->
                                <div class="mt-0 mt-md-5 p-0 pt-5 row justify-content-center align-items-end pe-md-4">
                                    <img src="img/logo/logo_osas.png" alt="" class="imglogo">
                                    <img src="img/logo/basc_logo.png" alt="" class="imglogo1">
                                    <img src="img/logo/newfssc.png" alt="" class="imglogo">
                                    <div class=" row1 text-center" >
                                        <div class="col-12 text-center mt-2 p-0">
                                            <h3 class="header-text">Welcome Back!</h3>
                                    <p class="signup-text">We're happy to have you back! This platform is designed to help you and your organization stay organized and efficient. <br><br>If your organization has not been registered yet, please register using the button below.</p>
                                    <!-- <a href="pages/register-account.php" class="register-account-button btn mt-3">Register Org</a> -->
                                        </div>
                                    </div>
                                </div>
                            <!-- </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col2 col-md-6 p-0">
                <div class="card-right card bg-white">
                    <div class="card-right-body card-body d-flex justify-content-center align-items-center">
                        <form action="pages/process_login.php" method="post">
                            
                        <h3 class="header-login text-center">Member Login</h3>
                        <p class="login-text text-center">Provide your login details to continue.</p>
                            <div class="input-group mb-3 ">
                                <input type="text" class="form-control " id="email" placeholder="Email" aria-label="username" name="email" aria-describedby="basic-addon2">
                                <span class="input-group-text " id="basic-addon2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                        <path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path>
                                    </svg>
                                </span>
                            </div>  
                           <div class="input-group mb-1">
                            <input type="password" class="form-control " placeholder="Password" id="password" aria-label="password" name="password" aria-describedby="basic-addon2">

                                <span class="input-group-text " id="togglepass" onclick="togglePasswordVisibility()" style="cursor:pointer;">
                                    <!-- Eye Open SVG -->
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5zm0-8a3 3 0 100 6 3 3 0 000-6z"></path>
                                    </svg>
                                </span>

                                <span class="input-group-text " id="basic-addon2">
                                    <!-- Lock Icon SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10 .002 8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="row px-4 py-0 mb-3">
                                <div class="col py-0">
                                    <a href="forgot/forgot.php" class="forgotpass normal-theme-text text-theme-green">Forgot Password?</a>
                                </div>
                            </div>
                            <div class="d-grid gap-2 col-6 mx-auto mt-2">
                                <button class="register-account-button btn mt-3" type="submit" name="login">LOGIN</button>
                            </div>
                            <div class="col-md-12 d-flex justify-content-end align-items-end">
                                <p class="register-text">Don’t have an account yet?  <a href="pages/register.php" class="fw-bold fst-italic normal-theme-text text-theme-green">Sign Up</a> here first.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <script src="assets/jquery/jquery-3.7.1.min.js"></script> <!-- added by me -->
    <script>
        
    alertify.set('notifier','position', 'top-right');
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.setAttribute("d", "M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12zm11 3a3 3 0 100-6 3 3 0 000 6z"); // optional: swap path
        } else {
        passwordInput.type = "password";
        eyeIcon.setAttribute("d", "M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5zm0-8a3 3 0 100 6 3 3 0 000-6z");
        }
    }
    </script>
</html>