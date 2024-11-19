<?php
include "public/session_start.php";
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_comments = createDatabaseConnection($config['comments_db']);
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'], $_GET['commentID'])) {
    echo json_encode(["status" => "error", "message" => "Not authorized or missing information"]);
    exit;
}

$userId = $_SESSION['user_id'];
$commentId = $_GET['commentID'];

$stmt = $mysqli_comments->prepare("DELETE FROM comments WHERE commentID = ? AND userID = ?");
$stmt->bind_param("ii", $commentId, $userId);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Comment deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error deleting comment: " . $stmt->error]);
}
$stmt->close();
?>