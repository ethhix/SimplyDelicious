<?php
include("public/session_start.php");
ob_start();
include 'categoryAssignment.php';
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
header('Content-Type: application/json');

function getCategoriesMap($mysqli_recipes)
{
    $categoriesMap = [];
    $categoryQuery = "SELECT categoryID, categoryName FROM categories";
    $categoryResult = $mysqli_recipes->query($categoryQuery);
    while ($category = $categoryResult->fetch_assoc()) {
        $categoriesMap[strtolower($category['categoryName'])] = $category['categoryID'];
    }
    $categoryResult->close();
    return $categoriesMap;
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $authorID = $_SESSION['user_id'] ?? null;

    if (!$authorID) {
        $response = ['status' => 'error', 'message' => 'User not logged in'];
        echo json_encode($response);
        ob_end_flush();
        exit;
    }

    $creationDate = date('Y-m-d H:i:s');
    $lastModifiedDate = $creationDate;
    $recipeTitle = $data['recipeTitle'] ?? 'Default Title';
    $ingredientsList = $data['ingredientsList'] ?? [];
    $instructionsList = $data['instructionsList'] ?? [];
    $nutritionFacts = $data['nutritionValues'] ?? [];

    try {
        $mysqli_recipes->begin_transaction();
        $stmt = $mysqli_recipes->prepare
        ("INSERT INTO recipes (authorID, recipe_title, commentCount, creation_date, lastModifiedDate, score) 
        VALUES (?, ?, 0, ?, ?, 0)");
        $stmt->bind_param("isss", $authorID, $recipeTitle, $creationDate, $lastModifiedDate);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert recipe: " . $stmt->error);
        }
        $recipeID = $mysqli_recipes->insert_id;

        $sortOrder = 1; //reinitialize sortOrder outside the loop
        foreach ($ingredientsList as $section) {
            if (isset($section['subtitle']) && !empty($section['ingredients'])) {
                $subTitle = $section['subtitle'];
                foreach ($section['ingredients'] as $ingredient) {
                    if (isset($ingredient['id'], $ingredient['quantity']) && $ingredient['id'] && trim($ingredient['quantity']) !== '') {
                        $ingredientID = $ingredient['id'];
                        $quantity = $ingredient['quantity'];
                        $stmt = $mysqli_recipes->prepare
                        ("INSERT INTO recipeingredients (RecipeID, IngredientID, Quantity, SubSection, SortOrder) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("iissi", $recipeID, $ingredientID, $quantity, $subTitle, $sortOrder);
                        if (!$stmt->execute()) {
                            throw new Exception("Failed to insert ingredient: " . $stmt->error);
                        }
                        $sortOrder++;
                    }
                }
            }
        }

        //Insert instructions
        $sortOrder = 1; //reinitialize sortOrder for instructions
        foreach ($instructionsList as $section) {
            if (isset($section['subtitle']) && !empty($section['instructions'])) {
                $subTitle = $section['subtitle'];
                foreach ($section['instructions'] as $step) {
                    if (trim($step) !== '') {
                        $stmt = $mysqli_recipes->prepare
                        ("INSERT INTO recipe_instructions (RecipeID, Subsection, Text, SortOrder) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("issi", $recipeID, $subTitle, $step, $sortOrder);
                        if (!$stmt->execute()) {
                            throw new Exception("Failed to insert instruction: " . $stmt->error);
                        }
                        $sortOrder++;
                    }
                }
            }
        }

        //Insert nutrition facts
        $stmt = $mysqli_recipes->prepare
        ("INSERT INTO recipe_nutrition (recipeID, calories, total_fat, saturated_fat, cholesterol, sodium, potassium, total_carbohydrate, sugars, protein) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iddddddddd",
            $recipeID,
            $nutritionFacts['calories']['value'],
            $nutritionFacts['totalFat']['value'],
            $nutritionFacts['saturatedFat']['value'],
            $nutritionFacts['cholesterol']['value'],
            $nutritionFacts['sodium']['value'],
            $nutritionFacts['potassium']['value'],
            $nutritionFacts['totalCarbohydrate']['value'],
            $nutritionFacts['sugars']['value'],
            $nutritionFacts['protein']['value']
        );
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert nutrition facts: " . $stmt->error);
        }

        //Insert additional recipe details
        $stmt = $mysqli_recipes->prepare("INSERT INTO recipe_details (recipe_id, dietary_options, cuisine, meal_type, recipe_difficulty, prep_time, cooking_time, cooking_method) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "issssiis",
            $recipeID,
            $data['dietaryOptions'],
            $data['cuisines'],
            $data['mealTypes'],
            $data['recipeDifficulty'],
            $data['preparationTime'],
            $data['cookingTime'],
            $data['cookingMethods']
        );
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert recipe details: " . $stmt->error);
        }

        //Assign categories
        $categoriesMap = getCategoriesMap($mysqli_recipes);
        $categories = assignCategories([
            'recipe_title' => $recipeTitle,
            'dietary_options' => $data['dietaryOptions'],
            'meal_type' => $data['mealTypes'],
            'recipe_difficulty' => $data['recipeDifficulty'],
            'prep_time' => $data['preparationTime'],
            'cooking_time' => $data['cookingTime']
        ], $categoriesMap);

        //Insert category relationships
        foreach ($categories as $categoryID) {
            $stmt = $mysqli_recipes->prepare("INSERT INTO recipe_categories (recipeID, categoryID) VALUES (?, ?)");
            $stmt->bind_param("ii", $recipeID, $categoryID);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert category relationship: " . $stmt->error);
            }
        }

        $mysqli_recipes->commit();
        $response = ['status' => 'success', 'message' => 'Recipe created successfully', 'recipeID' => $recipeID];

    } catch (Exception $e) {
        $mysqli_recipes->rollback();
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $mysqli_recipes->close();
    }

    ob_end_clean();
    echo json_encode($response);
    exit;
} else {
    http_response_code(405);
    $response = ['status' => 'error', 'message' => 'Method not allowed'];
    ob_end_clean();
    echo json_encode($response);
    exit;
}
?>