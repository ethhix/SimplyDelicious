<?php
include('public/session_start.php');

$config = include("includes/config.php");
$mysqli = createDatabaseConnection($config['users_db']);

$response = ["status" => "error", "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = $lastName = $email = $username = $password = "";
    $nameError = $emailError = $usernameError = $passwordError = "";
    $isValid = true;

    // Validate First Name
    if (empty($_POST["firstName"])) {
        $nameError = "First name is required";
        $isValid = false;
    } else {
        $firstName = test_inputs($_POST["firstName"]);
        if (preg_match("/\s/", $firstName)) {
            $nameError = "Only enter one word for the first name!";
            $isValid = false;
        } elseif (!preg_match("/^[a-zA-Z]*$/", $firstName)) {
            $nameError = "Only letters allowed in first name!";
            $isValid = false;
        }
    }

    // Validate Last Name
    if (empty($_POST["lastName"])) {
        $nameError = "Last name is required";
        $isValid = false;
    } else {
        $lastName = test_inputs($_POST["lastName"]);
        if (preg_match("/\s/", $lastName)) {
            $nameError = "Only enter one word for the last name!";
            $isValid = false;
        } elseif (!preg_match("/^[a-zA-Z]*$/", $lastName)) {
            $nameError = "Only letters allowed in last name!";
            $isValid = false;
        }
    }

    // Validate Email
    if (empty($_POST["email"])) {
        $emailError = "Email is required";
        $isValid = false;
    } else {
        $email = test_inputs($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Invalid email format";
            $isValid = false;
        }
    }

    // Validate Username
    if (empty($_POST["signupUsername"])) {
        $usernameError = "Username is required";
        $isValid = false;
    } else {
        $username = test_inputs($_POST["signupUsername"]);
        if (strlen($username) > 50) {
            $usernameError = "Username cannot be longer than 50 characters";
            $isValid = false;
        } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            $usernameError = "Only letters and numbers allowed in username!";
            $isValid = false;
        }
    }

    // Validate Password
    if (empty($_POST["signupPassword"])) {
        $passwordError = "Password is required";
        $isValid = false;
    } else {
        $password = test_inputs($_POST["signupPassword"]);
        if (strlen($password) < 8) {
            $passwordError = "Password must be at least 8 characters long";
            $isValid = false;
        }
    }

    // Proceed with inserting data into the database only if all validations pass
    if ($isValid) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into users table
        $stmt = $mysqli->prepare("INSERT INTO users (Username, PasswordHash, Email, JoinDate, firstLogin) VALUES (?, ?, ?, NOW(), 1)");
        if ($stmt) {
            $stmt->bind_param("sss", $username, $hashedPassword, $email);
            if ($stmt->execute()) {
                $userId = $mysqli->insert_id;
                $stmt->close();

                // Insert into user_profiles table
                $stmt = $mysqli->prepare("INSERT INTO user_profiles (UserId, FirstName, LastName, Bio, ProfilePictureURL) VALUES (?, ?, ?, '', '')");
                if ($stmt) {
                    $stmt->bind_param("iss", $userId, $firstName, $lastName);
                    if ($stmt->execute()) {
                        $response["status"] = "success";
                        $response["message"] = "Account created successfully.";
                    } else {
                        $response["message"] = "Error inserting into user_profiles.";
                    }
                    $stmt->close();
                } else {
                    $response["message"] = "Error preparing statement for user_profiles.";
                }
            } else {
                $response["message"] = "Error executing statement for users.";
            }
        } else {
            $response["message"] = "Error preparing statement for users.";
        }
    } else {
        $response["message"] = "Validation errors occurred.";
        $response["errors"] = [
            "nameError" => $nameError,
            "emailError" => $emailError,
            "usernameError" => $usernameError,
            "passwordError" => $passwordError
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();

function test_inputs($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>