<?php
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_comments = createDatabaseConnection($config['comments_db']);

function fetchCommentsWithReplies($recipeID, $mysqli_comments)
{
    $sql = "SELECT c.*, u.Username, up.ProfilePictureURL, COUNT(l.likeID) AS like_count,
    EXISTS(SELECT 1 FROM comments_db.comment_likes cl WHERE cl.commentID = c.commentID AND cl.userID = ?) 
    AS user_liked,
    c.parentID
    FROM comments_db.comments c
    JOIN users_db.users u ON c.userID = u.UserID
    JOIN users_db.user_profiles up ON u.UserID = up.UserID
    LEFT JOIN comments_db.comment_likes l ON c.commentID = l.commentID
    WHERE c.recipeID = ?
    GROUP BY c.commentID
    ORDER BY c.parentID ASC, c.timeStamp ASC";

    $stmt = $mysqli_comments->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $mysqli_comments->error;
        exit;
    }
    $stmt->bind_param("ii", $_SESSION['user_id'], $recipeID);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    $stmt->close();
    return $comments;
}
function organizeComments($comments)
{
    $commentTree = [];
    $commentMap = [];

    //First pass: collect all comments by id and initialize children
    foreach ($comments as $comment) {
        $comment['children'] = [];
        $commentMap[$comment['commentID']] = $comment;
    }

    //Second pass: build a tree by assigning children to parents
    foreach ($commentMap as $id => $comment) {
        if ($comment['parentID'] === NULL) {
            $commentTree[] = &$commentMap[$id];
        } else {
            $commentMap[$comment['parentID']]['children'][] = &$commentMap[$id];
        }
    }

    return $commentTree;
}

function displayComments($comments, $userID, $recipeID, $isNested = false)
{
    $class = $isNested ? "nested-comments-list" : "comments-list"; //apply different classes for nested lists
    echo "<ul class='{$class}'>";
    foreach ($comments as $comment) {
        $profileImage = $comment['ProfilePictureURL'] ?? '\uploads\profilePictures\profile-icon.jpg';
        $replyCount = count($comment['children'] ?? []); //count replies
        $userLiked = $comment['user_liked'] > 0; //check if the user liked the comment
        $likeStyle = $userLiked ? 'style="color:red;"' : 'style="color:none;"'; //style for liked comments

        echo '<li class="comment" id="comment-' . $comment['commentID'] . '">';
        echo '<div class="comment-header">';
        echo '<img src="' . htmlspecialchars($profileImage) . '" alt="Profile Picture" class="profile-pic">';
        echo '<div class="comment-author-and-time">';
        echo '<h3 class="comment-author">' . htmlspecialchars($comment['Username']) . '</h3>';
        echo '<p class="comment-time">' . htmlspecialchars($comment['timeStamp']) . ' ago</p>';
        echo '</div>';
        echo '</div>';
        echo '<p class="comment-text">' . htmlspecialchars($comment['commentText']) . '</p>';
        echo '<div class="comment-actions">';
        echo '<a href="javascript:void(0);" class="comment-reply" onclick="showReplyForm(' . $comment['commentID'] . ', event)" style="font-size: 15px;">Reply (' . $replyCount . ')</a>';
        echo '<a href="javascript:void(0);" class="comment-likes" onclick="toggleLike(' . $comment['commentID'] . ')" ' . $likeStyle . '>';
        echo '<i class="fas fa-heart"></i> ' . ($comment['like_count'] ?? 0) . '</a>';
        if ($userID == $comment['userID']) {
            echo '<button class="delete-comment btn btn-danger" data-comment-id="' . htmlspecialchars($comment['commentID']) . '">Delete</button>';
        }
        echo '</div>';
        echo '<form id="reply-form-' . $comment['commentID'] . '" class="reply-form" style="display:none;" method="post" action="submitComment.php">
                <textarea name="commentText" required placeholder="Write your reply..."></textarea>
                <input type="hidden" name="recipeID" value="' . $recipeID . '">
                <input type="hidden" name="parentID" value="' . $comment['commentID'] . '">
                <button type="submit">Submit Reply</button>
                <button type="button" onclick="hideReplyForm(' . $comment['commentID'] . ')">Cancel</button>
              </form>';
        if (!empty($comment['children'])) {
            //Recursive call to display child comments
            displayComments($comment['children'], $userID, $recipeID, true);
        }
        echo '</li>';
    }
    echo '</ul>';
}
?>