<?php
include("session_start.php");
include "../createRecipeCards.php";

function countRecipes($mysqli_recipes)
{
  $sql = "SELECT COUNT(*) as total FROM recipes";
  $result = $mysqli_recipes->query($sql);
  if ($row = $result->fetch_assoc()) {
    return $row['total'];
  }
  return 0; //return 0 if no recipes are found
}

$recipePerCard = 1; //assuming each card shows one recipe
$totalRecipes = countRecipes($mysqli_recipes);
$recipesPerColumn = ceil($totalRecipes / 3);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recipes Page</title>
  <link rel="stylesheet" href="../assets/css/recipesPageStyle.css">
  <link rel="stylesheet" href="../assets/css/homepagestyle.css">
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

<body data-user-id="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
  <div class="container-fluid">
    <div class="row">
      <?php include "../includes/filterSidebar.php"; ?>
      <?php include "../includes/recipeGrid.php"; ?>
    </div>
  </div>
</body>

<script src="../assets/js/recipesPageScript.js"></script>
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
          event.preventDefault(); //prevent the default link behavior
          $('#loginModal').modal('show'); //show signup modal
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