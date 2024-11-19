<?php
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli_users = createDatabaseConnection($config['users_db']);

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    //check if username already exists within database
    $stmt = $mysqli_users->prepare("SELECT Username FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Username already taken";
    } else {
        echo ""; //empty for valid username
    }
    $stmt->close();
}
?>