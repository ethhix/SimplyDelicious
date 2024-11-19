<?php
include("includes/db_connection.php");

$config = include("includes/config.php");

$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
//Fetch all categories from the categories table
$categoryStmt = $mysqli_recipes->prepare("SELECT categoryID, categoryName FROM categories");
$categoryStmt->execute();
$categoryResult = $categoryStmt->get_result();
$categoriesMap = [];

while ($category = $categoryResult->fetch_assoc()) {
    $categoriesMap[strtolower(trim($category['categoryName']))] = $category['categoryID'];
}

$categoryStmt->close();

//Fetch all recipes that have not been categorized yet
$stmt = $mysqli_recipes->prepare("
    SELECT rd.recipe_id, r.recipe_title, rd.dietary_options, rd.meal_type, rd.recipe_difficulty, rd.prep_time, rd.cooking_time 
    FROM recipe_details rd
    JOIN recipes r ON rd.recipe_id = r.recipeID
    WHERE r.recipeID NOT IN (SELECT recipeID FROM recipe_categories)
");
$stmt->execute();
$result = $stmt->get_result();

$updatedRecipes = [];
$errors = [];

while ($details = $result->fetch_assoc()) {
    $categories = assignCategories($details, $categoriesMap); //pass the details to the function

    foreach ($categories as $categoryID) {
        //Insert into the junction table
        $insertStmt = $mysqli_recipes->prepare("INSERT INTO recipe_categories (recipeID, categoryID) VALUES (?, ?)");
        $insertStmt->bind_param("ii", $details['recipe_id'], $categoryID);
        if ($insertStmt->execute()) {
            $updatedRecipes[] = ['recipe_id' => $details['recipe_id'], 'category_id' => $categoryID];
        } else {
            $errors[] = ['recipe_id' => $details['recipe_id'], 'error' => $insertStmt->error];
        }
        $insertStmt->close();
    }
}

$stmt->close();

//Output the results
echo json_encode(['status' => 'success', 'updatedRecipes' => $updatedRecipes, 'errors' => $errors]);

//Function to assign category IDs based on recipe details and title
function assignCategories($details, $categoriesMap)
{
    $categories = [];
    $title = strtolower(trim($details['recipe_title']));
    $titleWords = preg_split('/\s+/', $title);

    //Check for partial matches and exact matches within the entire title and each word
    foreach ($categoriesMap as $categoryName => $categoryID) {
        if (stripos($title, $categoryName) !== false) {
            $categories[] = $categoryID;
        }
    }

    //Additional check for exact matches per word
    foreach ($titleWords as $word) {
        foreach ($categoriesMap as $categoryName => $categoryID) {
            if ($word === $categoryName || stripos($categoryName, $word) !== false) {
                $categories[] = $categoryID;
            }
        }
    }

    //Process dietary options
    $dietaryOptions = explode(',', $details['dietary_options']);
    foreach ($dietaryOptions as $option) {
        $option = strtolower(trim($option));
        if (isset($categoriesMap[$option])) {
            $categories[] = $categoriesMap[$option];
        }
    }

    //Process meal type
    $mealType = strtolower(trim($details['meal_type']));
    if (isset($categoriesMap[$mealType])) {
        $categories[] = $categoriesMap[$mealType];
    }

    //Process difficulty levels
    $difficulty = strtolower(trim($details['recipe_difficulty']));
    if (isset($categoriesMap[$difficulty])) {
        $categories[] = $categoriesMap[$difficulty];
    }

    //Process total time
    $totalTime = $details['prep_time'] + $details['cooking_time'];
    if ($totalTime <= 30 && isset($categoriesMap['quick meals'])) {
        $categories[] = $categoriesMap['quick meals'];
    } elseif ($totalTime > 30 && isset($categoriesMap['long meals'])) {
        $categories[] = $categoriesMap['long meals'];
    }

    return array_unique($categories);
}
?>