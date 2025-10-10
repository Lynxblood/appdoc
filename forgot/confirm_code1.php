<?php 
    require '../config/newconn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/alertifyjs/css/alertify.min.css">
    <script src="../assets/alertifyjs/alertify.min.js"></script>
    <link rel="stylesheet" href="../assets/output.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/pdao-logo-new.jpg">
</head>
<body>
    <?php
        if(isset($_POST['sub_newpass'])){
            if(!empty($_GET['cqwd'])){
                $code = mysqli_real_escape_string($conn,$_GET['cqwd']);
                $reid = mysqli_real_escape_string($conn,$_GET['reid']);
                $newpass = mysqli_real_escape_string($conn,$_POST['newpass']);
                $conpass = mysqli_real_escape_string($conn,$_POST['conpass']);
                if($newpass == $conpass){
                    $check_user = mysqli_query($conn, "SELECT * FROM user_account WHERE code='$code' AND user_id='$reid'");
                    if(mysqli_num_rows($check_user)>0){
                        $password = password_hash($newpass, PASSWORD_DEFAULT);
                        $upd_pass_sql = mysqli_query($conn, "UPDATE user_account SET password='$password', code='0' WHERE user_id='$reid'");
                        $_SESSION['message'] = "Password Updated Successfully!";
                        $_SESSION['msgtype'] = "success";
                        $_SESSION['havemsg'] = true;
                        header('location:../public/newlogin.php');
                    }else{
                        echo "<script>alertify.error('Unkwown record!')</script>";
                    }
                }else{
                    echo "<script>alertify.error('Password does not matched!')</script>";
                }
            }else{
                echo "<script>alertify.error('Invalid reset code!')</script>";
            }
        }
    ?><div class="loader"></div>
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
                <h1 class="text-3xl font-bold ">Confirm code</h1>
                <p class="mt-3">Finish setting up of your password. Click&nbsp;<a href="../public/newlogin.php"><span class="text-[--greener] font-bold">here to login</span></a></p>
            </div>
            <div class="row">
            <form action="" method="post" class="w-100 p-4 d-flex flex-column gap-3 ">
                <div class="flex sm:gap-3 px-2 flex-col">
                        <div class="mb-5 basis-full">
                            <label for="newpass" class="block mb-2 text-sm font-medium">Reset Password&nbsp;<span class="text-red-600">*</span></label>
                            <div class="relative passEye">
                                <input name="newpass" oninput="validatePass(this, 'passrequ'); matchPass(this, 'passmatch', 'input2')" type="password" data-input1="input1" id="passwordinputPass" class="bg-gray-50 border passnormal text-gray-900 text-sm rounded-lg block w-full p-2.5" required />
                                <div id="eyeBtn" class="absolute right-3 top-[20%] z-50">
                                    <svg id="eye-slash" class="w-6 h-6 text-greener hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M4.998 7.78C6.729 6.345 9.198 5 12 5c2.802 0 5.27 1.345 7.002 2.78a12.713 12.713 0 0 1 2.096 2.183c.253.344.465.682.618.997.14.286.284.658.284 1.04s-.145.754-.284 1.04a6.6 6.6 0 0 1-.618.997 12.712 12.712 0 0 1-2.096 2.183C17.271 17.655 14.802 19 12 19c-2.802 0-5.27-1.345-7.002-2.78a12.712 12.712 0 0 1-2.096-2.183 6.6 6.6 0 0 1-.618-.997C2.144 12.754 2 12.382 2 12s.145-.754.284-1.04c.153-.315.365-.653.618-.997A12.714 12.714 0 0 1 4.998 7.78ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                                    </svg>
                                    <svg id="eye-open" class="w-6 h-6 text-greener" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="m4 15.6 3.055-3.056A4.913 4.913 0 0 1 7 12.012a5.006 5.006 0 0 1 5-5c.178.009.356.027.532.054l1.744-1.744A8.973 8.973 0 0 0 12 5.012c-5.388 0-10 5.336-10 7A6.49 6.49 0 0 0 4 15.6Z"/>
                                        <path d="m14.7 10.726 4.995-5.007A.998.998 0 0 0 18.99 4a1 1 0 0 0-.71.305l-4.995 5.007a2.98 2.98 0 0 0-.588-.21l-.035-.01a2.981 2.981 0 0 0-3.584 3.583c0 .012.008.022.01.033.05.204.12.402.211.59l-4.995 4.983a1 1 0 1 0 1.414 1.414l4.995-4.983c.189.091.386.162.59.211.011 0 .021.007.033.01a2.982 2.982 0 0 0 3.584-3.584c0-.012-.008-.023-.011-.035a3.05 3.05 0 0 0-.21-.588Z"/>
                                        <path d="m19.821 8.605-2.857 2.857a4.952 4.952 0 0 1-5.514 5.514l-1.785 1.785c.767.166 1.55.25 2.335.251 6.453 0 10-5.258 10-7 0-1.166-1.637-2.874-2.179-3.407Z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="requ hidden" id="passrequ">
                                <p class="mt-1 text-sm" id="requText">Password requirements:</p> 
                                <ul class="mt-1 text-xs max-w-md space-y-1 text-gray-500 list-inside">
                                    <li class="flex items-center" id="passReq">
                                        <svg class="w-3.5 h-3.5 me-2 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                        </svg>
                                        <span class="">At least 8 characters</span>
                                    </li>
                                    <li class="flex items-center" id="passReq">
                                        <svg class="w-3.5 h-3.5 me-2 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                        </svg>
                                        <span class="">At least one lowercase character</span>
                                    </li>
                                    <li class="flex items-center text-gray-500" id="passReq">
                                        <svg class="w-3.5 h-3.5 me-2 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                        </svg>
                                        <span class="">At least one uppercase character</span>
                                    </li>
                                    <li class="flex items-center text-gray-500" id="passReq">
                                        <svg class="w-3.5 h-3.5 me-2 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                        </svg>
                                        <span class="">At least one number</span>
                                    </li>
                                    <li class="flex items-center text-gray-500" id="passReq">
                                        <svg class="w-3.5 h-3.5 me-2 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                        </svg>
                                        <span class="">At least one special character, e.g., ! @ # ?</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mb-5 basis-full ">
                            <label for="conpass" class="block mb-2 text-sm font-medium">Confirm password&nbsp;<span class="text-red-600">*</span></label>
                            <div class="relative passEye">
                                <input name="conpass" oninput="matchPass(this, 'passmatch', 'input1')" type="password" data-input2="input2" id="passwordinputPass" class="bg-gray-50 border text-gray-900 text-sm rounded-lg passnormal block w-full p-2.5" required />
                                <div id="eyeBtn" class="absolute right-3 top-[20%] z-50">
                                    <svg id="eye-slash" class="w-6 h-6 text-greener hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M4.998 7.78C6.729 6.345 9.198 5 12 5c2.802 0 5.27 1.345 7.002 2.78a12.713 12.713 0 0 1 2.096 2.183c.253.344.465.682.618.997.14.286.284.658.284 1.04s-.145.754-.284 1.04a6.6 6.6 0 0 1-.618.997 12.712 12.712 0 0 1-2.096 2.183C17.271 17.655 14.802 19 12 19c-2.802 0-5.27-1.345-7.002-2.78a12.712 12.712 0 0 1-2.096-2.183 6.6 6.6 0 0 1-.618-.997C2.144 12.754 2 12.382 2 12s.145-.754.284-1.04c.153-.315.365-.653.618-.997A12.714 12.714 0 0 1 4.998 7.78ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                                    </svg>
                                    <svg id="eye-open" class="w-6 h-6 text-greener" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="m4 15.6 3.055-3.056A4.913 4.913 0 0 1 7 12.012a5.006 5.006 0 0 1 5-5c.178.009.356.027.532.054l1.744-1.744A8.973 8.973 0 0 0 12 5.012c-5.388 0-10 5.336-10 7A6.49 6.49 0 0 0 4 15.6Z"/>
                                        <path d="m14.7 10.726 4.995-5.007A.998.998 0 0 0 18.99 4a1 1 0 0 0-.71.305l-4.995 5.007a2.98 2.98 0 0 0-.588-.21l-.035-.01a2.981 2.981 0 0 0-3.584 3.583c0 .012.008.022.01.033.05.204.12.402.211.59l-4.995 4.983a1 1 0 1 0 1.414 1.414l4.995-4.983c.189.091.386.162.59.211.011 0 .021.007.033.01a2.982 2.982 0 0 0 3.584-3.584c0-.012-.008-.023-.011-.035a3.05 3.05 0 0 0-.21-.588Z"/>
                                        <path d="m19.821 8.605-2.857 2.857a4.952 4.952 0 0 1-5.514 5.514l-1.785 1.785c.767.166 1.55.25 2.335.251 6.453 0 10-5.258 10-7 0-1.166-1.637-2.874-2.179-3.407Z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-1 text-sm hidden" id="passmatch"></p>
                        </div>
                    </div>
                    <input type="submit" class="pdao-greenbtn" name="sub_newpass" value="Submit">
                </form>
            </div>
        </div>
        </div>
    </div>
        <script src="../assets/jquery/jquery-3.7.1.min.js"></script>
        <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
        <script src="../assets/newapp.js"></script>
    <script>
        document.querySelectorAll('.passEye').forEach(field => {
            const input = field.querySelector('#passwordinputPass');
            const eyeOpen = field.querySelector('#eye-open');
            const eyeSlash = field.querySelector('#eye-slash');

            eyeOpen.addEventListener("click", () => {
                input.type = "text";
                eyeOpen.classList.add("hidden");
                eyeSlash.classList.remove("hidden");
            });

            eyeSlash.addEventListener("click", () => {
                input.type = "password";
                eyeSlash.classList.add("hidden");
                eyeOpen.classList.remove("hidden");
            });
        });

        function validatePass(passElement, tooltipId){
            let input = passElement;
            let inpValue = passElement.value;
            let invalidTooltip = document.getElementById(tooltipId);
            let requText = document.getElementById('requText');

            const hasUpperCase = /[A-Z]/.test(inpValue);
            const hasLowerCase = /[a-z]/.test(inpValue);
            const hasDigits = /\d/.test(inpValue);
            const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(inpValue);
            checkReq(hasUpperCase, 2);
            checkReq(hasLowerCase, 1);
            checkReq(hasDigits, 3);
            checkReq(hasSpecialChars, 4);

            let lenChar = false;
            if(inpValue.length >= 8){
                lenChar = true;
                checkReq(lenChar, 0);
            }else{
                lenChar = false;
                checkReq(lenChar, 0);
            }

            const criteriaMet = [hasUpperCase, hasLowerCase, hasDigits, hasSpecialChars].filter(Boolean).length;

            if(input.value === ''){
                input.classList.remove('passdanger');
                input.classList.remove('passsuccess');
                input.classList.add('passnormal');
                requText.classList.remove('text-greener');
                requText.classList.remove('text-danger');
                invalidTooltip.classList.add('hidden');
            }else{  
                invalidTooltip.classList.remove('hidden');
                if((criteriaMet === 4) && (inpValue.length >= 8)){
                    requText.classList.remove('text-danger');
                    requText.classList.add('text-greener');
                    input.classList.remove('passnormal');
                    input.classList.remove('passdanger');
                    input.classList.add('passsuccess');
                }else{
                    requText.classList.remove('text-greener');
                    requText.classList.add('text-danger');
                    input.classList.remove('passnormal');
                    input.classList.remove('passsuccess');
                    input.classList.add('passdanger');
                }
            }
        }

        function checkReq(req, index){
            let passReq = document.querySelectorAll('#passReq');
            if (!req) {
                // passReq[index].classList.remove('d-none');
                passReq[index].classList.remove('text-greener');
            }else{
                // passReq[index].classList.add('d-none');
                passReq[index].classList.add('text-greener');
            }
        }
        
        function matchPass(passElement, tooltipId, matchHere){
            let input = passElement;
            let input2 = document.querySelectorAll("input[data-"+matchHere+"='"+matchHere+"']");
            let invalidTooltip = document.getElementById(tooltipId);
            let inputCss = document.querySelectorAll("input[data-input2='input2']");
            
            if(((input.value === '') && (input2[0].value === '')) || (input.value === '') || (input2[0].value === '')){
                inputCss[0].classList.remove('passdanger');
                inputCss[0].classList.remove('passsuccess');
                inputCss[0].classList.add('passnormal');
                invalidTooltip.classList.remove('passmatch');
                invalidTooltip.classList.remove('passnotmatch');
                invalidTooltip.classList.add('hidden');
            }else{
                if(input.value === input2[0].value){
                    inputCss[0].classList.remove('passnormal');
                    inputCss[0].classList.remove('passdanger');
                    inputCss[0].classList.add('passsuccess');
                    invalidTooltip.classList.remove('passnotmatch');
                    invalidTooltip.classList.add('passmatch');
                    invalidTooltip.innerHTML = "Password match!";
                    invalidTooltip.classList.remove('hidden');
                }else{
                    inputCss[0].classList.remove('passnormal');
                    inputCss[0].classList.remove('passsuccess');
                    inputCss[0].classList.add('passdanger');
                    invalidTooltip.classList.add('passnotmatch');
                    invalidTooltip.classList.remove('passmatch');
                    invalidTooltip.innerHTML = "Password did not match!";
                    invalidTooltip.classList.remove('hidden');
                }
            }
        }
    </script>
</body>
</html>