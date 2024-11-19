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
                <input type="text" id="ingredientSearch" class="ingredient-search" placeholder="Search ingredients...">
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
            <div class="modal-footer">
                <button id="preferencesNextButton" class="preferences-next-btn" style="margin-top: 15px;"></button>
            </div>
        </div>
        <div id="profilePicInput" class="profile-info" style='display: none;'>
            <h3>Profile Picture</h3>
            <img id="profilePicDisplay" src="profile-icon.jpg" alt="Profile Picture" class="profile-pic-display">
            <input type="file" id="profilePicUpload" class="profile-pic-upload" accept="image/*">
        </div>

        <div id="bioInput" class="profile-info" style="display: none;">
            <h3>Your Bio</h3>
            <textarea id="bio" class="bio" placeholder="Tell us about yourself..."></textarea>
        </div>

        <div class="modal-footer">
            <button id="completeProfile" class="save-preferences-btn" style="display: none; margin-top: 15px;">Complete
                Profile</button>
        </div>
    </div>
</div>