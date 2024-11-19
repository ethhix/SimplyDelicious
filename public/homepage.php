<?php
include("session_start.php");
include("../uploadRecipeModal.php");
//check if the sessions firstLogin is present or not
if (isset($_SESSION['firstLogin']) && $_SESSION['firstLogin']) {
  echo "<script>var showFirstLoginModal = true;</script>";
  //set variable to true so we know to display preference modal to new users
  $_SESSION['firstLogin'] = false;
  //set firstLogin session variable to false
} else {
  echo "<script>var showFirstLoginModal = false;</script>";
  //if firstLogin session variable is not set, set JS variable to false
}

echo $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SimplyDelicious Homepage</title>
  <link rel="stylesheet" href="../assets/css/homepagestyle.css">
  <link rel="stylesheet" href="../assets/css/preferenceModal.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Damion&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
</head>

<?php include "../includes/header.php"; ?>

<button type="button" id="uploadRecipeButton" class="floating-btn" data-toggle="modal" data-target="#createRecipeModal"
  style="display: none">
  Upload Recipe
</button>

<?php include "../includes/loginModal.php"; ?>
<?php include "../includes/signupModal.php"; ?>
<?php include "../includes/forgotUsernameModal.php"; ?>
<?php include "../includes/forgotPasswordModal.php"; ?>
<?php include '../getRecipeHighlight.php'; ?>

<body data-user-id="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
  <section class="recipe-section">
    <h2>Based On Your Dietary Preferences</h2>
    <div class="recipes-container">
      <?php
      if (isset($_SESSION['user_id'])) {
        include '../createPreferenceRecipeCards.php';
      } else {
        echo 'Please log in to view recipes.';
      }
      ?>
    </div>
    <h2>Sweet Tooth</h2>
    <div class="recipes-container">
      <?php include '../createRecipeCardsByCategory.php';
      createCategoryRecipeCards($mysqli_recipes, 5, 0, 26) ?>
    </div>
  </section>

  <?php include('../displayCategories.php'); ?>

  <h2 class='latestrecipestitle'>Recommended Recipes</h2>
  <div class="recipes-container">
    <?php
    include '../recommendedRecipesSection.php';
    ?>
  </div>
  <button class="load-more-recipes">Load More</button>
  </section>

  <script src="../assets/js/homepagescript.js"></script>
  <script src="../assets/js/preferenceModalScript.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script>

    function closeSuccessModal() {
      var successModal = document.getElementById("successModal");
      if (successModal) {
        successModal.style.display = "none";
      } else {
        console.error("Success modal element not found");
      }
    }

    $(document).ready(function () {
      //Check the flag set by PHP and show the modal if true
      if (showFirstLoginModal) {
        $('#preferenceModal').modal({ backdrop: 'static', keyboard: false });
        $('#preferenceModal').modal('show');
        localStorage.setItem('preferencesNeeded', 'true');
      }
    });

    document.addEventListener("DOMContentLoaded", function () {
      const colorThief = new ColorThief();
      const img = document.getElementById('highlight-image');

      // Ensure image is loaded before extracting color
      img.addEventListener('load', function () {
        const dominantColor = colorThief.getColor(img);
        const highlightContainer = document.querySelector('.recipe-highlight');

        // Convert the RGB array to a CSS color string
        const rgbColor = `rgb(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]})`;

        // Set the background color
        highlightContainer.style.backgroundColor = rgbColor;

        // Remove the loading class
        highlightContainer.classList.remove('loading');
      });

      // Trigger load event if the image is already cached
      if (img.complete) {
        img.dispatchEvent(new Event('load'));
      }
    });
  </script>
  <?php include "../includes/successModal.php"; ?>
  <?php include "../includes/accountCreatedSuccessModal.php"; ?>
</body>

</html>