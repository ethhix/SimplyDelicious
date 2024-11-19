<?php
include_once '../createCategoryRecipeCards.php';
include("../includes/db_connection.php");

$config = include('../includes/config.php');
$totalRecipes = 9;
$recipesPerColumn = 3;

$mysqli_recipes = createDatabaseConnection($config['recipes_db']);

$category = isset($_GET['category']) ? $_GET['category'] : 'defaultCategory';
function getTotalRecipesInCategory($mysqli_recipes, $categoryID)
{
    $sql = "SELECT COUNT(*) AS total FROM recipes JOIN recipe_categories ON recipes.recipeID = recipe_categories.recipeID WHERE recipe_categories.categoryID = ?";
    $stmt = $mysqli_recipes->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $mysqli_recipes->error;
        return 0;
    }
    $stmt->bind_param("i", $categoryID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['total'];
}

$totalRecipes = getTotalRecipesInCategory($mysqli_recipes, $category);
$recipesPerColumn = 3;
$totalColumns = ceil($totalRecipes / $recipesPerColumn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes Page</title>
    <link rel="stylesheet" href="../assets/css/homepagestyle.css">
    <link rel="stylesheet" href="../assets/css/recipesPageStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Damion&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<?php include "../includes/header.php"; ?>
<?php include "../includes/loginModal.php"; ?>
<?php include "../includes/signupModal.php"; ?>
<?php include "../includes/forgotUsernameModal.php"; ?>
<?php include "../includes/forgotPasswordModal.php"; ?>

<body>
    <div class="container">
        <div class="row" id="recipes-grid">
            <?php
            for ($col = 0; $col < $totalColumns; $col++):
                $offset = $col * $recipesPerColumn;
                include_once '../createCategoryRecipeCards.php';
                createCategoryRecipeCards($mysqli_recipes, $recipesPerColumn, $offset, $category);
            endfor;
            ?>
        </div>
    </div>
</body>
<script src="../assets/js/categoryRecipePageScript.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        //Check if the user is logged in
        var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        var loginState = false;

        if (isLoggedIn) {
            //Hide login and signup buttons if logged in
            document.getElementById("loginButtonNav").style.display = "none";
            document.getElementById("signupButtonNav").style.display = "none";
            //Show profile container
            document.querySelector(".profile-container").style.display = "block";
        } else {
            //Show login and signup buttons
            document.getElementById("loginButtonNav").style.display = "block";
            document.getElementById("signupButtonNav").style.display = "block";
            //Hide profile container
            document.querySelector(".profile-container").style.display = "none";
        }

        //Add click event to all elements with the class 'recipe-link'
        var recipeLinks = document.querySelectorAll('.recipe-link');
        recipeLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (!isLoggedIn) {
                    event.preventDefault(); // Prevent the default link behavior
                    $('#loginModal').modal('show'); // Show signup modal
                } else {
                    //If logged in, you can navigate to the recipe page.
                    window.location.href = link.getAttribute('data-recipe-url');
                }
            });
        });

        function toggleLoginPassword() {
            var passwordInput = document.getElementById("password");
            if (loginState) {
                passwordInput.setAttribute("type", "password");
                loginState = false;
            } else {
                passwordInput.setAttribute("type", "text");
                loginState = true;
            }
        }
        window.toggleLoginPassword = toggleLoginPassword;
    });
</script>

</html>

<?php
$mysqli_recipes->close(); // Close your database connection here
?>