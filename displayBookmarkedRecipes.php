<?php
if (session_status() === PHP_SESSION_NONE) {
    include "public/session_start.php";
}

function loadBookmarkedRecipes()
{
    include("includes/db_connection.php");

    $config = include("includes/config.php");
    $mysqli_recipes = createDatabaseConnection($config['recipes_db']);

    $userID = $_SESSION['user_id'] ?? null;

    if (!$userID) {
        echo "You must be logged in to see this page.";
        return;
    }

    try {
        //Query to fetch bookmarked recipes
        $sql = "SELECT r.recipeID, r.recipe_title, r.commentCount, ri.ImagePath
                FROM savedrecipes sr
                JOIN recipes r ON sr.RecipeID = r.recipeID
                LEFT JOIN recipe_images ri ON r.recipeID = ri.recipeID
                WHERE sr.UserID = ?";

        $stmt = $mysqli_recipes->prepare($sql);
        if (!$stmt) {
            throw new Exception($mysqli_recipes->error);
        }

        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($recipe = $result->fetch_assoc()) {
                echo '<div class="recipe-card" data-recipe-id="' . $recipe['recipeID'] . '">';
                echo '<img src="' . htmlspecialchars($recipe['ImagePath']) . '" alt="' . htmlspecialchars($recipe['recipe_title']) . '">';
                echo '<label class="ui-checkbox">';
                echo '<input type="checkbox" aria-label="Unbookmark this recipe" class="bookmark-checkbox" checked>';
                echo '<i class="fa fa-bookmark bookmark-icon"></i>';
                echo '</label>';
                echo '<div class="recipe-info">';
                echo '<h3>' . htmlspecialchars($recipe['recipe_title']) . '</h3>';
                echo '<div class="recipe-interactions">';
                echo '<span class="comments"><i class="fa fa-comment"></i> ' . htmlspecialchars($recipe['commentCount']) . ' comments</span>';
                echo '</div></div></div>';
            }
        } else {
            echo '<p>You have not bookmarked any recipes yet.</p>';
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo 'Error occurred, please try again later.';
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $mysqli_recipes->close();
    }
}

loadBookmarkedRecipes();
?>