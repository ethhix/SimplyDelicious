<?php
include_once("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
$mysqli = createDatabaseConnection($config['users_db']);
$mysqli_ingredients = createDatabaseConnection($config['ingredients_db']);

function getCurrentUserVoteType($recipeID, $userID, $mysqli_recipes)
{
    if (!$userID) {
        return null;
    }

    $stmt = $mysqli_recipes->prepare("SELECT VoteType FROM recipe_votes WHERE RecipeID = ? AND UserID = ?");
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $mysqli_recipes->error);
        return null;
    }

    $stmt->bind_param("ii", $recipeID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $voteType = null;

    if ($row = $result->fetch_assoc()) {
        $voteType = $row['VoteType'];
    }

    $stmt->close();
    return $voteType;
}

function getUserPreferences($mysqli, $userID)
{
    $stmt = $mysqli->prepare("SELECT DietaryPreferences, CuisinePreferences, PreferredIngredients FROM users_db.user_preferences WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function createPreferredRecipeCards($mysqli, $userID, $limit, $offset)
{
    global $mysqli_recipes;
    $preferences = getUserPreferences($mysqli, $userID);
    if (!$preferences) {
        echo "User preferences not found.";
        return;
    }

    $params = [$userID];
    $types = 'i';
    $conditions = [];

    $dietaryPreferences = json_decode($preferences['DietaryPreferences'], true);
    if ($dietaryPreferences !== ["None"] && is_array($dietaryPreferences)) {
        $dietaryConditions = array_map(function ($diet) {
            return "FIND_IN_SET(?, rd.dietary_options)";
        }, $dietaryPreferences);
        $conditions[] = "(" . implode(' OR ', $dietaryConditions) . ")";
        $params = array_merge($params, $dietaryPreferences);
        $types .= str_repeat('s', count($dietaryPreferences));
    }

    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';

    $sql = "SELECT DISTINCT r.recipeID, r.recipe_title, r.commentCount, rim.ImagePath,
            COALESCE((SELECT COUNT(*) FROM savedrecipes sr WHERE sr.RecipeID = r.recipeID AND sr.UserID = ?), 0) as IsBookmarked
            FROM recipes r
            JOIN recipe_details rd ON r.recipeID = rd.recipe_id
            LEFT JOIN recipe_images rim ON r.recipeID = rim.recipeID";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }

    $sql .= " GROUP BY r.recipeID ORDER BY r.recipeID LIMIT ? OFFSET ?";

    $stmt = $mysqli_recipes->prepare($sql);
    if (!$stmt) {
        echo "SQL prepare error: " . $mysqli_recipes->error;
        return;
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<p>No recipes found matching your preferences.</p>";
        return;
    }

    while ($recipe = $result->fetch_assoc()) {
        displayRecipeCard($recipe, $userID, $mysqli_recipes);
    }
    $stmt->close();
}

function displayRecipeCard($recipe, $userID, $mysqli_recipes)
{
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

    $userVoteType = getCurrentUserVoteType($recipe['recipeID'], $userID, $mysqli_recipes);
    $upvoteActive = ($userVoteType == 1) ? 'active' : '';
    $downvoteActive = ($userVoteType == -1) ? 'active' : '';
    $checked = $recipe['IsBookmarked'] > 0 ? 'checked' : '';
    $upvotes = isset($voteCounts[$recipe['recipeID']]['upvotes']) ? $voteCounts[$recipe['recipeID']]['upvotes'] : 0;
    $downvotes = isset($voteCounts[$recipe['recipeID']]['downvotes']) ? $voteCounts[$recipe['recipeID']]['downvotes'] : 0;

    echo '<div class="recipe-card" data-recipe-id="' . $recipe['recipeID'] . '">';
    echo '<img src="' . htmlspecialchars($recipe['ImagePath']) . '" alt="' . htmlspecialchars($recipe['recipe_title']) . '">';
    echo '<label class="ui-checkbox">';
    echo '<input type="checkbox" aria-label="Bookmark this recipe" class="bookmark-checkbox" ' . $checked . '>';
    echo '<i class="fa fa-bookmark bookmark-icon"></i>';
    echo '</label>';
    echo '<div class="recipe-info">';
    echo '<h3>' . htmlspecialchars($recipe['recipe_title']) . '</h3>';
    echo '<div class="recipe-interactions">';
    echo '<span class="comments"><i class="fa fa-comment"></i> ' . htmlspecialchars($recipe['commentCount']) . ' comments</span>';
    echo '<div class="voting-buttons">';
    echo '<button class="upvote ' . $upvoteActive . '" onclick="handleVote(' . $recipe['recipeID'] . ', 1)">';
    echo '<i class="fa-solid fa-up-long"></i> <span>' . $upvotes . '</span></button>';
    echo '<button class="downvote ' . $downvoteActive . '" onclick="handleVote(' . $recipe['recipeID'] . ', -1)">';
    echo '<i class="fa-solid fa-down-long"></i> <span>' . $downvotes . '</span></button>';
    echo '</div></div></div></div>';
}

$userID = $_SESSION['user_id'] ?? null;
if ($userID) {
    createPreferredRecipeCards($mysqli, $userID, 5, 0);
} else {
    echo "User not logged in.";
}
?>