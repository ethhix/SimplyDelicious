<?php
include 'session_start.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="../assets/css/userSettingsStyles.css">
    <link rel="stylesheet" href="../assets/css/userSettingsPreferenceModal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Damion&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<?php include '../includes/header.php'; ?>

<body>
    <div class="container">
        <div class="row">
            <!-- Settings sidebar -->
            <div class="settings col-md-4">
                <div class="settings-body">
                    <div class="settings-body__header">
                        <h3>Settings</h3>
                    </div>
                    <div class="settings-body__content">
                        <div class="settings-body__content-items">
                            <div class="settings-body__content-item">
                                <a href="#" class="settings-body__content-link"
                                    data-target="#profileContent">Profile</a>
                            </div>
                            <div class="settings-body__content-item">
                                <a href="#" class="settings-body__content-link" data-target="#recipesContent">My
                                    Recipes</a>
                            </div>
                            <div class="settings-body__content-item">
                                <a href="#" class="settings-body__content-link" data-target="#helpContent">Help</a>
                            </div>
                            <div class="settings-body__content-item">
                                <a href="../logout.php" class="settings-body__content-link">
                                    <button type="button" class="btn btn-outline-dark">Logout</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main settings content -->
            <div class="col-md-8 settings-section" id="profileContent">
                <!-- Profile Box -->
                <div class="card mb-3">
                    <div class="card-header">
                        Profile
                    </div>
                    <div class="card-body">
                        <!-- Profile Picture Section -->
                        <div class="user-profile-img mb-3">
                            <img src="..assets/images/profilePictures/profileicon.jpg" alt="user-profile-img"
                                id="profilePicture">
                            <input type="file" id="changeProfileImg" class="user" accept="image/*">
                        </div>
                        <!-- Bio Section -->
                        <div class="user-profile-bio mb-3">
                            <h5>Bio</h5>
                            <textarea name="bio" id="Bio" class="form-control" rows="3" readonly></textarea>
                        </div>
                        <!-- Username Section -->
                        <div class="user-setting mb-3">
                            <label for="username">Username</label>
                            <input type="text" id="Username" class="form-control" readonly>
                        </div>
                        <!-- Preferences Section -->
                        <a href="#preferences-modal" class="btn btn-link" id="changePreferenceBtn"
                            style="margin-top: 10px;">Change
                            Preferences</a>
                    </div>
                </div>

                <!-- Account Box -->
                <div class="card mb-3">
                    <div class="card-header">
                        Account
                    </div>
                    <div class="card-body">
                        <!-- Email Section -->
                        <div class="user-setting mb-3">
                            <label for="email">Email</label>
                            <input type="email" id="Email" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="mb-3">
                    <a href="#forgotPasswordModal" id="changePassword" class="btn btn-link">Change Password</a>
                    <a href="#delete-account-modal" class="btn btn-danger" id="deleteAccountBtn">Delete Account</a>
                </div>
            </div>

            <div id="recipesContent" class="settings-section" style="display: none;">
                <h2>My Recipes</h2>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#createdRecipes" data-toggle="tab">Created Recipes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#bookmarkedRecipes" data-toggle="tab">Bookmarked Recipes</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="createdRecipes" class="tab-pane fade show active" role="tabpanel"
                        aria-labelledby="created-recipes-tab">
                        <h3>Created Recipes</h3>
                        <div class="recipes-list">
                            <?php include "../displayCreatedRecipes.php"; ?>
                        </div>
                    </div>
                    <div id="bookmarkedRecipes" class="tab-pane fade" role="tabpanel"
                        aria-labelledby="bookmarked-recipes-tab">
                        <h3 class="bookmarkedHeader">Bookmarked Recipes</h3>
                        <div class="row recipes-list">
                            <?php include "../displayBookmarkedRecipes.php"; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Content -->
            <div id="helpContent" class="settings-section" style="display: none;">
                <!-- Include Help related content here -->
            </div>

        </div>
    </div>

    <div id="preferenceModal" class="preferenceModal" style='display:none;'>
        <!-- Modal content -->
        <div class="preference-modal-content">
            <div class="preference-modal-header">Your Preferences</div>
            <div class="preference-category dietary-preferences">
                <h3>Dietary Preferences</h3>
                <button class="preference-tag">Vegan</button>
                <button class="preference-tag">Vegetarian</button>
                <button class="preference-tag">Pescatarian</button>
                <button class="preference-tag">Paleolithic</button>
                <button class="preference-tag">Low Carb</button>
                <button class="preference-tag">Gluten-Free</button>
                <button class="preference-tag">Dairy-Free</button>
                <button class="preference-tag">Nut-Free</button>
                <button class="preference-tag">Sugar-Free</button>
                <button class="preference-tag">None</button>
            </div>
            <div class="preference-category cuisine-preferences">
                <h3>Cuisine Preferences (select 3)</h3>
                <button class="preference-tag">Italian</button>
                <button class="preference-tag">Mexican</button>
                <button class="preference-tag">Chinese</button>
                <button class="preference-tag">Indian</button>
                <button class="preference-tag">Japanese</button>
                <button class="preference-tag">Thai</button>
                <button class="preference-tag">Mediterranean</button>
                <button class="preference-tag">French</button>
                <button class="preference-tag">American</button>
                <button class="preference-tag">Greek</button>
                <div class="cuisine-group" data-index="0" style="display: none;">
                    <button class="preference-tag">Korean</button>
                    <button class="preference-tag">Vietnamese</button>
                    <button class="preference-tag">Spanish</button>
                    <button class="preference-tag">Middle Eastern</button>
                    <button class="preference-tag">Caribbean</button>
                    <button class="preference-tag">African</button>
                    <button class="preference-tag">Cuban</button>
                    <button class="preference-tag">Portuguese</button>
                    <button class="preference-tag">Indonesian</button>
                    <button class="preference-tag">Spanish</button>
                </div>
                <div class="cuisine-group" data-index="1" style="display: none;">
                    <button class="preference-tag">Peruvian</button>
                    <button class="preference-tag">Brazilian</button>
                    <button class="preference-tag">Polish</button>
                    <button class="preference-tag">Argentinian</button>
                    <button class="preference-tag">Turkish</button>
                    <button class="preference-tag">Croatian</button>
                    <button class="preference-tag">Serbian</button>
                    <button class="preference-tag">Hungarian</button>
                    <button class="preference-tag">Vietnamese</button>
                    <button class="preference-tag">Iranian</button>
                </div>
            </div>
            <button class="load-more-cuisines">Load More</button>
            <div class="preference-category ingredient-preferences">
                <div class="header-with-search">
                    <h3>Preferred Ingredients (select 5)</h3>
                    <input type="text" id="ingredientSearch" class="ingredient-search"
                        placeholder="Search ingredients...">
                </div>
                <div id="ingredients-container">
                </div>
                <!-- change these classes! -->
                <button id="loadMoreIngredients" class="load-more-btn">Load More</button>
                <button id="showLessIngredients" class="load-more-btn" style="display: none;">Show Less</button>
            </div>
            <div class="preference-category cooking-experience">
                <h3>Cooking Experience</h3>
                <button class="preference-tag">Beginner</button>
                <button class="preference-tag">Intermediate</button>
                <button class="preference-tag">Experienced</button>
                <div class="modalFooter">
                    <button id="preferenceCancelBtn" class="cancel btn" style="margin-top: 15px;">Cancel</button>
                    <button id="completeUpdatedPreferences" class="save-preferences btn" style="margin-top: 15px;">Save
                        Preferences</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel"
        aria-hidden="true" style="display:none">
        <div class="modal-dialog modal-lg" role="document" id="forgotPasswordModalDialog">
            <div class="modal-content modal-custom-height" id="forgotPasswordModalContent">
                <div class="modal-header" id="forgotPasswordModalHeader">
                    <div class="title-close-container"
                        style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                        <h5 style="font-size: 30px;" class="modal-title" id="modalTitleForgotPassword">Forgot Password
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="forgotPasswordCloseButton">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p class="modal-subtitle" id="modalSubtitleForgotPassword"
                        style="font-size: 20px; margin-top: 20px; width: 100%;">
                        Tell us the username and email associated with your SimplyDelicious account, and we’ll send you
                        an email with your password.
                    </p>
                </div>
                <div class="modal-body" id="forgotPasswordModalBody">
                    <form id="forgotPasswordForm">
                        <input type="text" id="forgotPassUsernameInput" placeholder="username" required>
                        <input type="email" id="forgotPassEmailInput" placeholder="email" required>
                        <button type="submit" id="sendPasswordButton"
                            style="padding: 10px; border-radius: 10px; margin-top: 15px; background-color: white; font-size: 20px;">
                            Send Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="deleteAccountModal modal" id="deleteAccountModal" tabindex="-1" role="dialog" style="display:hidden">
        <div class="modal-dialog" id="deleteAccountModalDialog" role="document">
            <div class="modal-content" id="deleteAccountModalContent">
                <div class="modal-header" id="deleteAccountModalHeader">
                    <h5 class="modal-title" id="deleteAccountModalTitle">Delete Account</h5>
                    <button type="button" class="close" id="closeDeleteAccountModalBtn" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="deleteAccountModalBody">
                    <p>If you proceed, your account will be deleted along with your created recipes.
                        Are you sure you would like to proceed?
                    </p>
                </div>
                <div class="modal-footer" id="deleteAccountModalFooter">
                    <button type="button" class="btn btn-danger" id="confirmDeleteAccountModalBtn">Confirm
                        Deletion</button>
                    <button type="button" class="cancel btn" id="cancelDeleteAccountModalBtn"
                        data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/userSettings.js"></script>
    <script src="../assets/js/userSettingsPreferenceModal.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>