<?php
include 'createRecipeCards.php';
include 'public/session_start.php';
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);

function getFilteredRecipes($mysqli, $filters)
{
    $userID = $_SESSION['user_id'] ?? null;

    $sql = "SELECT DISTINCT r.recipeID, r.recipe_title, COALESCE(r.commentCount, 0) AS commentCount, ri.ImagePath,
            rd.dietary_options, rd.cuisine, rd.meal_type, rd.recipe_difficulty, rd.prep_time, rd.cooking_time, rd.cooking_method,
            rn.calories, rn.total_fat, rn.cholesterol, rn.sodium, rn.potassium, rn.total_carbohydrate, rn.sugars, rn.protein,
            COALESCE(SUM(CASE WHEN rv.VoteType = 1 THEN 1 ELSE 0 END), 0) AS upvotes,
            COALESCE(SUM(CASE WHEN rv.VoteType = -1 THEN 1 ELSE 0 END), 0) AS downvotes,
            rv_user.VoteType AS userVote
     FROM recipes r
     LEFT JOIN recipe_details rd ON r.recipeID = rd.recipe_id
     LEFT JOIN recipe_images ri ON r.recipeID = ri.recipeID
     LEFT JOIN recipe_nutrition rn ON r.recipeID = rn.recipeID
     LEFT JOIN recipe_votes rv ON r.recipeID = rv.RecipeID
     LEFT JOIN recipe_votes rv_user ON r.recipeID = rv_user.RecipeID AND rv_user.UserID = ?
     WHERE 1=1";

    $params = [$userID];
    $types = 'i';
    $allConditions = [];

    foreach ($filters as $key => $values) {
        if (!empty($values) && is_array($values)) { //check if values are an array and not empty
            $conditions = [];
            foreach ($values as $value) {
                if ($key === 'cuisine' || $key === 'cooking_method' || $key === 'meal_type') {
                    $conditions[] = "rd.$key = ?";
                } else if ($key === 'dietary') {
                    $conditions[] = "FIND_IN_SET(?, rd.dietary_options)";
                } else {
                    $conditions[] = "rd.$key LIKE CONCAT('%', ?, '%')";
                }
                $params[] = $value;
                $types .= 's';
            }
            if (!empty($conditions)) {
                $allConditions[] = "(" . implode(' OR ', $conditions) . ")";
            }
        }
    }

    //Handling nutrition filters separately
    foreach (['calories', 'total_fat', 'cholesterol', 'sodium', 'potassium', 'total_carbohydrate', 'sugars', 'protein'] as $nutrient) {
        if (
            isset($filters[$nutrient . '-min']) && is_numeric($filters[$nutrient . '-min']) &&
            isset($filters[$nutrient . '-max']) && is_numeric($filters[$nutrient . '-max'])
        ) {
            $allConditions[] = "rn.$nutrient BETWEEN ? AND ?";
            $params[] = $filters[$nutrient . '-min'];
            $params[] = $filters[$nutrient . '-max'];
            $types .= 'ii';
        }
    }

    if (!empty($allConditions)) {
        $sql .= " AND " . implode(' AND ', $allConditions);
    }

    $sql .= " GROUP BY r.recipeID";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        error_log("SQL prepare error: " . $mysqli->error);
        return [];
    }

    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        error_log("SQL execute error: " . $stmt->error);
        return [];
    }

    $result = $stmt->get_result();
    $fetchedResults = $result->fetch_all(MYSQLI_ASSOC);
    return $fetchedResults;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filters = [
        'dietary' => $_POST['dietary'] ?? [],
        'cuisine' => $_POST['cuisine'] ?? [],
        'cooking_method' => $_POST['cookingMethod'] ?? [],
        'meal_type' => $_POST['meals'] ?? [],
        'recipe_difficulty' => $_POST['difficulty'] ?? '',
        'calories-min' => $_POST['calories-min'] ?? '',
        'calories-max' => $_POST['calories-max'] ?? '',
        'total_fat-min' => $_POST['total_fat-min'] ?? '',
        'total_fat-max' => $_POST['total_fat-max'] ?? '',
        'protein-min' => $_POST['protein-min'] ?? '',
        'protein-max' => $_POST['protein-max'] ?? '',
        'prep_time-min' => $_POST['prep_time-min'] ?? '',
        'prep_time-max' => $_POST['prep_time-max'] ?? '',
        'cooking_time-min' => $_POST['cooking_time-min'] ?? '',
        'cooking_time-max' => $_POST['cooking_time-max'] ?? ''
    ];

    $recipes = getFilteredRecipes($mysqli_recipes, $filters);
    if ($recipes) {
        echo json_encode(['data' => $recipes]);
    } else {
        echo json_encode(['error' => 'No recipes found matching your criteria.']);
    }
} else {

}
?>