<?php
  require '../config/dbcon.php';

  // // INSERTING FOLDER 
  // if(isset($_POST['add-folder'])){
  //   $foldername = mysqli_real_escape_string($conn, $_POST['folder_name']);

  //   echo $foldername;
  //   $addFolder = "INSERT INTO folders (folder_name) VALUES ('$foldername')";
  //   // = "INSERT INTO folder (folder_name) VALUES ('$foldername')";

  //   $addquery = mysqli_query($conn, $addFolder);

  //   if($addquery){
  //     echo '<script>("Added successfully!")</script>';
  //     header("Refresh:0; url = ../pages/files.php");
  //   }else{
      
  //     echo '<script>("Failed To Add!")</script>';
  //     header("Refresh:0; url = ../pages/files.php");
  //   }
  // }
  if (isset($_POST['add-folder-inside'])) {
    $folder_name = $_POST['folder_name'];
    $parent_id = $_POST['parent_id'];

    if ($parent_id === "NULL" || $parent_id === "" || $parent_id === null) {
        $query = "INSERT INTO folders (folder_name, parent_ID) VALUES (?, NULL)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $folder_name);
    } else {
        $query = "INSERT INTO folders (folder_name, parent_ID) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $folder_name, $parent_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("Folder added successfully!")</script>';
        // Redirect back to folder view with same parent_id
        $redirect = ($parent_id && $parent_id !== "NULL") ? "?folder_ID=$parent_id" : "";
        header("Refresh:0; url = ../pages/fssc_pages/folder.php$redirect");
        exit;
    } else {
        echo '<script>alert("Failed to add folder.")</script>';
        header("Refresh:0; url = ../pages/fssc_pages/folder.php");
        exit;
    }
}
if (isset($_POST['add-folder'])) {
    $folder_name = trim($_POST['folder_name']);

    if (!empty($folder_name)) {
        // Sanitize input
        $folder_name = htmlspecialchars($folder_name, ENT_QUOTES, 'UTF-8');

        // Check if folder already exists
        $check = $conn->prepare("SELECT folder_ID FROM folders WHERE folder_name = ? AND parent_ID IS NULL");
        $check->bind_param("s", $folder_name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $folder_error = "A folder with this name already exists.";
        } else {
            // Insert folder
            $stmt = $conn->prepare("INSERT INTO folders (folder_name) VALUES (?)");
            $stmt->bind_param("s", $folder_name);

            if ($stmt->execute()) {
                $folder_message = "Folder '$folder_name' created successfully.";
            } else {
                $folder_error = "Error creating folder: " . $stmt->error;
            }
            $stmt->close();
        }

        $check->close();
    }
}

