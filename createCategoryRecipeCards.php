<?php
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
function getCurrentUserVoteTypes($recipeID, $userID, $mysqli)
{
    if (!$userID) {
        return null;
    }

    $stmt = $mysqli->prepare("SELECT VoteType FROM recipe_votes WHERE RecipeID = ? AND UserID = ?");
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $mysqli->error);
        return null;
    }

    $stmt->bind_param("ii", $recipeID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $voteType = null;  //Default to null if no vote is found

    if ($row = $result->fetch_assoc()) {
        $voteType = $row['VoteType'];  //Assign the vote type if found
    }

    $stmt->close();
    return $voteType;  //Return the found or default vote type
}

// Function to create recipe cards, now includes category filtering
function createCategoryRecipeCards($mysqli, $limit, $offset, $category)
{
    global $mysqli_recipes;
    $userID = $_SESSION['user_id'] ?? null;
    $foundRecipes = false;  // Flag to track if any recipes are found

    $voteCounts = array();
    $query = "SELECT RecipeID, 
                     SUM(CASE WHEN VoteType = 1 THEN 1 ELSE 0 END) AS upvotes,
                     SUM(CASE WHEN VoteType = -1 THEN 1 ELSE 0 END) AS downvotes
              FROM recipe_votes
              GROUP BY RecipeID";
    $result_votes = $mysqli_recipes->query($query);

    while ($row = $result_votes->fetch_assoc()) {
        $voteCounts[$row['RecipeID']] = array(
            'upvotes' => $row['upvotes'],
            'downvotes' => $row['downvotes']
        );
    }

    try {
        $sql = "SELECT DISTINCT r.recipeID, r.recipe_title, r.commentCount, ri.ImagePath, 
                (SELECT COUNT(*) FROM savedrecipes sr WHERE sr.RecipeID = r.recipeID AND sr.UserID = ?) as IsBookmarked
        FROM recipes r
        JOIN recipe_categories rc ON r.recipeID = rc.recipeID
        LEFT JOIN recipe_images ri ON r.recipeID = ri.recipeID
        WHERE rc.categoryID = ?
        ORDER BY r.recipeID
        LIMIT ? OFFSET ?";

        $stmt = $mysqli_recipes->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $mysqli_recipes->error);
        }

        $stmt->bind_param("isii", $userID, $category, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $foundRecipes = true;  // Set flag to true as recipes are found
            while ($recipe = $result->fetch_assoc()) {
                $userVoteType = getCurrentUserVoteTypes($recipe['recipeID'], $userID, $mysqli_recipes);
                $upvoteActive = ($userVoteType == 1) ? 'active' : '';
                $downvoteActive = ($userVoteType == -1) ? 'active' : '';
                $checked = $recipe['IsBookmarked'] > 0 ? 'checked' : '';

                $upvotes = isset($voteCounts[$recipe['recipeID']]['upvotes']) ? $voteCounts[$recipe['recipeID']]['upvotes'] : 0;
                $downvotes = isset($voteCounts[$recipe['recipeID']]['downvotes']) ? $voteCounts[$recipe['recipeID']]['downvotes'] : 0;

                echo '<div class="recipe-card" data-recipe-id="' . $recipe['recipeID'] . '">';
                echo '<img src="' . htmlspecialchars($recipe['ImagePath']) . '" alt="' . htmlspecialchars($recipe['recipe_title']) . '">';
                echo '<label class="ui-checkbox">';
                echo '<input type="checkbox" aria-label="Bookmark this recipe" class="bookmark-checkbox" onchange="bookmarkRecipe(this);" ' . $checked . '>';
                echo '<i class="fa fa-bookmark bookmark-icon"></i>';
                echo '</label>';
                echo '<div class="recipe-info">';
                echo '<h3>' . htmlspecialchars($recipe['recipe_title']) . '</h3>';
                echo '<div class="recipe-interactions">';
                echo '<span class="comments"><i class="fa fa-comment"></i> ' . htmlspecialchars($recipe['commentCount']) . ' comments</span>';
                echo '<div class="voting-buttons">';
                echo '<button class="upvote ' . $upvoteActive . '" onclick="handleVote(' . $recipe['recipeID'] . ', 1)"><i class="fa-solid fa-up-long"></i> <span>' . $upvotes . '</span></button>';
                echo '<button class="downvote ' . $downvoteActive . '" onclick="handleVote(' . $recipe['recipeID'] . ', -1)"><i class="fa-solid fa-down-long"></i> <span>' . $downvotes . '</span></button>';
                echo '</div></div></div></div>';
            }
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo 'Error occurred: ' . htmlspecialchars($e->getMessage());
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
    //Check the flag and print the message if no recipes were found
    if (!$foundRecipes) {
        echo '<p>No recipes found in this category.</p>';
    }
}
?>