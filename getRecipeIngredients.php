<?php
header('Content-Type: application/json');

include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_ingredients = createDatabaseConnection($config['ingredients_db']);

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$search = isset($input['search']) ? $input['search'] : '';
$search = $mysqli_ingredients->real_escape_string($search); //Sanitize input
$limit = 10;

if (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt = $mysqli_ingredients->prepare("SELECT IngredientID, ingredients FROM ingredients WHERE ingredients LIKE ? LIMIT ?");
    $stmt->bind_param("si", $searchTerm, $limit);
} else {
    //Provide a meaningful default case
    $stmt = $mysqli_ingredients->prepare("SELECT IngredientID, ingredients FROM ingredients LIMIT ?");
    $stmt->bind_param("i", $limit);
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $ingredients = [];
    while ($row = $result->fetch_assoc()) {
        $ingredients[] = [
            'id' => $row['IngredientID'],
            'name' => $row['ingredients']
        ];
    }
    echo json_encode($ingredients);
} else {
    http_response_code(500);
    echo json_encode(['error' => "Failed to execute query"]);
}

$stmt->close();
$mysqli_ingredients->close();
?>