<?php
include("session_start.php");
include '../getComments.php';

$config = include('../includes/config.php');

$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
$mysqli_users = createDatabaseConnection($config['users_db']);
$mysqli_comments = createDatabaseConnection($config['comments_db']);
$mysqli_ingredients = createDatabaseConnection($config['ingredients_db']);

if (isset($_GET['recipeID']) && is_numeric($_GET['recipeID'])) {
    $recipeID = $_GET['recipeID'];

    //Fetch the main recipe details
    $recipeQuery = "SELECT *, authorID FROM recipes WHERE recipeID = ?";
    $stmt = $mysqli_recipes->prepare($recipeQuery);
    $stmt->bind_param("i", $recipeID);
    $stmt->execute();
    $recipeResult = $stmt->get_result();
    if ($recipe = $recipeResult->fetch_assoc()) {
        $authorID = $recipe['authorID'];

        //Fetch the recipe image
        $recipeImageQuery = "SELECT ImagePath FROM recipe_images WHERE recipeID = ?";
        $stmt = $mysqli_recipes->prepare($recipeImageQuery);
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $imageResult = $stmt->get_result();
        $recipeImage = $imageResult->fetch_assoc();

        //Fetch the username from the users table
        $userQuery = "SELECT Username FROM users WHERE UserID = ?";
        $userStmt = $mysqli_users->prepare($userQuery);
        $userStmt->bind_param("i", $authorID);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $user = $userResult->fetch_assoc();

        //Fetch the profile picture from the user_profiles table
        $profileQuery = "SELECT ProfilePictureURL FROM user_profiles WHERE UserID = ?";
        $profileStmt = $mysqli_users->prepare($profileQuery);
        $profileStmt->bind_param("i", $authorID);
        $profileStmt->execute();
        $profileResult = $profileStmt->get_result();
        $profile = $profileResult->fetch_assoc();

        //Fetch the comment count
        $commentCountQuery = "SELECT COUNT(*) as commentCount FROM comments WHERE recipeID = ?";
        $commentCountStmt = $mysqli_comments->prepare($commentCountQuery);
        $commentCountStmt->bind_param("i", $recipeID);
        $commentCountStmt->execute();
        $commentCountResult = $commentCountStmt->get_result();
        $commentCount = $commentCountResult->fetch_assoc();

        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
                integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link rel="stylesheet" href="../assets/css/recipehighlightstyle.css">
            <link rel="stylesheet" href="../assets/css/recipesPageStyle.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Damion&display=swap" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <title><?php echo htmlspecialchars($recipe['recipe_title']); ?></title>
        </head>

        <?php include "../includes/header.php"; ?>
        <?php include "../includes/loginModal.php"; ?>
        <?php include "../includes/signupModal.php"; ?>
        <?php include "../includes/forgotUsernameModal.php"; ?>
        <?php include "../includes/forgotPasswordModal.php"; ?>

        <body>
            <main role="main" class="container">
                <section id="recipe">
                    <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['recipe_title']); ?></h1>
                    <div class="author-info">
                        <img src="<?php echo htmlspecialchars($profile['ProfilePictureURL']); ?>" alt="" class="author-image">
                        <span class="author-name"><?php echo htmlspecialchars($user['Username']); ?></span>
                        <span class="post-date"><?php echo htmlspecialchars($recipe['creation_date']); ?></span>
                        <span class="likes"><?php echo htmlspecialchars($commentCount['commentCount']); ?> Comments</span>
                    </div>
                </section>

                <?php
                function getVoteCounts($recipeID, $mysqli_recipes)
                {
                    $query = "SELECT 
                                SUM(CASE WHEN VoteType = 1 THEN 1 ELSE 0 END) AS upvotes,
                                SUM(CASE WHEN VoteType = -1 THEN 1 ELSE 0 END) AS downvotes
                              FROM recipe_votes
                              WHERE RecipeID = ?";
                    $stmt = $mysqli_recipes->prepare($query);
                    $stmt->bind_param("i", $recipeID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $votes = $result->fetch_assoc();
                    $stmt->close();

                    return $votes ? $votes : ['upvotes' => 0, 'downvotes' => 0];
                }

                function getUserVoteType($recipeID, $userID, $mysqli_recipes)
                {
                    if (!$userID) {
                        return null;
                    }

                    $stmt = $mysqli_recipes->prepare("SELECT VoteType FROM recipe_votes WHERE RecipeID = ? AND UserID = ?");
                    $stmt->bind_param("ii", $recipeID, $userID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $stmt->close();

                    return $row ? $row['VoteType'] : null;
                }

                $voteCounts = getVoteCounts($recipeID, $mysqli_recipes);
                $userVoteType = getUserVoteType($recipeID, $_SESSION['user_id'] ?? null, $mysqli_recipes);

                $upvoteActive = ($userVoteType == 1) ? 'active' : '';
                $downvoteActive = ($userVoteType == -1) ? 'active' : '';
                ?>

                <div class="col-md-12 d-flex align-items-center">
                    <div class="recipe-image flex-grow-1">
                        <img src="<?php echo htmlspecialchars($recipeImage['ImagePath']); ?>" class="img-fluid"
                            alt="Recipe Image">
                    </div>
                    <div class="recipe-page--voting-buttons" data-recipe-id="<?php echo $recipeID; ?>">
                        <button class="recipe-page--upvote <?php echo $upvoteActive; ?>">
                            <i class="fa-solid fa-up-long"></i>
                            <span class="votes-count"><?php echo $voteCounts['upvotes']; ?></span>
                        </button>
                        <button class="recipe-page--downvote <?php echo $downvoteActive; ?>">
                            <i class="fa-solid fa-down-long"></i>
                            <span class="votes-count"><?php echo $voteCounts['downvotes']; ?></span>
                        </button>
                    </div>
                </div>
                <!-- Ingredients List -->
                <div class="recipe-details">
                    <div class="row">
                        <div class="col-lg-6 ingredients-section">
                            <h2 class='ingredients-title'>Ingredients</h2>
                            <?php
                            $ingredientsQuery = "SELECT i.ingredients, ri.Quantity, ri.Subsection FROM recipes_db.recipeingredients ri
                                             JOIN ingredients_db.ingredients i ON ri.IngredientID = i.IngredientID
                                             WHERE ri.RecipeID = ?
                                             ORDER BY ri.SortOrder ASC";
                            $ingredientsStmt = $mysqli_ingredients->prepare($ingredientsQuery);
                            $ingredientsStmt->bind_param("i", $recipeID);
                            $ingredientsStmt->execute();
                            $ingredientsResult = $ingredientsStmt->get_result();

                            $currentSubsection = null;
                            while ($ing = $ingredientsResult->fetch_assoc()) {
                                if ($currentSubsection !== $ing['Subsection']) {
                                    if ($currentSubsection !== null) {
                                        echo "</ul>";
                                    }
                                    echo "<h5 class='ingredientslist-subsection'>" . htmlspecialchars($ing['Subsection']) . "</h5>";
                                    echo "<ul class='ingredientslist' style='padding-left: 0; list-style-type: none;'>";
                                    $currentSubsection = $ing['Subsection'];
                                }
                                echo "<li>" . htmlspecialchars($ing['Quantity'] . ' ' . $ing['ingredients']) . "</li>";
                            }
                            if ($currentSubsection !== null) {
                                echo "</ul>";
                            }
                            $ingredientsResult->close();
                            $ingredientsStmt->close();
                            ?>

                            <?php
                            $nutritionQuery = "SELECT * FROM recipe_nutrition WHERE recipeID = ?";
                            $nutritionStmt = $mysqli_recipes->prepare($nutritionQuery);
                            if ($nutritionStmt) {
                                $nutritionStmt->bind_param("i", $recipeID);
                                $nutritionStmt->execute();
                                $nutritionResult = $nutritionStmt->get_result();
                                $nutrition = $nutritionResult->fetch_assoc();
                            }
                            ?>

                            <section class="nutrition-facts">
                                <h2>Nutrition Facts</h2>
                                <?php if ($nutrition): ?>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Calories</span>
                                        <span class="nutrition-value"><?php echo htmlspecialchars($nutrition['calories']); ?>
                                            kcal</span>
                                    </div>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Total Fat</span>
                                        <span class="nutrition-value"><?php echo htmlspecialchars($nutrition['total_fat']); ?>
                                            g</span>
                                    </div>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Saturated Fat</span>
                                        <span class="nutrition-value"><?php echo htmlspecialchars($nutrition['saturated_fat']); ?>
                                            g</span>
                                    </div>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Cholesterol</span>
                                        <span class="nutrition-value"><?php echo htmlspecialchars($nutrition['cholesterol']); ?>
                                            mg</span>
                                    </div>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Sodium</span>
                                        <span class="nutrition-value"><?php echo htmlspecialchars($nutrition['sodium']); ?>
                                            mg</span>
                                    </div>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Potassium</span>
                                        <span class="nutrition-value"><?php echo htmlspecialchars($nutrition['potassium']); ?>
                                            mg</span>
                                    </div>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Total Carbohydrate</span>
                                        <span
                                            class="nutrition-value"><?php echo htmlspecialchars($nutrition['total_carbohydrate']); ?>
                                            g</span>
                                    </div>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Sugars</span>
                                        <span class="nutrition-value"><?php echo htmlspecialchars($nutrition['sugars']); ?> g</span>
                                    </div>
                                    <div class="nutrition-item">
                                        <span class="nutrition-label">Protein</span>
                                        <span class="nutrition-value"><?php echo htmlspecialchars($nutrition['protein']); ?>
                                            g</span>
                                    </div>
                                <?php else: ?>
                                    <p>No nutrition information available.</p>
                                <?php endif; ?>
                            </section>
                        </div>
                        <!-- Instructions List -->
                        <div class="col-lg-6 instructions-section">
                            <h2 class='instructions-title'>Instructions</h2>
                            <ol class='instructionslist'>
                                <?php
                                $instructionsQuery = "SELECT * FROM recipe_instructions WHERE RecipeID = ? ORDER BY SortOrder ASC";
                                $instructionsStmt = $mysqli_recipes->prepare($instructionsQuery);
                                $instructionsStmt->bind_param("i", $recipeID);
                                $instructionsStmt->execute();
                                $instructionsResult = $instructionsStmt->get_result();

                                $currentSubsection = null;
                                while ($inst = $instructionsResult->fetch_assoc()) {
                                    if ($currentSubsection !== $inst['Subsection']) {
                                        if ($currentSubsection !== null) {
                                            echo "</ol></li>"; //close the previous list of steps and list item
                                        }
                                        //Start a new list item and a new ordered list for the subsection
                                        echo "<h5 class='instructions-subsection'>" . htmlspecialchars($inst['Subsection']) . "</h5>";
                                        echo "<ol class='instructions-list' style='padding-left: 0; list-style-type: decimal;'>";
                                        $currentSubsection = $inst['Subsection'];
                                    }
                                    //Add each step within the subsection
                                    echo "<li>" . htmlspecialchars($inst['Text']) . "</li>";
                                }
                                if ($currentSubsection !== null) {
                                    echo "</ol></li>"; //close the last list of steps and list item
                                }
                                $instructionsResult->close();
                                $instructionsStmt->close();
                                ?>
                            </ol>
                        </div>
                    </div>
                </div>
                <section id="comments" class="comments-container">
                    <?php
                    if (isset($recipeID) && $recipeID) {
                        //Fetch comments
                        $comments = fetchCommentsWithReplies($recipeID, $mysqli_comments);
                        $organizedComments = organizeComments($comments);

                        //Check if comments exist
                        if (!empty($comments)) {
                            echo '<h2>Comments (' . count($comments) . ')</h2>';
                            //Call this with organized comments
                            displayComments($organizedComments, $_SESSION['user_id'] ?? null, $recipeID);
                        } else {
                            echo '<h2>Comments (0)</h2>';
                            echo '<p>No comments yet.</p>';
                            echo '<ul class="comments-list">';
                        }
                    }
                    ?>

                    <!-- Comment submission form -->
                    <form id="comment-form" class="comment-form" action="submitComment.php" method="post">
                        <textarea id="comment-input" name="commentText" class="form-control" placeholder="Write a comment..."
                            rows="3" required></textarea>
                        <input type="hidden" name="recipeID" value="<?php echo htmlspecialchars($recipeID); ?>">
                        <input type="hidden" name="parentID" value="NULL"> <!-- This field will change for replies -->
                        <button type="submit" class="btn btn-primary" style="background-color: black;">Post Comment</button>
                    </form>
                </section>
            </main>
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
            <script src="../assets/js/recipePageScript.js"></script>
            <script>
                function deleteComment(commentID) {
                    if (confirm('Are you sure you want to delete this comment?')) {
                        fetch('deleteComment.php?commentID=' + commentID)
                            .then(response => response.text())
                            .then(data => {
                                alert(data);
                            })
                            .catch(error => console.error('Error:', error));
                    }
                }

                function showReplyForm(commentID) {
                    var form = document.getElementById('reply-form-' + commentID);
                    if (form.style.display === 'block') {
                        form.style.display = 'none'; //hide form if already visible
                    } else {
                        form.style.display = 'block'; //show form if not visible
                    }
                }

                document.addEventListener("DOMContentLoaded", function () {
                    //Check if the user is logged in
                    var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
                    var loginState = false;

                    if (isLoggedIn) {
                        //Hide login and signup buttons if logged in
                        document.getElementById("loginButtonNav").style.display = "none";
                        document.getElementById("signupButtonNav").style.display = "none";
                        // Show profile container
                        document.querySelector(".profile-container").style.display = "block";
                    } else {
                        //Show login and signup buttons
                        document.getElementById("loginButtonNav").style.display = "block";
                        document.getElementById("signupButtonNav").style.display = "block";
                        //Hide profile container
                        document.querySelector(".profile-container").style.display = "none";
                    }

                    //Add click event to all elements with the class 'recipe-link'
                    var recipeLinks = document.querySelectorAll('.recipe-link');
                    recipeLinks.forEach(function (link) {
                        link.addEventListener('click', function (event) {
                            if (!isLoggedIn) {
                                event.preventDefault();
                                $('#loginModal').modal('show');
                            } else {
                                window.location.href = link.getAttribute('data-recipe-url');
                            }
                        });
                    });

                    function toggleLoginPassword() {
                        var passwordInput = document.getElementById("password");
                        if (loginState) {
                            passwordInput.setAttribute("type", "password");
                            loginState = false;
                        } else {
                            passwordInput.setAttribute("type", "text");
                            loginState = true;
                        }
                    }
                    window.toggleLoginPassword = toggleLoginPassword;
                });
            </script>
        </body>

        </html>
        <?php
    } else {
        echo "Recipe not found.";
    }
    $recipeResult->close();
    $stmt->close();
    $mysqli_recipes->close();
    $mysqli_ingredients->close();
    $nutritionStmt->close();
} else {
    echo "Invalid recipe ID or recipe not found.";
}
?>