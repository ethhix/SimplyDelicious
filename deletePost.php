<?php
include "public/session_start.php";
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_dicussion = createDatabaseConnection($config['discussion_db']);
$mysqli = createDatabaseConnection($config['users_db']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postID'])) {
    $postID = $_POST['postID'];
    $userID = $_SESSION['user_id'];

    $query = "SELECT userID FROM discussionboard_comments_db.posts WHERE postID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $postID);
    $stmt->execute();
    $stmt->bind_result($postOwnerID);
    $stmt->fetch();
    $stmt->close();

    if ($postOwnerID == $userID) {
        //Delete the post
        $deletePostQuery = "DELETE FROM discussionboard_comments_db.posts WHERE postID = ?";
        $stmt = $mysqli->prepare($deletePostQuery);
        $stmt->bind_param('i', $postID);
        if ($stmt->execute()) {
            //Redirect to the discussion board page after deletion
            header("Location: public/recipeDiscussionBoard.php");
            exit();
        } else {
            echo "Error deleting post: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Unauthorized action.";
    }
} else {
    echo "Invalid request.";
}
?>