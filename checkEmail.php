<?php
include("includes/db_connection.php");

$config = include("includes/config.php");

$mysqli_users = createDatabaseConnection($config['users_db']);
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    //check if email already exists within database
    $stmt = $mysqli_users->prepare("SELECT Email FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already taken";
    } else {
        echo "";
    }
    $stmt->close();
}
?>