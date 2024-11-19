<?php
include("session_start.php");
include("../includes/db_connection.php");

$config = include("../includes/config.php");

$mysqli_comments = createDatabaseConnection($config['discussion_db']);
$mysqli_users = createDatabaseConnection($config['users_db']);

$query = "SELECT p.postID, p.title, u.Username, p.creationDate, COUNT(c.commentID) AS num_replies, p.userID
          FROM discussionboard_comments_db.posts p
          JOIN users_db.users u ON p.userID = u.UserID
          LEFT JOIN discussionboard_comments_db.discussionboard_comments c ON p.postID = c.postID
          GROUP BY p.postID
          ORDER BY p.creationDate DESC";

$result = $mysqli_comments->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Recipe Discussions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Damion&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/recipeDiscussionBoardStyles.css">
</head>
<?php include "../includes/header.php"; ?>

<div class="profile-container">
    <input id="toggler" type="checkbox">
    <label for="toggler">
        <img src="<?php echo isset($_SESSION['profilePicUrl']) ? $_SESSION['profilePicUrl'] : 'profile-icon.jpg'; ?>"
            style='width: 80px; height: 80px;' alt="Profile Picture">
    </label>
    <div class="dropdown" style="font-size: 30px;">
        <a href="../userSettings.php?section=profileContent">View Profile</a>
        <a href="../userSettings.php?section=recipesContent">My Recipes</a>
        <a href="../userSettings.php?section=settingsContent">Settings</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<?php include "../includes/loginModal.php"; ?>
<?php include "../includes/signupModal.php"; ?>
<?php include "../includes/forgotUsernameModal.php"; ?>
<?php include "../includes/forgotPasswordModal.php"; ?>

<body>
    <div class="container mt-5">
        <h1>Recipe Discussion Board</h1>
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary" id="discussionModalBtn" data-toggle="modal"
            data-target="#submitDiscussionModal">
            Start New Discussion
        </button>

        <!-- The Modal -->
        <div class="modal fade" id="submitDiscussionModal" tabindex="-1" role="dialog"
            aria-labelledby="submitDiscussionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="submitDiscussionModalLabel">Create New Discussion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="discussionForm">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="content">Content:</label>
                                <textarea class="form-control" id="content" name="content"
                                    placeholder="Write your discussion content here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Title</th>
                    <th>Username</th>
                    <th>Date Posted</th>
                    <th>Replies</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><a
                                href="viewDiscussion.php?post_id=<?= $row['postID'] ?>"><?= htmlspecialchars($row['title']) ?></a>
                        </td>
                        <td><?= htmlspecialchars($row['Username']) ?></td>
                        <td><?= date("F j, Y, g:i a", strtotime($row['creationDate'])) ?></td>
                        <td><?= $row['num_replies'] ?></td>
                        <td>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['userID']): ?>
                                <form action="../deletePost.php" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    <input type="hidden" name="postID" value="<?= $row['postID'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

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

    $(document).ready(function () {
        $('#discussionForm').on('submit', function (event) {
            event.preventDefault(); //Prevent the form from submitting via the browser
            $.ajax({
                url: 'submitPost.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    //Parse the response if it is JSON
                    var data = JSON.parse(response);

                    if (data.status === 'success') {
                        $('#submitDiscussionModal').modal('hide');

                        //Create a new row for the new discussion
                        var newRow = '<tr>' +
                            '<td><a href="viewDiscussion.php?post_id=' + data.postID + '">' + $('<div>').text(data.title).html() + '</a></td>' +
                            '<td>' + $('<div>').text(data.username).html() + '</td>' +
                            '<td>' + data.creationDate + '</td>' +
                            '<td>0</td>' +
                            '<td>' +
                            '<form action="deletePost.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this post?\');">' +
                            '<input type="hidden" name="postID" value="' + data.postID + '">' +
                            '<button type="submit" class="btn btn-danger btn-sm">Delete</button>' +
                            '</form>' +
                            '</td>' +
                            '</tr>';

                        //Append the new row to the table
                        $('table tbody').append(newRow);

                        //Clear the form inputs
                        $('#discussionForm')[0].reset();
                    } else {
                        alert('Failed to create a new post: ' + data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        });
    });
</script>

</html>