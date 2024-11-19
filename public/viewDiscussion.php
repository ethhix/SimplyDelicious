<?php
include('../public/session_start.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Discussion Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/discussionPageStyles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Damion&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include "../includes/header.php"; ?>

    <div class="profile-container">
        <input id="toggler" type="checkbox">
        <label for="toggler">
            <img src="<?php echo isset($_SESSION['profilePicUrl']) ? $_SESSION['profilePicUrl'] : 'profile-icon.jpg'; ?>"
                style='width: 80px; height: 80px;' alt="Profile Picture">
        </label>
        <div class="dropdown" style="font-size: 30px;">
            <a href="userSettings.php?section=profileContent">View Profile</a>
            <a href="userSettings.php?section=recipesContent">My Recipes</a>
            <a href="userSettings.php?section=settingsContent">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <?php include "../includes/loginModal.php"; ?>
    <?php include "../includes/signupModal.php"; ?>
    <?php include "../includes/forgotUsernameModal.php"; ?>
    <?php include "../includes/forgotPasswordModal.php"; ?>

    <div class="container my-5">
        <?php
        include_once("../includes/db_connection.php");

        $config = include("../includes/config.php");

        $mysqli_discussion = createDatabaseConnection($config['discussion_db']);
        $mysqli_users = createDatabaseConnection($config['users_db']);
        $postID = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
        if ($postID == 0) {
            echo "<p class='alert alert-danger'>Invalid Post ID.</p>";
            exit;
        }

        $stmt = $mysqli_discussion->prepare("SELECT p.title, p.content, p.creationDate, u.username, up.ProfilePictureURL
            FROM posts p
            JOIN users_db.users u ON p.userID = u.UserID
            JOIN users_db.user_profiles up ON u.UserID = up.UserID
            WHERE p.postID = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();

        if (!$post) {
            echo "<p class='alert alert-warning'>No such post found.</p>";
            exit;
        }
        ?>
        <div class="row">
            <div class="col-md-2">
                <!-- Profile Picture -->
                <img src="<?= htmlspecialchars($post['ProfilePictureURL']) ?>" alt="Profile Picture"
                    class="profile-pic img-thumbnail">
            </div>
            <div class="col-md-10">
                <!-- Post Content -->
                <div class='post mb-4'>
                    <h1 class='post-title'><?= htmlspecialchars($post['title']) ?></h1>
                    <div class='post-content'><?= nl2br(htmlspecialchars($post['content'])) ?></div>
                    <p class='text-muted'><small>Posted by <?= htmlspecialchars($post['username']) ?> on:
                            <?= htmlspecialchars($post['creationDate']) ?></small></p>
                </div>
            </div>
        </div>

        <?php
        echo '<h3>Add a Comment</h3>';
        echo '<form id="discussion-comment-form" class="comment-form" action="submitDiscussionComment.php" method="post">
        <textarea id="comment-input" name="commentText" class="form-control" placeholder="Write a comment..." rows="3" required></textarea>
        <input type="hidden" name="postID" value="' . htmlspecialchars($postID) . '">
        <input type="hidden" name="parentID" value="NULL">
        <button type="submit" id="postCommentBtn" class="btn btn-primary">Post Comment</button>
      </form>';
        echo '</section>';

        include '../getDiscussionComments.php';
        $comments = fetchCommentsWithReplies($postID, $mysqli_discussion);
        $organizedComments = organizeComments($comments);
        echo '<section id="comments" class="comments-container mt-4">';
        if (!empty($comments)) {
            echo '<h2>Comments (' . count($comments) . ')</h2>';
            displayComments($organizedComments, $_SESSION['user_id'] ?? null, $postID);
        } else {
            echo '<p>No comments yet.</p>';
        }

        $mysqli_discussion->close();
        ?>

    </div>
    <script src="assets/js/discussionCommentsHandler.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById("searchBtn").addEventListener("click", function () {
            var searchInput = document.getElementById("searchInput");
            if (searchInput.style.width === "100%" || searchInput.style.opacity === "1") {
                searchInput.style.width = "0";
                searchInput.style.opacity = "0";
            } else {
                searchInput.style.visibility = "visible";
                searchInput.style.width = "100%";
                searchInput.style.opacity = "1";
                searchInput.focus();
            }
        });

        document.getElementById("searchInput").addEventListener("blur", function (event) {
            if (!event.target.value.trim()) {
                event.target.style.width = "0";
                event.target.style.opacity = "0";
            }
        });

        $(document).ready(function () {
            $(".modal-trigger").click(function (event) {
                event.preventDefault();
                var targetModal = $(this).data("target-modal");
                var hideModal = $(this).data("hide-modal");

                $(hideModal).modal("hide");
                setTimeout(() => $(targetModal).modal("show"), 100);
            });

            $(".modal-form").submit(function (event) {
                event.preventDefault();
            });

            $(".btn-back").click(function () {
                var targetModal = $(this).data("target");
                $(this).closest(".modal").modal("hide");
                $(targetModal).modal("show");
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            var loginState = false;
            if (isLoggedIn) {
                document.getElementById("loginButtonNav").style.display = "none";
                document.getElementById("signupButtonNav").style.display = "none";
                document.querySelector(".profile-container").style.display = "block";
            } else {
                document.getElementById("loginButtonNav").style.display = "block";
                document.getElementById("signupButtonNav").style.display = "block";
                document.querySelector(".profile-container").style.display = "none";
                if (uploadRecipeButton) {
                    uploadRecipeButton.style.display = "none";
                }
            }
        });

        function openModal() {
            document.getElementById('submitDiscussionModal').style.display = 'flex';
            $('#submitDiscussionModal').modal({ backdrop: 'static', keyboard: false });
        }

        function closeModal() {
            document.getElementById('submitDiscussionModal').style.display = 'none';
        }

        function toggleLoginPassword() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }

        function changeEyeIcon(element) {
            element.classList.toggle("fa-eye-slash");
        }

        function toggle() {
            var passwordInput = document.getElementById("signupPassword");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }

        function myFunction(element) {
            element.classList.toggle("fa-eye-slash");
        }
    </script>
</body>

</html>