<?php
include('public/session_start.php');
$config = include("includes/config.php");
$mysqli_discussion = createDatabaseConnection($config['users_db']);
//Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to post.']);
    exit();
}

$userID = $_SESSION['user_id'];
$title = $_POST['title'];
$content = $_POST['content'];

//Validate input
if (empty($title) || empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Both title and content are required.']);
    exit();
}

//Prepare SQL statement to insert the post
if ($stmt = $mysqli_discussion->prepare("INSERT INTO posts (userID, title, content, creationDate) VALUES (?, ?, ?, NOW())")) {
    $stmt->bind_param("iss", $userID, $title, $content);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        $postID = $stmt->insert_id;  //get the ID of the newly created post

        //Prepare to display the new post
        $stmt->prepare("SELECT title, content, creationDate FROM posts WHERE postID = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();

        //Get the username
        $stmt->prepare("SELECT username FROM users_db.users WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $userResult = $stmt->get_result();
        $user = $userResult->fetch_assoc();

        //Return the post data as JSON
        echo json_encode([
            'status' => 'success',
            'postID' => $postID,
            'title' => $post['title'],
            'username' => $user['username'],
            'creationDate' => date("F j, Y, g:i a", strtotime($post['creationDate']))
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create a new post.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $mysqli_discussion->error]);
}

$mysqli_discussion->close();
?>