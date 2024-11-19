<?php
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_ingredients = createDatabaseConnection($config['ingredients_db']);

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$search = isset($input['search']) ? $input['search'] : '';
$offset = isset($input['offset']) ? (int) $input['offset'] : 0;
$limit = 10;

if (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt = $mysqli_ingredients->prepare("SELECT ingredients FROM ingredients WHERE ingredients LIKE ? LIMIT ?");
    $stmt->bind_param("si", $searchTerm, $limit);
} else {
    $stmt = $mysqli_ingredients->prepare("SELECT ingredients FROM ingredients LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

$ingredients = [];
while ($row = $result->fetch_assoc()) {
    $ingredients[] = $row['ingredients'];
}

$stmt->close();
$mysqli_ingredients->close();

echo json_encode($ingredients);
?>