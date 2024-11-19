<?php

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
$mysqli = createDatabaseConnection($config['users_db']);

function getUserDietaryPreferences($userID, $mysqli)
{
    $stmt = $mysqli->prepare("SELECT DietaryPreferences, CuisinePreferences FROM users_db.user_preferences WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $preferences = [];

    while ($row = $result->fetch_assoc()) {
        $dietaryPreferences = json_decode($row['DietaryPreferences'], true);
        $cuisinePreferences = json_decode($row['CuisinePreferences'], true);
        if (is_array($dietaryPreferences)) {
            $preferences['dietary'] = $dietaryPreferences;
        }
        if (is_array($cuisinePreferences)) {
            $preferences['cuisine'] = $cuisinePreferences;
        }
    }

    $stmt->close();
    return $preferences;
}

function getUserInteractedRecipeIDs($userID, $mysqli_recipes)
{
    $stmt = $mysqli_recipes->prepare("
        SELECT DISTINCT RecipeID
        FROM (
            SELECT RecipeID FROM recipe_votes WHERE UserID = ?
            UNION
            SELECT RecipeID FROM comments_db.comments WHERE UserID = ?
        ) AS interacted_recipes
    ");
    $stmt->bind_param("ii", $userID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipeIDs = [];

    while ($row = $result->fetch_assoc()) {
        $recipeIDs[] = $row['RecipeID'];
    }

    $stmt->close();
    return $recipeIDs;
}

function createRecommendedRecipeCards($mysqli_recipes, $mysqli, $limit, $offset)
{
    $userID = $_SESSION['user_id'] ?? null;
    if (!$userID) {
        echo '<p>Please log in to see recommended recipes.</p>';
        return;
    }

    $preferences = getUserDietaryPreferences($userID, $mysqli);
    $interactedRecipeIDs = getUserInteractedRecipeIDs($userID, $mysqli_recipes);

    $dietaryPreferences = $preferences['dietary'] ?? [];

    $params = [$userID]; //user ID for bookmark check
    $types = 'i';

    $sql = "SELECT r.recipeID, r.recipe_title, r.commentCount, ri.ImagePath, 
                   (SELECT COUNT(*) FROM savedrecipes sr WHERE sr.RecipeID = r.recipeID AND sr.UserID = ?) as IsBookmarked,
                   SUM(CASE WHEN rv.VoteType = 1 THEN 1 ELSE 0 END) AS upvotes,
                   SUM(CASE WHEN rv.VoteType = -1 THEN 1 ELSE 0 END) AS downvotes
            FROM recipes r
            LEFT JOIN recipe_images ri ON r.recipeID = ri.recipeID
            LEFT JOIN recipe_votes rv ON r.recipeID = rv.RecipeID
            JOIN recipe_details rd ON r.recipeID = rd.recipe_id
            WHERE 1=1";

    if (!empty($interactedRecipeIDs)) {
        $placeholders = implode(',', array_fill(0, count($interactedRecipeIDs), '?'));
        $sql .= " AND r.recipeID NOT IN ($placeholders)";
        $params = array_merge($params, $interactedRecipeIDs);
        $types .= str_repeat('i', count($interactedRecipeIDs));
    }

    if (!empty($dietaryPreferences)) {
        $dietaryPlaceholders = implode(' OR ', array_map(function () {
            return "FIND_IN_SET(?, rd.dietary_options)";
        }, $dietaryPreferences));
        $sql .= " AND ($dietaryPlaceholders)";
        $params = array_merge($params, $dietaryPreferences);
        $types .= str_repeat('s', count($dietaryPreferences));
    }

    $sql .= " GROUP BY r.recipeID ORDER BY r.recipeID LIMIT ? OFFSET ?";
    $params = array_merge($params, [$limit, $offset]);
    $types .= 'ii';

    $stmt = $mysqli_recipes->prepare($sql);
    if (!$stmt) {
        echo "SQL prepare error: " . $mysqli_recipes->error;
        return;
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($recipe = $result->fetch_assoc()) {
            $userVoteType = getCurrentUserVoteType($recipe['recipeID'], $userID, $mysqli_recipes);
            $upvoteActive = ($userVoteType == 1) ? 'active' : '';
            $downvoteActive = ($userVoteType == -1) ? 'active' : '';
            $checked = $recipe['IsBookmarked'] > 0 ? 'checked' : '';

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
            echo '<i class="fa-solid fa-up-long"></i> <span>' . $recipe['upvotes'] . '</span></button>';
            echo '<button class="downvote ' . $downvoteActive . '" onclick="handleVote(' . $recipe['recipeID'] . ', -1)">';
            echo '<i class="fa-solid fa-down-long"></i> <span>' . $recipe['downvotes'] . '</span></button>';
            echo '</div></div></div></div>';
        }
    } else {
        echo '<p>No recommended recipes found.</p>';
    }
    $stmt->close();
}
?>

<?php
createRecommendedRecipeCards($mysqli_recipes, $mysqli, 5, 0);
?>