<?php
include('public/session_start.php');
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_comments = createDatabaseConnection($config['comments_db']);

if (isset($_SESSION['user_id'], $_POST['commentID'])) {
    $userID = $_SESSION['user_id'];
    $commentID = $_POST['commentID'];

    $mysqli_comments->begin_transaction();

    try {
        $stmt = $mysqli_comments->prepare("SELECT likeID FROM comment_likes WHERE userID = ? AND commentID = ?");
        $stmt->bind_param("ii", $userID, $commentID);
        $stmt->execute();
        $result = $stmt->get_result();
        $liked = false; //flag to track if the user has liked the comment

        if ($result->fetch_assoc()) {
            //User has liked the comment, so remove the like
            $stmt = $mysqli_comments->prepare("DELETE FROM comment_likes WHERE userID = ? AND commentID = ?");
            $stmt->bind_param("ii", $userID, $commentID);
            $stmt->execute();

            //Decrement the like count in the comments table
            $stmt = $mysqli_comments->prepare("UPDATE comments SET likeCount = likeCount - 1 WHERE commentID = ?");
            $stmt->bind_param("i", $commentID);
            $stmt->execute();
        } else {
            //User hasn't liked the comment, so add the like
            $stmt = $mysqli_comments->prepare("INSERT INTO comment_likes (userID, commentID) VALUES (?, ?)");
            $stmt->bind_param("ii", $userID, $commentID);
            $stmt->execute();

            //Increment the like count in the comments table
            $stmt = $mysqli_comments->prepare("UPDATE comments SET likeCount = likeCount + 1 WHERE commentID = ?");
            $stmt->bind_param("i", $commentID);
            $stmt->execute();

            $liked = true;
        }

        $mysqli_comments->commit();

        //Prepare the response
        $response = ['liked' => $liked, 'likeCount' => fetchLikeCount($commentID, $mysqli_comments)];
        echo json_encode($response);
    } catch (Exception $e) {
        //An error occurred, rollback changes
        $mysqli_comments->rollback();
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Missing user session or comment ID.']);
}

//Function to fetch the current like count for a comment
function fetchLikeCount($commentID, $mysqli_comments)
{
    $stmt = $mysqli_comments->prepare("SELECT likeCount FROM comments WHERE commentID = ?");
    $stmt->bind_param("i", $commentID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['likeCount'] : 0;
}
?>