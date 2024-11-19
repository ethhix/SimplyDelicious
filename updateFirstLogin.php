<?php
include('public/session_start.php');
$config = include("includes/config.php");
$mysqli = createDatabaseConnection($config['users_db']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['userId'])) {
    $userId = $_POST['userId'];

    $stmt = $mysqli->prepare("UPDATE users SET firstLogin = 0 WHERE UserId = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not update firstLogin status.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>