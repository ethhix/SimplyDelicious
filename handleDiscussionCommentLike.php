<?php
include('public/session_start.php');
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_discussion = createDatabaseConnection($config['discussion_db']);

if (isset($_SESSION['user_id'], $_POST['commentID'])) {
    $userID = $_SESSION['user_id'];
    $commentID = $_POST['commentID'];

    $mysqli_discussion->begin_transaction();

    try {
        //Database query to check if the user has already liked the comment
        $stmt = $mysqli_discussion->prepare("SELECT likeID FROM discussioncomment_likes WHERE userID = ? AND commentID = ?");
        $stmt->bind_param("ii", $userID, $commentID);
        $stmt->execute();
        $result = $stmt->get_result();
        $liked = false;

        if ($result->fetch_assoc()) {
            //User has liked the comment, so remove the like
            $stmt = $mysqli_discussion->prepare("DELETE FROM discussioncomment_likes WHERE userID = ? AND commentID = ?");
            $stmt->bind_param("ii", $userID, $commentID);
            $stmt->execute();

            //Decrement the like count in the discussion comments table
            $stmt = $mysqli_discussion->prepare("UPDATE discussionboard_comments SET likeCount = likeCount - 1 WHERE commentID = ?");
            $stmt->bind_param("i", $commentID);
            $stmt->execute();
        } else {
            //User hasn't liked the comment, so add the like
            $stmt = $mysqli_discussion->prepare("INSERT INTO discussioncomment_likes (userID, commentID) VALUES (?, ?)");
            $stmt->bind_param("ii", $userID, $commentID);
            $stmt->execute();

            //Increment the like count in the discussion comments table
            $stmt = $mysqli_discussion->prepare("UPDATE discussionboard_comments SET likeCount = likeCount + 1 WHERE commentID = ?");
            $stmt->bind_param("i", $commentID);
            $stmt->execute();

            $liked = true;
        }

        //Commit the transaction
        $mysqli_discussion->commit();

        //Prepare the response
        $response = ['liked' => $liked, 'likeCount' => fetchLikeCount($commentID, $mysqli_discussion)];
        echo json_encode($response);
    } catch (Exception $e) {
        //An error occurred, rollback changes
        $mysqli_discussion->rollback();
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Missing user session or comment ID.']);
}

//Function to fetch the current like count for a comment
function fetchLikeCount($commentID, $mysqli_discussion)
{
    $stmt = $mysqli_discussion->prepare("SELECT likeCount FROM discussionboard_comments WHERE commentID = ?");
    $stmt->bind_param("i", $commentID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['likeCount'] : 0;
}
?>