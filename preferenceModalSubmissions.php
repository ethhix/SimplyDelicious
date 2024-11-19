<?php
include('public/session_start.php');
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli = createDatabaseConnection($config['users_db']);
//Input validation
$userId = isset($_POST['userId']) ? $_POST['userId'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $userId) {
    $dietaryPreferences = json_encode($_POST['dietaryPreferences']);
    $cuisinePreferences = json_encode($_POST['cuisinePreferences']);
    $ingredientPreferences = json_encode($_POST['ingredientPreferences']);
    $cookingExperience = $_POST['cookingExperience'];
    $userBio = $_POST['bio'];

    //Insert or update preferences
    updatePreferences($mysqli, $userId, $dietaryPreferences, $cuisinePreferences, $ingredientPreferences, $cookingExperience);
    updateFirstLoginStatus($mysqli, $userId);
    handleProfilePicture($mysqli, $userId);

    //Update bio and other user info if provided
    updateUserProfile($mysqli, $userId, $userBio);
}

$mysqli->close();

function updatePreferences($mysqli, $userId, $dietary, $cuisine, $ingredients, $cooking)
{
    $sql = "INSERT INTO user_preferences (UserId, DietaryPreferences, CuisinePreferences, PreferredIngredients, CookingExperience)
            VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE DietaryPreferences=VALUES(DietaryPreferences), CuisinePreferences=VALUES(CuisinePreferences),
            PreferredIngredients=VALUES(PreferredIngredients), CookingExperience=VALUES(CookingExperience)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("issss", $userId, $dietary, $cuisine, $ingredients, $cooking);
        $stmt->execute();
        $stmt->close();
    }
}

function updateFirstLoginStatus($mysqli, $userId)
{
    $sql = "UPDATE users SET firstLogin = FALSE WHERE UserID = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
}

function handleProfilePicture($mysqli, $userId)
{
    $defaultProfilePicUrl = "profile-icon.jpg";
    $profilePicUrl = $defaultProfilePicUrl;

    //Check if a new file was uploaded and there were no errors
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
        $profilePic = $_FILES['profilePic'];
        $targetDirectory = "uploads/profilePictures/";
        $targetFile = $targetDirectory . basename($profilePic['name']);

        //Move the uploaded file to the target directory
        if (move_uploaded_file($profilePic['tmp_name'], $targetFile)) {
            $profilePicUrl = $targetFile;
        }
    }

    //Update the profile picture URL in the database
    $sql = "UPDATE user_profiles SET ProfilePictureURL = ? WHERE UserID = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("si", $profilePicUrl, $userId);
        $stmt->execute();
        $stmt->close();

        //Update the session variable if the profile picture was successfully updated
        $_SESSION['profilePicUrl'] = $profilePicUrl;
    }
}

function updateUserProfile($mysqli, $userId, $userBio)
{
    if (!empty($userBio)) {
        $sql = "UPDATE user_profiles SET Bio = ? WHERE UserID = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("si", $userBio, $userId);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>