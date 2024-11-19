<?php
include('public/session_start.php');
$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
$mysqli_comments = createDatabaseConnection($config['comments_db']);

header('Content-Type: application/json'); //treated as JSON

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

if (isset($_POST['commentText'], $_POST['recipeID'], $_POST['parentID'])) {
    $userId = $_SESSION['user_id'];
    $commentText = $_POST['commentText'];
    $recipeId = $_POST['recipeID'];
    $parentId = $_POST['parentID'] === 'NULL' ? NULL : $_POST['parentID'];
    $profilePicUrl = $_SESSION['profilePicUrl'];

    if (empty($commentText)) {
        echo json_encode(["status" => "error", "message" => "Please enter a comment."]);
        exit;
    }

    $username = $_SESSION['username'];

    $stmt = $mysqli_comments->prepare("INSERT INTO comments (recipeID, userID, commentText, parentID) 
    VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $mysqli_comments->error]);
        exit;
    }

    $stmt->bind_param("iisi", $recipeId, $userId, $commentText, $parentId);
    if ($stmt->execute()) {
        $newCommentId = $mysqli_recipes->insert_id;//get ID of the newly inserted comment
        //Increment comment count in the recipes table
        $updateStmt = $mysqli_recipes->prepare("UPDATE recipes SET commentCount = commentCount + 1 
        WHERE recipeID = ?");
        $updateStmt->bind_param("i", $recipeId);
        $updateStmt->execute();
        $updateStmt->close();

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