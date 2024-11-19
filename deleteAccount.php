<?php

include "public/session_start.php";
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli = createDatabaseConnection($config['users_db']);
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$sql = "DELETE FROM users WHERE userID = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt == false) {
    error_log("MySQL prepare error: " . $mysqli->error);
    echo json_encode(["success" => false, 'error' => 'Internal server error.']);
    exit;
}

$stmt->bind_param('s', $userId);
$executeSuccess = $stmt->execute();

if ($executeSuccess == true) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Account deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No account found with the given ID.']);
    }
} else {
    error_log('Execute error: ' . $stmt->error);
    echo json_encode(['success' => false, 'error' => 'Failed to delete account.']);
}

$stmt->close();
$mysqli->close();

?>