<?php
include('public/session_start.php');
include("includes/db_connection.php");

$config = include("includes/config.php");
$mysqli = createDatabaseConnection($config['users_db']);
$response = ['error' => false]; //default response
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    //SQL statement to fetch user details
    $stmt = $mysqli->prepare("SELECT u.UserId, u.Username, u.Email, u.PasswordHash, u.firstLogin, up.ProfilePictureURL 
                              FROM users u
                              LEFT JOIN user_profiles up ON u.UserID = up.UserID
                              WHERE u.Username = ? OR u.Email = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['PasswordHash'])) { //verify if this account is found
            $_SESSION['user_id'] = $row['UserId'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['firstLogin'] = (bool) $row['firstLogin'];
            $_SESSION['profilePicUrl'] = $row['ProfilePictureURL'];

            // Log session data for debugging
            error_log("Session data after login: " . print_r($_SESSION, true));

            //Include needed information within response
            $response = [
                'error' => false,
                'firstLogin' => $_SESSION['firstLogin'],
                'userId' => $row['UserId'],
                'profilePicUrl' => $row['ProfilePictureURL']
            ];
        } else {
            $loginError = "Invalid username or password";
            $response = ['error' => true, 'errorMessage' => $loginError];
        }
    } else {
        $loginError = "Invalid username or password";
        $response = ['error' => true, 'errorMessage' => $loginError];
    }
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>