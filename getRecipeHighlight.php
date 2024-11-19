<?php
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
function getRecipeHighlight($mysqli)
{
    $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-1 week'));

    $sql = "
        SELECT r.recipeID, r.recipe_title, COUNT(c.commentID) AS commentCount, r.score, ri.ImagePath
        FROM recipes r
        LEFT JOIN recipe_images ri ON r.recipeID = ri.recipeID
        LEFT JOIN comments_db.comments c ON r.recipeID = c.recipeID AND c.timeStamp >= ?
        GROUP BY r.recipeID
        ORDER BY COUNT(c.commentID) DESC, r.score DESC
        LIMIT 1";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("s", $oneWeekAgo);
    $stmt->execute();
    $result = $stmt->get_result();

    $recipe = $result->fetch_assoc();
    $stmt->close();

    return $recipe;
}

$highlightedRecipe = getRecipeHighlight($mysqli_recipes);
?>

<?php if ($highlightedRecipe): ?>
    <div class="recipe-highlight loading" style="cursor: pointer;">
        <div class="recipe-link"
            data-recipe-url="getRecipesPage.php?recipeID=<?= htmlspecialchars($highlightedRecipe['recipeID']) ?>">
            <div class="row">
                <div class="recipe-highlight-image col-lg-7 col-sm-12 p-0">
                    <img src="<?= htmlspecialchars($highlightedRecipe['ImagePath']) ?>" class="img-fluid"
                        id="highlight-image" alt="<?= htmlspecialchars($highlightedRecipe['recipe_title']) ?>">
                </div>
                <div class="col-lg-5 col-sm-12 recipe-highlight-details">
                    <h2 class="recipe-highlight-title">
                        <span class="star-container">
                            <span class="star">&#127775;</span>
                            <span class="star">&#127775;</span>
                            <span class="star">&#127775;</span>
                        </span>
                        <?= htmlspecialchars($highlightedRecipe['recipe_title']) ?>
                    </h2>
                    <p class="recipe-highlight-description">This week's most popular recipe based on interactions!</p>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>No highlighted recipe found for this week.</p>
<?php endif; ?>