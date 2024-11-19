<?php
include('public/session_start.php');
$config = include("includes/config.php");
$mysqli_discussion = createDatabaseConnection($config['discussion_db']);
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

if (isset($_POST['commentText'], $_POST['postID'], $_POST['parentID'])) {
    $userId = $_SESSION['user_id'];
    $commentText = $_POST['commentText'];
    $postId = $_POST['postID'];
    $parentId = $_POST['parentID'] === 'NULL' ? NULL : (int) $_POST['parentID'];
    $profilePicUrl = $_SESSION['profilePicUrl'] ?? 'default_pic.jpg';

    if (empty($commentText)) {
        echo json_encode(["status" => "error", "message" => "Please enter a comment."]);
        exit;
    }

    //Assume the username is stored in the session
    $username = $_SESSION['username'];

    //Prepare SQL statement to insert a new comment
    $stmt = $mysqli_discussion->prepare("INSERT INTO discussionboard_comments (postID, userID, content, parentID) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $mysqli_discussion->error]);
        exit;
    }

    $stmt->bind_param("iisi", $postId, $userId, $commentText, $parentId);
    if ($stmt->execute()) {
        $newCommentId = $mysqli_discussion->insert_id;  //get the ID of the newly inserted comment
        echo json_encode([
            "status" => "success",
            "comment" => [
                "id" => $newCommentId,
                "text" => $commentText,
                "username" => $username,
                "profilePicUrl" => $profilePicUrl,
                "parentId" => $parentId
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
}
?>