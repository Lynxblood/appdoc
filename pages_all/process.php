<?php
require '../config/dbcon.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$app_id = $_POST['app_id'] ?? null;

if ($action === 'submit_new' && ($user_role === 'academic_organization' || $user_role === 'non_academic_organization')) {
    $organization_id = $_SESSION['organization_id'];
    $organization_logo = $_POST['organization_logo'];
    $letter_recipient = $_POST['letter_recipient'];
    $letter_date = $_POST['letter_date'];
    $letter_content = $_POST['letter_content'];

    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO accreditation_applications (organization_id, status, current_step) VALUES (?, 'submitted', 'submitted')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $organization_id);
        $stmt->execute();
        $new_app_id = $stmt->insert_id;
        $stmt->close();

        $sql = "INSERT INTO document_revisions (application_id, revision_number, organization_logo, letter_recipient, letter_date, letter_content, submitted_by_user_id, revision_type) VALUES (?, 1, ?, ?, ?, ?, ?, 'initial_submission')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssi", $new_app_id, $organization_logo, $letter_recipient, $letter_date, $letter_content, $user_id);
        $stmt->execute();
        $new_revision_id = $stmt->insert_id;
        $stmt->close();
        
        $sql = "UPDATE accreditation_applications SET revision_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_revision_id, $new_app_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        header('Location: dashboard.php');
        exit;
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        die("Error submitting application: " . $exception->getMessage());
    }
}

if ($action === 'resubmit_revision' && ($user_role === 'academic_organization' || $user_role === 'non_academic_organization')) {
    $organization_logo = $_POST['organization_logo'];
    $letter_recipient = $_POST['letter_recipient'];
    $letter_date = $_POST['letter_date'];
    $letter_content = $_POST['letter_content'];

    $conn->begin_transaction();
    try {
        $sql = "SELECT MAX(revision_number) as max_rev FROM document_revisions WHERE application_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $app_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $new_revision_number = $row['max_rev'] + 1;
        $stmt->close();

        $sql = "INSERT INTO document_revisions (application_id, revision_number, organization_logo, letter_recipient, letter_date, letter_content, submitted_by_user_id, revision_type) VALUES (?, ?, ?, ?, ?, ?, ?, 'resubmission')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssi", $app_id, $new_revision_number, $organization_logo, $letter_recipient, $letter_date, $letter_content, $user_id);
        $stmt->execute();
        $new_revision_id = $stmt->insert_id;
        $stmt->close();

        $sql = "UPDATE accreditation_applications SET status = 'submitted', current_step = 'submitted', revision_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_revision_id, $app_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        header('Location: dashboard.php');
        exit;
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        die("Error resubmitting application: " . $exception->getMessage());
    }
}

if ($action && $app_id) {
    $sql = "SELECT current_step FROM accreditation_applications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $current_step = $application['current_step'] ?? null;
    $stmt->close();

    $new_status = '';
    $new_step = '';
    $new_revision_id = null;
    $has_new_revision = false;

    switch ($action) {
        case 'approve':
            if ($user_role === 'osas' && $current_step === 'submitted') {
                $new_status = 'reviewed';
                $new_step = 'reviewed';
            } elseif ($user_role === 'dean' && $current_step === 'reviewed') {
                $new_status = 'endorsed';
                $new_step = 'endorsed';
            } elseif ($user_role === 'program_chair' && $current_step === 'endorsed') {
                $new_status = 'approved';
                $new_step = 'approved';
            } elseif ($user_role === 'vice_pres_academic_affairs' && $current_step === 'approved') {
                $new_status = 'accredited';
                $new_step = 'final';
            }
            break;

        case 'reject':
            if ($user_role === 'vice_pres_academic_affairs' && $current_step === 'approved') {
                $new_status = 'rejected';
                $new_step = 'final';
            }
            break;

        case 'request_revision':
            $organization_logo = $_POST['organization_logo'];
            $letter_recipient = $_POST['letter_recipient'];
            $letter_date = $_POST['letter_date'];
            $letter_content = $_POST['letter_content'];
            $has_new_revision = true;

            $conn->begin_transaction();
            try {
                $sql = "SELECT MAX(revision_number) as max_rev FROM document_revisions WHERE application_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $app_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $new_revision_number = $row['max_rev'] + 1;
                $stmt->close();

                $sql = "INSERT INTO document_revisions (application_id, revision_number, organization_logo, letter_recipient, letter_date, letter_content, submitted_by_user_id, revision_type) VALUES (?, ?, ?, ?, ?, ?, ?, 'requested_revision')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iissssi", $app_id, $new_revision_number, $organization_logo, $letter_recipient, $letter_date, $letter_content, $user_id);
                $stmt->execute();
                $new_revision_id = $stmt->insert_id;
                $stmt->close();

                $new_status = 'revision_requested';
                $new_step = 'submitted';

                $conn->commit();
            } catch (mysqli_sql_exception $exception) {
                $conn->rollback();
                die("Error creating revision: " . $exception->getMessage());
            }
            break;
    }

    if ($new_status && $new_step) {
        $sql = "UPDATE accreditation_applications SET status = ?, current_step = ?";
        if ($has_new_revision) {
            $sql .= ", revision_id = ?";
            $sql .= " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $new_status, $new_step, $new_revision_id, $app_id);
        } else {
            $sql .= " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $new_status, $new_step, $app_id);
        }
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header('Location: dashboard.php');
exit;