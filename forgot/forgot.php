<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        /* General Setup */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to the top, good for short screens */
            padding-top: 50px;
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
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #355f2e; /* Updated Green label */
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box; /* Include padding in width */
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="email"]:focus {
            outline: none;
            border-color: #355f2e; /* Highlight on focus (New Primary) */
            /* Adjusted box-shadow using the new color's RGB value */
            box-shadow: 0 0 0 3px rgba(53, 95, 46, 0.2); 
        }

        /* Button Styling (pdao-greenbtn replacement) */
        .reset-button {
            width: 100%;
            padding: 12px 20px;
            background-color: #355f2e; /* Primary Green (New Primary) */
            color: #ffffff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.1s;
            margin-bottom: 10px; /* Added margin to separate from the link */
        }

        .reset-button:hover {
            background-color: #274821; /* Darker green on hover (Calculated darker shade) */
        }
        
        /* PHP Status Messages */
        .status-message {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: left;
        }

        .status-success {
            background-color: #e8f5e9; /* Light green background - keeping subtle */
            color: #274821; /* Dark green text (New Darker) */
            border: 1px solid #355f2e; /* Border (New Primary) */
        }

        .status-error {
            background-color: #ffebee; /* Light red background */
            color: #D32F2F; /* Dark red text */
            border: 1px solid #F44336;
        }

        /* Back Link Styling (New) */
        .back-link-container {
            margin-top: 20px;
        }

        .back-link {
            color: #355f2e; /* Primary Green (New Primary) */
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: color 0.3s;
            display: inline-block;
        }

        .back-link:hover {
            color: #274821; /* Darker green on hover (New Darker) */
            text-decoration: underline;
        }

    </style>
</head>
<body>

    <div class="container">
        
        <h2>Forgot Password</h2>
        <p class="instruction-text">Enter your email address associated with your account to receive a password reset link.</p>

        <?php
        // Display any messages (e.g., success or error from send_reset_link.php)
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<p class="status-message status-success">A password reset link has been sent to your email address.</p>';
            } elseif ($_GET['status'] == 'error') {
                echo '<p class="status-message status-error">Error: Could not send the reset link. Please check your email and try again.</p>';
            }
        }
        ?>

        <!-- Note: I removed the unused Tailwind classes and replaced them with standard HTML/CSS structure -->
        <form action="send_reset_link.php" method="post">
            
            <div class="form-group">
                <label for="resetemail">Email</label>
                <input name="resetemail" 
                       type="email" 
                       id="resetemail" 
                       placeholder="sample@gmail.com" 
                       required />
            </div>

            <!-- The old Resend button block is commented out, as requested in the original code -->
            <!-- 
            <div class="flex mb-2 gap-1">
                <p class="text-xs text-[--greener] font-bold">Didn't recieve email?</p>
                <button class="text-xs text-[--greener]" id="resendBtn" onclick="timerRes(this)"><span>Resend</span></button> <span class="" id="resendTimer"></span>
            </div> 
            -->

            <button type="submit" name="resetbtn" class="reset-button">
                Reset Password
            </button>
        </form>

        <!-- Back Link added here -->
        <div class="back-link-container">
            <!-- Assuming your login page is called login.php or index.php -->
            <a href="../" class="back-link">← Back to Login</a>
        </div>

    </div>

</body>
</html>
<!-- ALTER TABLE `users` ADD `reset_code` VARCHAR(15) NOT NULL AFTER `signature_base_code`;  -->
