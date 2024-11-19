<?php
include('public/session_start.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recipe Highlight</title>
  <link rel="stylesheet" href="assets/css/recipehighlightstyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Damion&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<?php include "includes/header.php"; ?>

<body>
  <main role="main" class="container mt-4">
    <section id="recipe">
      <h1 class="recipe-title mb-4">Strawberry Cream Cheesecake</h1> <!-- Recipe title -->
      <div class="author-info">
        <img src="authorprofile.png" alt="Tricia Albert" class="author-image"> <!-- Authors profile picture -->
        <span class="author-name">Tricia Albert</span> <!-- Author username -->
        <span class="post-date"><i class="fa fa-clock"></i> Yesterday</span> <!-- Date when recipe was created -->
        <span class="likes"><i class="fa-solid fa-comment"></i>25</span> <!-- Number of comments recipe recieved -->
      </div>
      <div class="row">
        <div class="col-md-12 d-flex align-items-center">
          <div class="recipe-image flex-grow-1">
            <img src="cheesecake.png" class="img-fluid" alt="Strawberry Cream Cheesecake">
          </div>
          <div class="highlight-votebuttons d-flex flex-column align-items-center justify-content-center">
            <button class="upvote">
              <i class="fa-solid fa-up-long"></i> <span class="votes-count">120</span>
              <!-- Number of upvotes recipe recieved -->
            </button>
            <button class="downvote">
              <i class="fa-solid fa-down-long"></i> <span class="votes-count">30</span>
              <!-- Number of downvotes recieved -->
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Ingredients and Instructions -->
    <div class="row">
      <div class="col-lg-6">
        <h2 class='ingredients-title' style='margin-bottom: 20px;'>Ingredients</h2>
        <ul class='ingredientslist' style='padding-left: 0; list-style-type: none;'>
          <h5 class="ingredientslist-subsection">For The Crust</h5> <!-- subtitle for ingredients -->
          <!-- instructions -->
          <li>1 1/2 cups (170g) graham cracker crumbs</li>
          <li>2 tablespoons granulated sugar</li>
          <li>1 Tablespoon light brown sugar firmly packed</li>
          <li>5 Tablespoons (74 g) unsalted butter, melted</li>
          <h5 class="ingredientslist-subsection">For The Cheesecake</h5>
          <li>24 oz (680 g) cream cheese softened (use full-fat)</li>
          <li>1 cup (200 g) granulated sugar</li>
          <li>1/2 cup sour cream</li>
          <li>1 teaspoon vanilla extract</li>
          <li>3 large eggs</li>
          <h5 class="ingredientslist-subsection">For The Strawberry Topping</h5> <!-- subtitle for ingredients -->
          <li>1 1/2 lb (450 g) fresh strawberries hulled and sliced</li>
          <li>1 1/2 cup (100 g) granulated sugar</li>
          <li>1 ½ Tablespoon cornstarch</li>
          <li>1 Tablespoon lemon juice</li>
          <li>2 Tablespoon water</li>
          <li>1 Tablespoon butter salted or unsalted</li>
        </ul>
      </div>
      <div class="col-lg-6">
        <h2 class='instructions-title'>Instructions</h2> <!-- instructions title -->
        <ol class='instructionslist'>
          <li>
            <h5 class="instructionslist-subsection">Graham Cracker Base:</h5> <!-- subtitle for instructions -->
            <ol> <!-- first set of instructions -->
              <li>Mix 1 ½ cups (170 g) of graham cracker crumbs with 2 tablespoons of granulated sugar and 1 tablespoon
                of light brown sugar in a medium bowl.</li>
              <li>Blend in 5 tablespoons (74 g) of melted unsalted butter until the crumbs are evenly moist.</li>
              <li>Press this mixture firmly into the bottom and up the sides of a 9-inch springform pan, using the
                bottom and sides of a glass for assistance. Set this aside.</li>
            </ol>
          </li>
          <li>
            <h5>Cheesecake Filling:</h5> <!-- second subtitle for instructions -->
            <ol> <!-- second set of instructions -->
              <li>In a large bowl, use an electric mixer at medium-low speed to blend 24 oz (680 g) of softened cream
                cheese with 1 cup (200 g) of granulated sugar until smooth and well combined. Scrape the sides and
                bottom of the bowl with a spatula.</li>
              <li>Gently mix in ½ cup of sour cream and 1 teaspoon of vanilla extract.</li>
              <li>Add 3 large eggs one at a time, mixing lightly after each egg. Be careful not to over-mix to avoid the
                cheesecake puffing up during baking.</li>
              <li>Pour the filling into the crust-lined pan. Bake at 325°F (160°C) for 45-50 minutes. The cheesecake
                will be mostly set but still slightly jiggly in the center.</li>
              <li>Let the cheesecake cool at room temperature, then chill in the fridge for at least 6 hours, or
                overnight for best results. Keep the springform collar on until serving.</li>
            </ol>
          </li>
          <li>
            <h5>Strawberry Topping:</h5> <!--third subtitle for instructions -->
            <ol> <!-- third set of instructions -->
              <li>In a small saucepan, combine ¼ cup (50 g) of granulated sugar and 1 ½ tablespoons of cornstarch. Add
                half of the 1 ½ lb of strawberries (fresh or frozen), 2 tablespoons of water, and 1 tablespoon of lemon
                juice. Cook over medium/low heat, stirring constantly, until the strawberries release their juices and
                the sauce thickens.</li>
              <li>Remove from heat, stir in the remaining strawberries and 1 tablespoon of butter. Let the sauce cool,
                then pour over the chilled cheesecake.</li>
              <li>You can serve it immediately for a looser topping or let it set in the fridge for an hour for neater
                slices.</li>
            </ol>
          </li>
        </ol>
      </div>

      <!-- Nutrition Facts, aligned under Ingredients for smaller screens -->
      <div class="nutrition-facts">
        <h2>Nutrition Facts</h2>
        <div class="nutrition-item">
          <span class="nutrition-label">Calories</span>
          <span class="nutrition-value">219.9</span>
        </div>
        <div class="nutrition-item">
          <span class="nutrition-label">Total Fat</span>
          <span class="nutrition-value">10.7 g</span>
        </div>
        <div class="nutrition-item">
          <span class="nutrition-label">Saturated Fat</span>
          <span class="nutrition-value">2.2 g</span>
        </div>
        <div class="nutrition-item">
          <span class="nutrition-label">Cholesterol</span>
          <span class="nutrition-value">37.4 mg</span>
        </div>
        <div class="nutrition-item">
          <span class="nutrition-label">Sodium</span>
          <span class="nutrition-value">120.3 mg</span>
        </div>
        <div class="nutrition-item">
          <span class="nutrition-label">Potassium</span>
          <span class="nutrition-value">32.8 mg</span>
        </div>
        <div class="nutrition-item">
          <span class="nutrition-label">Total Carbohydrate</span>
          <span class="nutrition-value">22.3 g</span>
        </div>
        <div class="nutrition-item">
          <span class="nutrition-label">Sugars</span>
          <span class="nutrition-value">8.4 g</span>
        </div>
        <div class="nutrition-item">
          <span class="nutrition-label">Protein</span>
          <span class="nutrition-value">7.9 g</span>
        </div>
      </div>
      </section>

      <!-- Comments Section -->
      <section id="comments" class="comments-container">
        <h2>Comments (3)</h2>
        <div class="comment-list">
          <div class="comment">
            <div class="comment-header">
              <img src="profile.jpg" alt="Profile Picture" class="profile-pic">
              <div class="comment-author-and-time">
                <h3 class="comment-author">Jelane Uwe</h3>
                <p class="comment-time">45min ago</p>
              </div>
            </div>
            <p class="comment-text">I added some strawberry jam to the cheesecake filling and it was amazing! Thanks for
              the recipe!</p>
            <div class="comment-actions">
              <a href="#" class="comment-reply" style='font-size: 15px;'>Reply (1)
                <i class="fas fa-reply"></i>
              </a>
              <a class="comment-likes" style='cursor: pointer; font-size: 15px;'>
                <i class="fas fa-heart"></i> 48
              </a>
            </div>
            <!-- Reply Comments -->
            <div class="comment-replies">
              <div class="comment reply">
                <div class="comment-header">
                  <img src="profile2.jpg" alt="Profile Picture" class="profile-pic" style='margin-top: 10px'>
                  <div class="comment-author-and-time">
                    <h3 class="comment-author" style='margin-top: 10px; font-size: 20px;'>Alex Smith</h3>
                    <p class="comment-time">30min ago</p>
                  </div>
                </div>
                <p class="comment-text">That sounds like a great idea! Did the jam affect the baking time?</p>
                <div class="comment-actions">
                  <a href="#" class="comment-reply" style='font-size: 15px;'>Reply
                    <i class="fas fa-reply"></i>
                  </a>
                  <a class="comment-likes" style='cursor: pointer; font-size: 15px;'>
                    <i class="fas fa-heart"></i> 4
                  </a>
                </div>
              </div>
              <div class="comment reply">
                <div class="comment-header">
                  <img src="profile3.jpg" alt="Profile Picture" class="profile-pic">
                  <div class="comment-author-and-time">
                    <h3 class="comment-author">Maria Garcia</h3>
                    <p class="comment-time">20min ago</p>
                  </div>
                </div>
                <p class="comment-text">I must try this next time. Thanks for the tip!</p>
                <div class="comment-actions">
                  <a href="#" class="comment-reply" style='font-size: 15px;'>Reply
                    <i class="fas fa-reply"></i>
                  </a>
                  <a class="comment-likes" style='cursor: pointer; font-size: 15px;'>
                    <i class="fas fa-heart"></i> 2
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Comment submission form -->
        <form id="comment-form" class="comment-form">
          <textarea id="comment-input" class="form-control" placeholder="Write a comment..." rows="3"></textarea>
          <button type="submit" class="btn btn-primary" style='background-color: black;'>Post comment</button>
        </form>
      </section>

      <!-- Recommendations Section -->
      <section id="recommendations">
        <h2 class='recommended-recipes'>Recommended Recipes</h2>
        <div class="recipes-container">
          <div class="recipe-card">
            <img src="jamwafflebreakfast.jpg" alt="Healthy Waffle Jam Breakfast">
            <div class="recipe-info">
              <h3>Healthy Waffle Jam Breakfast</h3>
              <div class="recipe-interactions">
                <span class="comments">
                  <i class="fa fa-comment"></i> 12 comments
                </span>
                <div class="voting-buttons">
                  <button class="upvote">
                    <i class="fa-solid fa-up-long"></i>
                  </button>
                  <button class="downvote">
                    <i class="fa-solid fa-down-long"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="recipe-card">
            <img src="cashewveganrice.jpg" alt="Cashew Vegan Rice">
            <div class="recipe-info">
              <h3>Cashew Vegan Rice</h3>
              <div class="recipe-interactions">
                <span class="comments">
                  <i class="fa fa-comment"></i> 8 comments
                </span>
                <div class="voting-buttons">
                  <button class="upvote">
                    <i class="fa-solid fa-up-long"></i>
                  </button>
                  <button class="downvote">
                    <i class="fa-solid fa-down-long"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="recipe-card">
            <img src="smokedsalmonsaladsandwich.jpg" alt="Smoked Salmon Salad Sandwich">
            <div class="recipe-info">
              <h3>Smoked Salad Sandwich</h3>
              <div class="recipe-interactions">
                <span class="comments">
                  <i class="fa fa-comment"></i> 33 comments
                </span>
                <div class="voting-buttons">
                  <button class="upvote">
                    <i class="fa-solid fa-up-long"></i>
                  </button>
                  <button class="downvote">
                    <i class="fa-solid fa-down-long"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button class="load-more" style='margin-top: 20px; background-color: black; color: white;'>Load More</button>
      </section>
  </main>
  <script src="assets/js/highlightrecipescript.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
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
      // Hide profile container
      document.querySelector(".profile-container").style.display = "none";
    }

    //Add click event to all elements with the class 'recipe-link'
    var recipeLinks = document.querySelectorAll('.recipe-link');
    recipeLinks.forEach(function (link) {
      link.addEventListener('click', function (event) {
        if (!isLoggedIn) {
          event.preventDefault(); //Prevent the default link behavior
          $('#loginModal').modal('show'); //Show signup modal
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