if (isset($_POST['add-file']) && isset($_FILES['files'])) {
    $folder_id = isset($_POST['folder_id']) && is_numeric($_POST['folder_id']) ? intval($_POST['folder_id']) : null;

    $filename = $_FILES['files']['name'];
    $tempname = $_FILES['files']['tmp_name'];
    $fileSize = $_FILES['files']['size'];
    $fileType = $_FILES['files']['type'];

    $allowedExtensions = ['pdf', 'docx', 'jpg', 'png'];
    $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "<script>alert('File type not allowed.'); window.location.href='../pages/files.php';</script>";
        exit;
    }

    if ($fileSize > 5000000) {
        echo "<script>alert('File is too large (max 5MB).'); window.location.href='../pages/files.php';</script>";
        exit;
    }

    // Check if folder exists if folder_id is set
    if ($folder_id !== null) {
        $checkFolder = $conn->prepare("SELECT folder_ID FROM folders WHERE folder_ID = ?");
        $checkFolder->bind_param("i", $folder_id);
        $checkFolder->execute();
        $checkFolder->store_result();
        if ($checkFolder->num_rows === 0) {
            echo "<script>alert('Invalid folder ID.'); window.location.href='../pages/files.php';</script>";
            exit;
        }
        $checkFolder->close();
    }

    $uniqueName = uniqid() . '_' . basename($filename);
    $uploadPath = '../uploads/' . $uniqueName;

    if (move_uploaded_file($tempname, $uploadPath)) {
        $stmt = $conn->prepare("INSERT INTO files (file_Name, file_Type, file_Size, folder_ID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $uniqueName, $fileType, $fileSize, $folder_id);
        if ($stmt->execute()) {
            echo "<script>alert('File uploaded successfully.'); window.location.href='../pages/files.php';</script>";
        } else {
            echo "<script>alert('Database error: " . $stmt->error . "'); window.location.href='../pages/files.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Failed to upload file.'); window.location.href='../pages/files.php';</script>";
    }
}



if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $user_Type = mysqli_real_escape_string($conn,$_POST['user_Type']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $cpassword = mysqli_real_escape_string($conn,$_POST['cpassword']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $select = mysqli_query($conn, "SELECT * FROM account WHERE name = '$name'") or die ('Query Failed');

    if(mysqli_num_rows($select) > 0){
        echo "<script> alert('User Already Exist!'); </script>";
        header("Refresh:0; url=../pages/signup.php");
    }elseif ($password!== $cpassword){
        echo "<script> alert('Password does not match!'); </script>";
        header("Refresh:0; url=../pages/signup.php");
    }else{
        $add_query = "INSERT INTO account (name, email, password, userType_ID)
                    VALUES ('$name', '$email', '$hashedPassword', '$user_Type')";
        $addQuery_run = mysqli_query($conn, $add_query);
        if($addQuery_run){
            echo '<script> alert("Account is Successfully Registered.")</script>';
            header("Refresh:0; url=../pages/signup.php");
        }
        else{
            echo '<script> alert("Account NOT Registered, Unknown Error Occured")</script>';
            header("Refresh:0; url=../pages/signup[.php");
        }
    }
  }
 
//   if(isset($_POST['login'])){
//     $email = mysqli_real_escape_string($conn, $_POST['email']);
//     $password= mysqli_real_escape_string($conn, $_POST['password']);

  
//     $query = "SELECT * FROM account WHERE email = '$email' AND password = '$password'";
//     $result = mysqli_query($conn,$query);

  
//     if(mysqli_num_rows($result) > 0){
    
//       header("Refresh:0; url=../pages/fssc_pages/dash.php");
//         exit(0);
//     } else {
//         echo '<script> alert("Incorrect USERNAME or PASSWORD.")</script>';
//         header("Refresh:0; url=../index.php");
  
//     }
//   }
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $passwordInput = $_POST['password'];

        $query = "SELECT * FROM account WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verify password securely
            if (password_verify($passwordInput, $row['password'])) {
                $_SESSION['user_ID'] = $row['user_ID'];
                $_SESSION['userType_ID'] = $row['userType_ID']; // Store role

                // Redirect based on role
                switch ($row['userType_ID']) {
                    case '1':
                        header("Location: ../pages/org_pages/dash-org.php");
                        break;
                    case '2':
                        header("Location: ../pages/org_pages/dash-org.php");
                        break;
                    case '3':
                        header("Location: ../pages/adviser_pages/dash-adviser.php");
                        break;
                    case '4':
                        header("Location: ../pages/signatory_pages/dash-sig.php");
                        break;
                    case '5':
                        header("Location: ../pages/signatory_pages/dash-sig.php");
                        break;
                    case '6':
                        header("Location: ../pages/osas_pages/dash-osas.php");
                        break;
                    case '7':
                        header("Location: ../pages/fssc_pages/dash.php");
                        break;
                    default:
                        echo '<script>alert("Unknown role. Contact admin.")</script>';
                        echo '<script>window.location.href = "../index.php";</script>';
                }
                exit(0);
            } else {
                echo '<script>alert("Incorrect PASSWORD.")</script>';
                echo '<script>window.location.href = "../index.php";</script>';
                exit(0);
            }
        } else {
            echo '<script>alert("Incorrect EMAIL.")</script>';
            echo '<script>window.location.href = "../index.php";</script>';
            exit(0);
        }
    }

    if (isset($_POST['add-officer']) && isset($_FILES['image'])) {
        $img_name = $_FILES['image']['name'];
        $img_size = $_FILES['image']['size'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $error = $_FILES['image']['error'];
    
        // Get user_ID from session
        $user_ID = $_SESSION['user_ID'];
    
        // Check for file upload errors
        if ($error === 0) {
            // Check if the file size is too large
            if ($img_size > 1000000) {
                echo '<script>alert("Sorry, your file is too large")</script>';
                header("Refresh:0; url=../../pages/fssc_pages/org.php");
                exit();
            } else {
                // Get file extension and validate
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $allowed_exs = array("jpg", "jpeg", "png");
    
                // Validate the file extension
                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = '../img/profile/' . $new_img_name;
    
                    // Move the uploaded file
                    if (move_uploaded_file($tmp_name, $img_upload_path)) {
                        // Sanitize form data
                        $fname = mysqli_real_escape_string($conn, $_POST['f_name']);
                        $mname = mysqli_real_escape_string($conn, $_POST['m_name']);
                        $lname = mysqli_real_escape_string($conn, $_POST['l_name']);
                        $role = mysqli_real_escape_string($conn, $_POST['role']);
    
                        // Check if user_ID exists in the account table
                        $user_check_query = "SELECT * FROM account WHERE user_ID = '$user_ID'";
                        $user_check_result = mysqli_query($conn, $user_check_query);
    
                        if (mysqli_num_rows($user_check_result) > 0) {
                            // Insert the new officer
                            $add_query = "INSERT INTO officers (f_name, m_name, l_name, role, photo, user_ID)
                                          VALUES ('$fname', '$mname', '$lname', '$role', '$new_img_name', '$user_ID')";
                            $addQuery_run = mysqli_query($conn, $add_query);
    
                            if ($addQuery_run) {
                                echo '<script>alert("Added Successfully.")</script>';
                                header("Refresh:0; url=../pages/fssc_pages/officers.php");
                                exit();
                            } else {
                                echo '<script>alert("Officer addition failed.")</script>';
                                header("Refresh:0; url=../pages/fssc_pages/officers.php");
                                exit();
                            }
                        } else {
                            // User doesn't exist, handle the error
                            echo '<script>alert("Invalid user ID. Cannot add officer.")</script>';
                            header("Refresh:0; url=../pages/fssc_pages/officers.php");
                            exit();
                        }
                    } else {
                        echo '<script>alert("Failed to upload image.")</script>';
                        header("Refresh:0; url=../pages/fssc_pages/officers.php");
                        exit();
                    }
                } else {
                    echo '<script>alert("You cannot upload files of this type.")</script>';
                    header("Refresh:0; url=../pages/fssc_pages/officers.php");
                    exit();
                }
            }
        } else {
            echo '<script>alert("Unknown Error Occurred.")</script>';
            header("Refresh:0; url=../pages/fssc_pages/officers.php");
            exit();
        }
    }