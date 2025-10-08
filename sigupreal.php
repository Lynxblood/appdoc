<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
	<link rel="icon"  href="../img/logo/logo_osas.png"><!-- sample icon -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/externalCSS/signup.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    
</head>
<body>
    <div class="container-fluid">
        <div class="row ps-5 ms-5" style="height: 100vh;">
            <div class="col-6 d-flex justify-content-center  align-items-center ">
                <div class="row">
                    <div class="col-12  d-flex justify-content-center ">
                        <img src="../img/logo/basc_logo.png" height="200 " width="200" >
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                        <img src="../img/logo/logo_osas.png" height="150 " width="150">
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                        <img src="../img/logo/fssc-logo.png" height="150 " width="150">
                    </div>
                </div>
            </div>
            
            <div class="col-6 card-wrapper  d-flex justify-content-start ps-5" style="margin: auto;">
                <div class="card card-outer shadow-lg pb-3 " style="height: 75vh; width:400px;">
                    <div class="card card-inner mx-3 mt-3 shadow" style="height: 65vh;">
                        <div class="card-body align-content-center">
                            <div class="card-title text-center starttitle mb-2">Sign Up</div>
                            <p class="note px-4 pb-0 mb-0">Please fill in this form to create an account.</p>
                            <form action="../function/function.php" method="post">
                                <div class="input-group mb-3 px-3">
                                    <input type="text" class="form-control inputradius" placeholder="Organizations Name/Name" aria-label="name" required name="name" aria-describedby="basic-addon2">
                                    <span class="input-group-text inputradius" id="basic-addon2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                            <path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path>
                                        </svg>
                                    </span>
                                </div>
                                
                                <div class="input-group mb-3 px-3">
                                    <input type="email" class="form-control inputradius" placeholder="Email" aria-label="email" name="email" required aria-describedby="basic-addon2">
                                    <span class="input-group-text inputradius" id="basic-addon2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                        <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10c1.466 0 2.961-.371 4.442-1.104l-.885-1.793C14.353 19.698 13.156 20 12 20c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8v1c0 .692-.313 2-1.5 2-1.396 0-1.494-1.819-1.5-2V8h-2v.025A4.954 4.954 0 0 0 12 7c-2.757 0-5 2.243-5 5s2.243 5 5 5c1.45 0 2.748-.631 3.662-1.621.524.89 1.408 1.621 2.838 1.621 2.273 0 3.5-2.061 3.5-4v-1c0-5.514-4.486-10-10-10zm0 13c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3z"></path></svg>
                                    </span>
                                </div>
                                <div class="input-group mb-3 px-3">
                                    <select class="form-select inputradius"  name="user_Type"required aria-label="Default select example">
                                        <option selected disabled>User Type</option>
                                        <option value="1">Academic Organization</option>
                                        <option value="2">Non-Academic Organization</option>
                                        <option value="3">Adviser</option>
                                        <option value="4">Dean</option>
                                        <option value="5">Program Chair</option>
                                        <option value="6">OSAS</option>
                                        <option value="7">FSSC</option>
                                    </select>
                                    <span class="input-group-text inputradius" id="basic-addon2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                        <path d="M12 10c1.151 0 2-.848 2-2s-.849-2-2-2c-1.15 0-2 .848-2 2s.85 2 2 2zm0 1c-2.209 0-4 1.612-4 3.6v.386h8V14.6c0-1.988-1.791-3.6-4-3.6z"></path><path d="M19 2H5c-1.103 0-2 .897-2 2v13c0 1.103.897 2 2 2h4l3 3 3-3h4c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zm-5 15-2 2-2-2H5V4h14l.002 13H14z"></path></svg>
                                    </span>
                                </div>
                                <div class="input-group mb-3 px-3">
                                    <input type="password" class="form-control inputradius" placeholder="Password"  required aria-label="password " id="password"  name="password" aria-describedby="basic-addon2">
                                    
                                    <span class="toggle-icon" id="togglepass" onclick="togglePasswordVisibility()" style="width: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 0.5);transform: scaleX(-1);">
                                            <path d="M14 12c-1.095 0-2-.905-2-2 0-.354.103-.683.268-.973C12.178 9.02 12.092 9 12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-.092-.02-.178-.027-.268-.29.165-.619.268-.973.268z"></path><path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z"></path></svg>
                                    </span> 
                                    
                                    <span class="input-group-text inputradius" id="basic-addon2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                            <path d="M17 8V7c0-2.757-2.243-5-5-5S7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2H9V7c0-1.654 1.346-3 3-3s3 1.346 3 3v1h2zm1 4 .002 8H6v-8h12z"></path>
                                        </svg>
                                    </span>
                                    
                                </div>
                                
                                <div class="input-group mb-1 px-3">
                                    <input type="password" class="form-control inputradius" placeholder="Confirm Password" required aria-label="cpassword" id="cpassword"  name="cpassword" aria-describedby="basic-addon2">
                                    
                                    <span class="toggle-icon"  id="togglecpass" onclick="toggleCPasswordVisibility()" style="width: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 0.5);transform: scaleX(-1);">
                                            <path d="M14 12c-1.095 0-2-.905-2-2 0-.354.103-.683.268-.973C12.178 9.02 12.092 9 12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-.092-.02-.178-.027-.268-.29.165-.619.268-.973.268z"></path><path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z"></path></svg>
                                    </span> 

                                    <span class="input-group-text inputradius" id="basic-addon2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                            <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10 .002 8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="d-grid gap-2 col-6 mx-auto">
                                    <button class="btn shadow" type="submit" name="signup" id="signup">Sign in</button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col px-0 d-flex justify-content-end">   
                            <a href="../index.php" class="login-text text-center py-2">Login</a>
                        </div>
                        <div class="col px-0  d-flex justify-content-start ">
                            <a href="signup.php" class="signup-text text-center py-2">Sign up</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("password");
        const icon = document.getElementById("togglepass");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("bx-show");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("bx-hide");
        }
    }

    function toggleCPasswordVisibility() {
        const passwordInput = document.getElementById("cpassword");
        const icon = document.getElementById("togglecpass");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("bx-show");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("bx-hide");
        }
    }
    </script>
</body>
</html>



<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up Page</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/externalCSS/signup.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
</head>
<body>

  <div class="login-container">
    <h1> Welcome!</h1>
    <div class="logo-section">
      <img src="../img/logo/fssc-logo.png" alt="FSSC Logo">
      <img src="../img/logo/logo_osas.png" alt="OSAS Logo">
      <img src="../img/logo/basc_logo.png" alt="BASC Logo">
    </div>

    <div class="bg">
      <div class="form-section">
          <a href="../index.php">
        <div class="login-text">Login</div>
        </a>
        <div class="signup-text">Sign up</div>

        <form>
          <div class="mb-3">
            <label><i class="bi bi-person-fill me-2"></i>Username</label>
            <input type="text" class="form-control">
          </div>

          <div class="mb-3">
            <label><i class="bi bi-envelope-fill me-2"></i>Email address</label>
            <input type="email" class="form-control">
          </div>

          <div class="mb-3">
            <label><i class="bi bi-telephone-fill me-2"></i>Contact no.</label>
            <input type="text" class="form-control">
          </div>

          <div class="mb-3">
            <label><i class="bi bi-lock-fill me-2"></i>Password</label>
            <input type="password" class="form-control">
          </div>

          <div class="mb-3">
            <label><i class="bi bi-lock-fill me-2"></i>Confirm password</label>
            <input type="password" class="form-control">
          </div>

          <button clas type="submit" class="btn btn-signup">Sign up</button>
        </form>
      </div>
    </div>
  </div>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->
