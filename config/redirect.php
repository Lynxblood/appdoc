<?php
require 'dbcon.php';

if (!empty($_SESSION['user_role'])) {
    $role = $_SESSION['user_role'];

    if (($role == "academic_organization") || ($role == "non_academic_organization")) {
        header('Location: ../pages/organization/dashboard.php');
    } elseif ($role == "adviser") {
        header('Location: ../pages/adviser_pages/dashboard.php');
    } elseif ($role == "dean") {
        header('Location: ../pages/dean_pages/dashboard.php');
    } elseif ($role == "fssc") {
        header('Location: ../pages/fssc_pages/dashboard.php');
    } elseif ($role == "admin") {
        header('Location: ../pages/admin_pages/dashboard.php');
    } else {
        header("Location: ../pages/logout.php");
    }
    exit;

}
?>
