<?php
include('public/session_start.php');
$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
$mysqli = createDatabaseConnection($config['users_db']);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['imageUpload']) || $_FILES['imageUpload']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error.']);
        exit;
    }

    if (empty($_POST['recipeID'])) {
        echo json_encode(['status' => 'error', 'message' => 'Recipe ID is missing.']);
        exit;
    }

    $recipeID = $_POST['recipeID'];
    $uploadDir = 'assets/images/recipeImages/';
    $fileName = basename($_FILES['imageUpload']['name']);
    $cleanFileName = preg_replace("/[^a-zA-Z0-9.]/", "", $fileName); //clean the filename
    $filePath = $uploadDir . time() . $cleanFileName;

    if ($_FILES['imageUpload']['size'] > 5000000) {
        echo json_encode(['status' => 'error', 'message' => 'File too large.']);
        exit;
    }

    if (!move_uploaded_file($_FILES['imageUpload']['tmp_name'], $filePath)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload file.']);
        exit;
    }

    $stmt = $mysqli_recipes->prepare("INSERT INTO recipe_images (recipeID, ImagePath, AltText) VALUES (?, ?, ?)");
    $altText = "Image for Recipe " . $recipeID;
    $stmt->bind_param("iss", $recipeID, $filePath, $altText);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Image uploaded and saved successfully', 'imagePath' => $filePath]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save image info to database: ' . $stmt->error]);
    }
    $stmt->close();
    $mysqli_recipes->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>