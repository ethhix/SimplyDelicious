<?php
include('public/session_start.php');
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
$response = ['status' => 'error', 'message' => 'An unexpected error occurred'];

//Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

//Get the recipe ID from POST data
$recipeID = $_POST['recipeID'] ?? null;

//Validate the recipe ID
if (is_null($recipeID)) {
    echo json_encode(['status' => 'error', 'message' => 'Recipe ID is required']);
    exit;
}

try {

    $sql = "DELETE FROM savedrecipes WHERE UserID = ? AND RecipeID = ?";

    $stmt = $mysqli_recipes->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $_SESSION['user_id'], $recipeID);
        $stmt->execute();

        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Bookmark removed successfully';
        } else {
            $response['message'] = 'No bookmark found or already removed';
        }
        $stmt->close();
    } else {
        $response['message'] = "SQL error: " . $mysqli_recipes->error;
    }
} catch (Exception $e) {
    $response['message'] = "Exception: " . $e->getMessage();
} finally {
    $mysqli_recipes->close();
}

echo json_encode($response);
?>