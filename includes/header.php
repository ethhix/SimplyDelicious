<header>
    <div class="header">
        <!-- Logo -->
        <div class="header-logo">
            <a href="homepage.php">
                <img src="../assets/images/SimplyDeliciousLogo.png" alt="SimplyDelicious Logo">
            </a>
        </div>

        <!-- Navigation -->
        <div class="header-nav" style="height: 100px; overflow: visible;">
            <a class="page-link" href="recipesPage.php">Recipes</a>
            <a class="page-link" href="recipeDiscussionBoard.php">Recipe Discussions</a>
            <div class="search-container">
                <button id="searchBtn" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
                <input type="text" id="searchInput" class="search-input" placeholder="Search...">
            </div>
            <div class="auth-buttons">
                <!-- Login Button -->
                <button type="button" id="loginButtonNav" class="btn-auth" data-toggle="modal" data-target="#loginModal"
                    style='margin-top: 32px;'>
                    Login
                </button>

                <!-- Signup Button -->
                <button type="button" id="signupButtonNav" class="btn-auth" data-toggle="modal"
                    data-target="#signupModal" style='margin-top: 32px;'>
                    Sign Up
                </button>
            </div>
            <div class="profile-container" style="display: none">
                <input id="toggler" type="checkbox">
                <label for="toggler">
                    <!-- retrieve the users profilePicture when they login, if they do not have one set, display the defualt profile image -->
                    <img src="<?php echo isset($_SESSION['profilePicUrl']) ? $_SESSION['profilePicUrl'] : 'profile-icon.jpg'; ?>"
                        style='width: 80px; height: 80px;' alt="Profile Picture">
                </label>
                <div class="dropdown" style="font-size: 30px;">
                    <a href="userSettings.php?section=profileContent">View Profile</a>
                    <a href="userSettings.php?section=recipesContent">My Recipes</a>
                    <a href="userSettings.php?section=settingsContent">Settings</a>
                    <a href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if the user is logged in
        var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        var loginState = false;

        // Toggle login password visibility
        window.toggleLoginPassword = function () {
            var passwordInput = document.getElementById("password");
            if (loginState) {
                passwordInput.setAttribute("type", "password");
                loginState = false;
            } else {
                passwordInput.setAttribute("type", "text");
                loginState = true;
            }
        };

        if (isLoggedIn) {
            // Hide login and signup buttons if logged in
            document.getElementById("loginButtonNav").style.display = "none";
            document.getElementById("signupButtonNav").style.display = "none";
            // Show profile container
            document.querySelector(".profile-container").style.display = "block";
            document.querySelector("#uploadRecipeButton").style.display = "block";
        } else {
            // Show login and signup buttons
            document.getElementById("loginButtonNav").style.display = "block";
            document.getElementById("signupButtonNav").style.display = "block";
            // Hide profile container
            document.querySelector(".profile-container").style.display = "none";
            document.querySelector("#uploadRecipeButton").style.display = "none";
        }

        // Prevent viewing highlighted recipe without signing up
        var recipeLinks = document.querySelectorAll('.recipe-link');
        recipeLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (!isLoggedIn) {
                    event.preventDefault(); // Prevent the default link behavior
                    $('#loginModal').modal('show'); // Show login modal
                } else {
                    // Navigate to the recipe page if logged in
                    window.location.href = link.getAttribute('data-recipe-url');
                }
            });
        });
    });
</script>