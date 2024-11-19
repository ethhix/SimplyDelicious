<?php

$config = include("includes/config.php");
$mysqli_comments = createDatabaseConnection($config['comments_db']);

function fetchCommentsWithReplies($postID, $mysqli_comments)
{
    // SQL query with the correct table and column names
    $sql = "SELECT c.*, u.Username, up.ProfilePictureURL, COUNT(l.likeID) AS like_count,
            EXISTS(SELECT 1 FROM discussioncomment_likes cl WHERE cl.commentID = c.commentID AND cl.userID = ?) AS user_liked,
            c.parentID
            FROM discussionboard_comments c
            JOIN users_db.users u ON c.userID = u.UserID
            JOIN users_db.user_profiles up ON u.UserID = up.UserID
            LEFT JOIN discussioncomment_likes l ON c.commentID = l.commentID
            WHERE c.postID = ?
            GROUP BY c.commentID
            ORDER BY c.parentID ASC, c.creationDate ASC";

    $stmt = $mysqli_comments->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $mysqli_comments->error;
        exit;
    }
    $stmt->bind_param("ii", $_SESSION['user_id'], $postID);
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

    foreach ($comments as $comment) {
        $comment['children'] = [];
        $commentMap[$comment['commentID']] = $comment;
    }

    foreach ($commentMap as $id => $comment) {
        if ($comment['parentID'] === NULL) {
            $commentTree[] = &$commentMap[$id];
        } else {
            $commentMap[$comment['parentID']]['children'][] = &$commentMap[$id];
        }
    }

    return $commentTree;
}

function displayComments($comments, $userID, $postID, $isNested = false)
{
    $class = $isNested ? "nested-comments-list" : "comments-list";
    echo "<ul class='{$class}'>";
    foreach ($comments as $comment) {
        $profileImage = !empty($comment['ProfilePictureURL']) ? $comment['ProfilePictureURL'] : 'path_to_default_profile_pic.jpg';
        $replyCount = count($comment['children'] ?? []);
        $userLiked = $comment['user_liked'] > 0;
        $likeClass = $userLiked ? 'liked' : '';

        echo '<li class="comment" id="comment-' . $comment['commentID'] . '">';
        echo '<div class="comment-header">';
        echo '<img src="' . htmlspecialchars($profileImage) . '" alt="Profile Picture" class="profile-pic">';
        echo '<div class="comment-author-and-time">';
        echo '<h3 class="comment-author">' . htmlspecialchars($comment['Username']) . '</h3>';
        echo '<p class="comment-time">' . htmlspecialchars($comment['creationDate']) . ' ago</p>';
        echo '</div>';
        echo '</div>';
        echo '<p class="comment-text">' . htmlspecialchars($comment['content']) . '</p>';
        echo '<div class="comment-actions">';
        echo '<a href="javascript:void(0);" class="comment-reply" onclick="showReplyForm(' . $comment['commentID'] . ', event)" style="font-size: 15px;">Reply (' . $replyCount . ')</a>';
        echo '<a href="javascript:void(0);" class="comment-likes ' . $likeClass . '" onclick="toggleLike(' . $comment['commentID'] . ')" style="font-size: 15px;">';
        echo '<i class="fas fa-heart ' . $likeClass . '"></i> ' . ($comment['like_count'] ?? 0) . '</a>';
        if ($userID == $comment['userID']) {
            echo '<button class="delete-comment btn btn-danger" data-comment-id="' . htmlspecialchars($comment['commentID']) . '">Delete</button>';
        }
        echo '</div>';
        echo '<form id="reply-form-' . $comment['commentID'] . '" class="reply-form" style="display:none;" method="post" action="submitDiscussionComment.php">
                <textarea name="commentText" required placeholder="Write your reply..."></textarea>
                <input type="hidden" name="postID" value="' . $postID . '">
                <input type="hidden" name="parentID" value="' . $comment['commentID'] . '">
                <button type="submit">Submit Reply</button>
                <button type="button" onclick="hideReplyForm(' . $comment['commentID'] . ')">Cancel</button>
              </form>';
        if (!empty($comment['children'])) {
            displayComments($comment['children'], $userID, $postID, true);
        }
        echo '</li>';
    }
    echo '</ul>';
}
?>