<?php
include 'public/session_start.php';
include("includes/db_connection.php");

$config = include("includes/config.php");

$mysqli_recipes = createDatabaseConnection($config['recipes_db']);

$response = ['status' => 'error', 'message' => 'An unexpected error occurred'];

if (isset($_POST['recipeID'], $_POST['isBookmarked'])) {
    $userID = $_SESSION['user_id'] ?? null;

    if ($userID === null) {
        $response['message'] = 'User not logged in';
        echo json_encode($response);
        exit;
    }

    $recipeID = $_POST['recipeID'];
    $isBookmarked = $_POST['isBookmarked'] === 'true';

    if ($isBookmarked) {
        $sql = "INSERT INTO savedrecipes (UserID, RecipeID, SaveDate) VALUES (?, ?, NOW())";
    } else {
        $sql = "DELETE FROM savedrecipes WHERE UserID = ? AND RecipeID = ?";
    }

    $stmt = $mysqli_recipes->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $userID, $recipeID);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = $isBookmarked ? 'Recipe bookmarked successfully' : 'Bookmark removed successfully';
        } else {
            $response['message'] = "Error executing statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Error preparing statement: " . $mysqli_recipes->error;
    }
    $mysqli_recipes->close();
} else {
    $response['message'] = "Necessary parameters not set";
}

echo json_encode($response);
?>