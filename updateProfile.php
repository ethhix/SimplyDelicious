<?php
include('public/session_start.php');
$config = include("includes/config.php");
$mysqli = createDatabaseConnection($config['users_db']);
header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

if (isset($_POST['field'], $_POST['value'])) {
    updateUserData($userId, $_POST['field'], $_POST['value']);
}
//Check if updating preferences
elseif (isset($_POST['dietaryPreferences'], $_POST['cuisinePreferences'], $_POST['ingredientPreferences'], $_POST['cookingExperience'])) {
    updatePreferences($userId);
} elseif (isset($_FILES['userImage'])) {
    $uploadResponse = uploadUserImage($_FILES['userImage'], $userId, $mysqli);
    echo json_encode($uploadResponse);
} else {
    fetchUserData($userId);
}

function fetchUserData($userId)
{
    global $mysqli;
    $sql = "SELECT p.ProfilePictureURL, p.Bio, u.Username, u.Email 
            FROM users u
            JOIN user_profiles p ON p.UserID = u.UserID 
            WHERE p.UserID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $response = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'No user found.']);
    }
    $stmt->close();
}

function updateUserData($userId, $field, $value)
{
    global $mysqli;
    $tableMapping = [
        'Username' => 'users',
        'Email' => 'users',
        'Bio' => 'user_profiles'
    ];

    if (!array_key_exists($field, $tableMapping)) {
        echo json_encode(['error' => 'Invalid field']);
        exit;
    }

    $table = $tableMapping[$field];
    $sql = "UPDATE {$table} SET $field = ? WHERE UserID = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'SQL error: ' . $mysqli->error]);
        exit;
    }

    $stmt->bind_param('si', $value, $userId);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Data updated successfully']);
    } else {
        echo json_encode(['error' => 'Execute error: ' . $stmt->error]);
    }


    $stmt->close();
}
function updatePreferences($userId)
{
    global $mysqli;

    $dietaryPreferences = isset($_POST['dietaryPreferences']) ? $_POST['dietaryPreferences'] : null;
    $cuisinePreferences = isset($_POST['cuisinePreferences']) ? $_POST['cuisinePreferences'] : null;
    $ingredientPreferences = isset($_POST['ingredientPreferences']) ? $_POST['ingredientPreferences'] : null;
    $cookingExperience = isset($_POST['cookingExperience']) ? $_POST['cookingExperience'] : null;

    if (!$dietaryPreferences || !$cuisinePreferences || !$ingredientPreferences || !$cookingExperience) {
        echo json_encode(['error' => 'Missing data for update']);
        return;
    }

    $sql = "UPDATE user_preferences SET DietaryPreferences = ?, CuisinePreferences = ?, PreferredIngredients = ?, CookingExperience = ? WHERE UserID = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssi", $dietaryPreferences, $cuisinePreferences, $ingredientPreferences, $cookingExperience, $userId);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Preferences updated successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to update preferences: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Database prepare error: ' . $mysqli->error]);
    }
}
function uploadUserImage($file, $userId, $mysqli, $directory = "uploads/profilePictures/")
{
    $response = array("success" => false, "error" => "", "imageUrl" => "");

    if (!isset($file)) {
        $response['error'] = "No file uploaded.";
        return $response;
    }

    $targetFile = $directory . basename($file['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $currentImageUrl = null;
    $currentImageSql = "SELECT ProfilePictureURL FROM user_profiles WHERE UserId = ?";
    $stmt = $mysqli->prepare($currentImageSql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($currentImageUrl);
        $stmt->fetch();
        $stmt->close();
    }

    $targetFile = $directory . basename($file['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    //Check if image file is an actual image
    if (!getimagesize($file['tmp_name'])) {
        $response['error'] = "File is not an image.";
        $uploadOk = 0;
    }

    if ($file['size'] > 500000) {
        $response['error'] .= " Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $response['error'] .= " Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk) {
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $response['success'] = true;
            $response['imageUrl'] = $targetFile;

            $updateSql = "UPDATE user_profiles SET ProfilePictureURL = ? WHERE UserId = ?";
            $stmt = $mysqli->prepare($updateSql);
            if ($stmt) {
                $stmt->bind_param("si", $targetFile, $userId);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    $response['dbUpdated'] = true;

                    if ($currentImageUrl && $currentImageUrl !== $response['imageUrl']) {
                        $oldFilePath = $directory . $currentImageUrl;
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                } else {
                    $response['error'] .= " Failed to update the database.";
                }
                $stmt->close();
            } else {
                $response['error'] .= " Database error: " . $mysqli->error;
            }
        } else {
            $response['error'] = "Error uploading the file.";
        }
    } else {
        $response['error'] .= " File validation failed.";
    }

    return $response;
}
?>