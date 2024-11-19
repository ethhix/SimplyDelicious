<?php
include('public/session_start.php');
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);

$response = array('status' => 'error', 'message' => 'An error occurred');

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

if (isset($_POST['recipeID'], $_POST['voteType']) && is_numeric($_POST['recipeID']) && in_array($_POST['voteType'], [0, 1, -1])) {
    $userID = $_SESSION['user_id'];
    $recipeID = $_POST['recipeID'];
    $voteType = $_POST['voteType'];

    $mysqli_recipes->begin_transaction();
    try {
        //Fetch existing vote
        $stmt = $mysqli_recipes->prepare("SELECT VoteType FROM recipe_votes WHERE UserID = ? AND RecipeID = ?");
        $stmt->bind_param("ii", $userID, $recipeID);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingVote = $result->fetch_assoc();
        $stmt->close();

        if ($existingVote) {
            if ($voteType === 0 || $existingVote['VoteType'] == $voteType) {
                //Delete vote
                $stmt = $mysqli_recipes->prepare("DELETE FROM recipe_votes WHERE UserID = ? AND RecipeID = ?");
                $stmt->bind_param("ii", $userID, $recipeID);
            } else {
                //Update vote
                $stmt = $mysqli_recipes->prepare("UPDATE recipe_votes SET VoteType = ? WHERE UserID = ? AND RecipeID = ?");
                $stmt->bind_param("iii", $voteType, $userID, $recipeID);
            }
        } else if ($voteType !== 0) {
            //Insert new vote
            $stmt = $mysqli_recipes->prepare("INSERT INTO recipe_votes (UserID, RecipeID, VoteType) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $userID, $recipeID, $voteType);
        }

        if ($stmt->execute()) {
            //Fetch updated vote counts
            $stmt = $mysqli_recipes->prepare("SELECT SUM(CASE WHEN VoteType = 1 THEN 1 ELSE 0 END) AS upvotes, 
            SUM(CASE WHEN VoteType = -1 THEN 1 ELSE 0 END) AS downvotes FROM recipe_votes WHERE RecipeID = ?");
            $stmt->bind_param("i", $recipeID);
            $stmt->execute();
            $votes = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            //Calculate new score
            $newScore = (int) $votes['upvotes'] - (int) $votes['downvotes'];

            //Update the score in the recipes table
            $stmt = $mysqli_recipes->prepare("UPDATE recipes SET score = ? WHERE recipeID = ?");
            $stmt->bind_param("ii", $newScore, $recipeID);
            $stmt->execute();
            $stmt->close();

            $mysqli_recipes->commit();
            $response = [
                'status' => 'success',
                'message' => "Vote processed successfully",
                'upvotes' => $votes['upvotes'],
                'downvotes' => $votes['downvotes'],
                'score' => $newScore
            ];
        } else {
            $mysqli_recipes->rollback();
            $response['message'] = "Error executing query: " . $stmt->error;
        }
    } catch (Exception $e) {
        $mysqli_recipes->rollback();
        $response['message'] = "Transaction failed: " . $e->getMessage();
    }
} else {
    $response['message'] = "Invalid parameters provided";
}

echo json_encode($response);
$mysqli_recipes->close();
?>