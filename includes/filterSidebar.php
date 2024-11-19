<div class="sidebar col-md-3 px-0 show">
    <div class="sidebar__inner">
        <form method="POST" action="filterRecipes.php" id="all-filters-form">
            <div class="filter-body">
                <h2 class="border-bottom filter-title">Dietary Filter</h2>
                <div class="mb-30 filter-options" id='dietary-options'
                    style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Vegan" name="dietary[]" value="Vegan">
                        <label class="custom-control-label" for="Vegan">Vegan</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Vegetarian" name="dietary[]"
                            value="Vegetarian">
                        <label class="custom-control-label" for="Vegetarian">Vegetarian</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Pescatarian" name="dietary[]"
                            value="Pescatarian">
                        <label class="custom-control-label" for="Pescatarian">Pescatarian</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Paleolithic" name="dietary[]"
                            value="Paleolithic">
                        <label class="custom-control-label" for="Paleolithic">Paleolithic</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Low Carb" name="dietary[]"
                            value="Low Carb">
                        <label class="custom-control-label" for="Low Carb">Low Carb</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Gluten-Free" name="dietary[]"
                            value="Gluten-Free">
                        <label class="custom-control-label" for="Gluten-Free">Gluten-Free</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Dairy-Free" name="dietary[]"
                            value="Dairy-Free">
                        <label class="custom-control-label" for="Dairy-Free">Dairy-Free</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Nut-Free" name="dietary[]"
                            value="Nut-Free">
                        <label class="custom-control-label" for="Nut-Free">Nut-Free</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Sugar-Free" name="dietary[]"
                            value="Sugar-Free">
                        <label class="custom-control-label" for="Sugar-Free">Sugar-Free</label>
                    </div>
                </div>
                <h2 class="font-xbold body-font border-bottom filter-title">Cuisines</h2>
                <div class="mb-3 filter-options" id="cuisine-options">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Italian" name="cuisine[]"
                            value="Italian">
                        <label class="custom-control-label" for="Italian">Italian</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Chinese" name="cuisine[]"
                            value="Chinese">
                        <label class="custom-control-label" for="Chinese">Chinese</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Indian" name="cuisine[]" value="Indian">
                        <label class="custom-control-label" for="Indian">Indian</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="French" name="cuisine[]" value="French">
                        <label class="custom-control-label" for="French">French</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Spanish" name="cuisine[]"
                            value="Spanish">
                        <label class="custom-control-label" for="Spanish">Spanish</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Japanese" name="cuisine[]"
                            value="Japanese">
                        <label class="custom-control-label" for="Japanese">Japanese</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Thai" name="cuisine[]" value="Thai">
                        <label class="custom-control-label" for="Thai">Thai</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Mexican" name="cuisine[]"
                            value="Mexican">
                        <label class="custom-control-label" for="Mexican">Mexican</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="American" name="cuisine[]"
                            value="American">
                        <label class="custom-control-label" for="American">American</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Vietnamese" name="cuisine[]"
                            value="Vietnamese">
                        <label class="custom-control-label" for="Vietnamese">Vietnamese</label>
                    </div>
                    <div class="cuisine-group" data-index="0" style="display: none;">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Turkish" name="cuisine[]"
                                value="Turkish">
                            <label class="custom-control-label" for="Turkish">Turkish</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Korean" name="cuisine[]"
                                value="Korean">
                            <label class="custom-control-label" for="Korean">Korean</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Mediterranean" name="cuisine[]"
                                value="Mediterranean">
                            <label class="custom-control-label" for="Mediterranean">Mediterranean</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Greek" name="cuisine[]"
                                value="Greek">
                            <label class="custom-control-label" for="Greek">Greek</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Lebanese" name="cuisine[]"
                                value="Lebanese">
                            <label class="custom-control-label" for="Lebanese">Lebanese</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Brazilian" name="cuisine[]"
                                value="Brazilian">
                            <label class="custom-control-label" for="Brazilian">Brazilian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Moroccan" name="cuisine[]"
                                value="Moroccan">
                            <label class="custom-control-label" for="Moroccan">Moroccan</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Argentine" name="cuisine[]"
                                value="Argentine">
                            <label class="custom-control-label" for="Argentine">Argentine</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="British" name="cuisine[]"
                                value="British">
                            <label class="custom-control-label" for="British">British</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="German" name="cuisine[]"
                                value="German">
                            <label class="custom-control-label" for="German">German</label>
                        </div>
                    </div>
                    <div class="cuisine-group" data-index="1" style="display: none;">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Peruvian" name="cuisine[]"
                                value="Peruvian">
                            <label class="custom-control-label" for="Peruvian">Peruvian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Portuguese" name="cuisine[]"
                                value="Portuguese">
                            <label class="custom-control-label" for="Portuguese">Portuguese</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Russian" name="cuisine[]"
                                value="Russian">
                            <label class="custom-control-label" for="Russian">Russian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Malaysian" name="cuisine[]"
                                value="Malaysian">
                            <label class="custom-control-label" for="Malaysian">Malaysian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Indonesian" name="cuisine[]"
                                value="Indonesian">
                            <label class="custom-control-label" for="Indonesian">Indonesian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Cuban" name="cuisine[]"
                                value="Cuban">
                            <label class="custom-control-label" for="Cuban">Cuban</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Filipino" name="cuisine[]"
                                value="Filipino">
                            <label class="custom-control-label" for="Filipino">Filipino</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Ethiopian" name="cuisine[]"
                                value="Ethiopian">
                            <label class="custom-control-label" for="Ethiopian">Ethiopian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="South African" name="cuisine[]"
                                value="South African">
                            <label class="custom-control-label" for="South African">South African</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Caribbean" name="cuisine[]"
                                value="Carribbean">
                            <label class="custom-control-label" for="Caribbean">Caribbean</label>
                        </div>
                    </div>
                </div>
                <div class="load-more-less-btns" id="cuisine-load-more-less-btns">
                    <button type="button" class="load-more-cuisines-button">Load More</button>
                    <button type="button" class="load-less-cuisines-button" style='display: none;'>Load Less</button>
                </div>
                <h2 class="font-xbold body-font border-bottom filter-title">Preparation Time</h2>
                <div class="mb-3 theme-clr xs2-font d-flex justify-content-between">
                    <span id="slider-range-value1">5min</span>
                    <span id="slider-range-value2">240min</span>
                </div>
                <div class="mb-30 filter-options">
                    <div class="form-group">
                        <input type="range" class="form-control-range" name="preparation_time" min="5" max="240"
                            id="preparation-time-slider">
                        <output for="preparation-time-slider" id="preparation-time-output">120</output>
                    </div>
                </div>

                <h2 class="font-xbold body-font border-bottom filter-title">Cooking Time</h2>
                <div class="mb-3 theme-clr xs2-font d-flex justify-content-between">
                    <span id="slider-range-value1-cooking">5min</span>
                    <span id="slider-range-value2-cooking">480min</span>
                </div>
                <div class="mb-30 filter-options">
                    <div class="form-group">
                        <input type="range" class="form-control-range" name="cooking_time" min="5" max="480"
                            id="cooking-time-slider">
                        <output for="cooking-time-slider" id="cooking-time-output">240</output>
                    </div>
                </div>
                <h2 class="border-bottom filter-title">Meals</h2>
                <div class="mb-3 filter-options" id="services-options"
                    style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Breakfast" name="meals[]"
                            value="Breakfast">
                        <label class="custom-control-label" for="Breakfast">Breakfast</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Lunch" name="meals[]" value="Lunch">
                        <label class="custom-control-label" for="Lunch">Lunch</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Dinner" name="meals[]" value="Dinner">
                        <label class="custom-control-label" for="Dinner">Dinner</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Brunch" name="meals[]" value="Brunch">
                        <label class="custom-control-label" for="Brunch">Brunch</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Dessert" name="meals[]" value="Dessert">
                        <label class="custom-control-label" for="Dessert">Dessert</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="other" name="meals[]" value="Other">
                        <label class="custom-control-label" for="other">Other</label>
                    </div>
                </div>
                <h2 class="font-xbold body-font border-bottom filter-title">Recipe Difficulty</h2>
                <div class="recipe-diff-container" style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Beginner" name="difficulty[]"
                            value="Beginner">
                        <label class="custom-control-label" for="Beginner">Beginner</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Medium" name="difficulty[]"
                            value="Medium">
                        <label class="custom-control-label" for="Medium">Medium</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Hard" name="difficulty[]" value="Hard">
                        <label class="custom-control-label" for="Hard">Hard</label>
                    </div>
                </div>
                <h2 class="font-xbold body-font border-bottom filter-title">Nutrition Filter</h2>
                <div class="nutrition-filter-container">
                    <div class="nutrition-filter">
                        <label for="calories-min">Min Calories:</label>
                        <input type="number" id="calories-min" name="calories-min" placeholder="0" min="0">
                        <label for="calories-max">Max Calories:</label>
                        <input type="number" id="calories-max" name="calories-max" placeholder="1000" min="0">
                    </div>
                    <div class="nutrition-filter">
                        <label for="protein-min">Min Protein (g):</label>
                        <input type="number" id="protein-min" name="protein-min" placeholder="0">
                        <label for="protein-max">Max Protein (g):</label>
                        <input type="number" id="protein-max" name="protein-max" placeholder="100">
                    </div>
                    <div class="nutrition-filter">
                        <label for="fat-min">Min Fat (g):</label>
                        <input type="number" id="fat-min" name="fat-min" placeholder="0">
                        <label for="fat-max">Max Fat (g):</label>
                        <input type="number" id="fat-max" name="fat-max" placeholder="100">
                    </div>
                    <div class="nutrition-filter">
                        <label for="carbs-min">Min Carbs (g):</label>
                        <input type="number" id="carbs-min" name="carbs-min" placeholder="0">
                        <label for="carbs-max">Max Carbs (g):</label>
                        <input type="number" id="carbs-max" name="carbs-max" placeholder="100">
                    </div>
                </div>
                <div class="preference-category ingredient-preferences">
                    <div class="border-bottom header-with-search">
                        <h2 class="font-xbold body-font" style="margin-top: 15px;">Preferred Ingredients</h2>
                        <input type="text" id="ingredientSearch" class="ingredient-search"
                            placeholder="Search ingredients...">
                    </div>
                    <div id="ingredients-container">
                    </div>
                    <!-- change these classes! -->
                    <div class="ingredient-load-btns" style="display: flex;">
                        <button type="button" id="loadMoreIngredients" class="load-more-btn">Load More</button>
                        <button type="button" id="showLessIngredients" class="load-more-btn" style="display: none;">Show
                            Less</button>
                    </div>
                </div>
                <h2 class="font-xbold body-font border-bottom filter-title">Cooking Method</h2>
                <div class="mb-3 filter-options" id="cooking-options"
                    style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Baking" name="cookingMethod[]"
                            value="Baking">
                        <label class="custom-control-label" for="Baking">Baking</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Boiling" name="cookingMethod[]"
                            value="Boiling">
                        <label class="custom-control-label" for="Boiling">Boiling</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Steaming" name="cookingMethod[]"
                            value="Steaming">
                        <label class="custom-control-label" for="Steaming">Steaming</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Grilling" name="cookingMethod[]"
                            value="Grilling">
                        <label class="custom-control-label" for="Grilling">Grilling</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Sautéing" name="cookingMethod[]"
                            value="Sautéing">
                        <label class="custom-control-label" for="Sautéing">Sautéing</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="Roasting" name="cookingMethod[]"
                            value="Roasting">
                        <label class="custom-control-label" for="Roasting">Roasting</label>
                    </div>
                    <div class="cooking-group" data-index="0" style="display: none;">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Frying" name="cookingMethod[]"
                                value="Frying">
                            <label class="custom-control-label" for="Frying">Frying</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Broiling" name="cookingMethod[]"
                                value="Broiling">
                            <label class="custom-control-label" for="Broiling">Broiling</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="SlowCooking" name="cookingMethod[]"
                                value="SlowCooking">
                            <label class="custom-control-label" for="SlowCooking">Slow Cooking</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="PressureCooking"
                                name="cookingMethod[]" value="PressureCooking">
                            <label class="custom-control-label" for="PressureCooking">Pressure Cooking</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Poaching" name="cookingMethod[]"
                                value="Poaching">
                            <label class="custom-control-label" for="Poaching">Poaching</label>
                        </div>
                    </div>
                    <div class="cooking-group" data-index="1" style="display: none;">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Braising" name="cookingMethod[]"
                                value="Braising">
                            <label class="custom-control-label" for="Braising">Braising</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="StirFrying" name="cookingMethod[]"
                                value="Stirfrying">
                            <label class="custom-control-label" for="StirFrying">Stir Frying</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Smoking" name="cookingMethod[]"
                                value="Smoking">
                            <label class="custom-control-label" for="Smoking">Smoking</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="SousVide" name="cookingMethod[]"
                                value="SousVide">
                            <label class="custom-control-label" for="SousVide">Sous Vide</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="Blanching" name="cookingMethod[]"
                                value="Blanching">
                            <label class="custom-control-label" for="Blanching">Blanching</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="AirFrying" name="cookingMethod[]"
                                value="AirFrying">
                            <label class="custom-control-label" for="AirFrying">Air Frying</label>
                        </div>
                    </div>
                    <div class="cooking-btn-container" style="display: flex;">
                        <button type="button" class="load-more-cooking-button">Load More</button>
                        <button type="button" class="load-less-cooking-button" style="display: none;">Load Less</button>
                    </div>
                </div>
                <div class="apply-button-footer">
                    <button type="submit" class="apply-nutrition-button">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>
</div>