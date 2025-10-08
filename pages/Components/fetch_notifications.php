<?php
require '../../config/dbcon.php';

header('Content-Type: application/json');
ob_clean();

$response = [
    "success" => false,
    "message" => "An unexpected error occurred."
];

// Check for authenticated user
if (!isset($_SESSION['user_id'])) {
    $response["message"] = "User not authenticated.";
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle POST request to mark a notification as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read'])) {
    $notification_id = intval($_POST['mark_as_read']);

    // Update the notification as read, but only for the current user
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $notification_id, $user_id);
    
    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Notification marked as read.";
    } else {
        $response["message"] = "Failed to mark notification as read.";
    }
    
    $stmt->close();
    echo json_encode($response);
    exit;
}

// Handle GET request to fetch notifications
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch unread notifications for the user
    $stmt_unread = $conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt_unread->bind_param("i", $user_id);
    $stmt_unread->execute();
    $unread_count = $stmt_unread->get_result()->fetch_assoc()['unread_count'];
    $stmt_unread->close();

    // Fetch the latest 10 notifications for the user
    $stmt_notifs = $conn->prepare("SELECT notification_id, message, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
    $stmt_notifs->bind_param("i", $user_id);
    $stmt_notifs->execute();
    $notifications_result = $stmt_notifs->get_result();
    
    $notifications = [];
    while ($row = $notifications_result->fetch_assoc()) {
        $notifications[] = $row;
    }
    
    $stmt_notifs->close();

    echo json_encode([
        "success" => true,
        "unread_count" => $unread_count,
        "notifications" => $notifications
    ]);
}

$conn->close();
?>