<?php
    session_start();
    date_default_timezone_set('Asia/Manila');
    $servername = "localhost";
    $username = "root";
    $password = "";
    // $dbname = "student_organization";
    $dbname = "stud_org_gemini";
    $currentTime = time();
    $formattedTime = date('Y-m-d H:i:s', $currentTime);
    
    // CHANGE THIS VALUE TO true OR false
    $maintenance_mode = false; 
    $maintenance_page = __DIR__ . '/maintenance_page.html'; 
    
    if ($maintenance_mode === true) {
        // 1. Send 503 Service Unavailable header
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Retry-After: 3600'); // Optional: Tell search engines to retry in 1 hour (3600 seconds)
        
        // 2. Output the maintenance page content
        if (file_exists($maintenance_page)) {
            readfile($maintenance_page);
        } else {
            echo '<h1>Website is down for maintenance.</h1>';
        }
    
        // 3. Stop the script immediately
        exit();
    }
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    // $useURL = "https://appdoc.apxathletix.net/"; // change this part once deployed on hosting for images links e.g https://basc.edu.ph/
    
    $useURL = "http://localhost:3000/"; 
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if(!empty($_SESSION['havemsg'])){
        if($_SESSION['havemsg'] == true){
            if($_SESSION['msgtype'] == 'warning'){
                echo "<script>alertify.warning('" .$_SESSION['message']. "');</script>";
                $_SESSION['havemsg'] = false;
            }elseif($_SESSION['msgtype'] == 'success'){
                echo "<script>alertify.success('" .$_SESSION['message']. "');</script>";
                $_SESSION['havemsg'] = false;
            }elseif($_SESSION['msgtype'] == 'error'){
                echo "<script>alertify.error('" .$_SESSION['message']. "');</script>";
                $_SESSION['havemsg'] = false;
            }
        }
    }

    // function usable around all pages to format raw date
    function formatDate($rawDate) {
        try {
            $date = new DateTime($rawDate);
            
            return $date->format('F j, Y');
        } catch (Exception $e) {
            return 'Invalid date';
        }
    }

    // function usable around all pages to format raw datetime
    function formatDateTime($rawDate) {
        try {
            $date = new DateTime($rawDate);
            
            return $date->format('F j, Y, h:i A');
        } catch (Exception $e) {
            return 'Invalid date';
        }
    }


    //trigger alertify in the start of the website
    // $_SESSION['message'] = formatDateTime($formattedTime);
    // $_SESSION['msgtype'] = "success";
    // $_SESSION['havemsg'] = true;

    // $sql = "SELECT DISTINCT role FROM users";
    $sql = "SELECT DISTINCT user_role FROM users";
    $result = $conn->query($sql);

    $allroles = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // $allroles[] = $row['role'];
            $allroles[] = $row['user_role'];
        }
    }


?>