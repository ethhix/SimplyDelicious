<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/uploadRecipeModalStyles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>SimplyDelicious Homepage</title>
</head>

<body>
    <div class="recipeModal" id="createRecipeModal" style="display: none;">
        <div class="modal-content">
            <!-- First Section -->
            <div id="firstSection">
                <div class="modal-header" id="firstModalHeader">
                    <h2>Create Your Recipe</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-description">
                    <h4 style="text-decoration: underline; margin-top: 20px;">Recipe Information</h4>
                    <p>This is your chance to name and publish your recipe, making it a part of
                        the SimplyDelicious community! Share your culinary art and inspire food lovers like you.</p>
                </div>
                <div class="upload-recipe-info">
                    <h5>Recipe Title</h5>
                    <input type="text" id="recipeTitle" placeholder="Enter Recipe Title..." />
                </div>
                <form id="recipeForm" method="post" enctype="multipart/form-data">
                    <div class="form-section">
                        <label for="uploadImageBtn">Recipe Cover Image</label>
                        <div id="imageUploadContainer" class="container">
                            <div class="image-slot" data-index="1">
                                <button type="button" id="uploadImageBtn" class="btn btn-primary">Upload Image</button>
                                <input type="file" id="hiddenFileInput" name="imageUpload" hidden accept="image/*">
                            </div>
                        </div>
                    </div>
                </form>
                <label class="ingredients-title">Recipe Ingredients</label>
                <div class="ingredients-section">
                    <div id="ingredientsContainer" class="ingredients-section-container scrollable-section">
                        <div class="ingredients-section-subtitle">
                            <input type="text" class="ingredient-input" placeholder="For the crust...">
                            <button type="button" id="addIngredientBtn">&#43;</button>
                            <button type="button" id="minusIngredientBtn">&#45;</button>
                            <select id="ingredientsDropdown" size="1" hidden></select>
                        </div>
                        <textarea style="resize:none;" class="ingredient-textarea" name="ingredient-input" rows="5"
                            placeholder="Enter each ingredient on a new line and select the desired ingredient from the dropdown...
flour 2cups
sugar 1/2cups
eggs 3"></textarea>
                    </div>
                </div>
                <div id="buttonContainerFirstSection">
                    <button id="firstSectionBtn" class="firstSectionBtn" style="margin-top: 15px;">Next
                        Section</button>
                </div>
            </div>

            <!-- Second Section -->
            <div class="modal-second-section" id="secondSection" style="display:none;">
                <div class="modal-header" id="secondModalHeader">
                    <h2>Create Your Recipe</h2>
                    <span class="close" id="secondClose">&times;</span>
                </div>
                <label class="instructions-title">Recipe Instructions</label>
                <div class="instructions-section">
                    <div id="instructionsContainer" class="instructions-section-container scrollable-section">
                        <div class="new-instructions-section-subtitle">
                            <div class="input-and-buttons">
                                <input type="text" class="instructions-input" placeholder="For the crust...">
                                <button type="button" id="addInstructionBtn">&#43;</button>
                                <button type="button" id="minusInstructionBtn">&#45;</button>
                            </div>
                            <textarea class="instructions-textarea" name="instruction-input" rows="5"
                                placeholder="Include each instruction on a new line..."></textarea>
                        </div>
                    </div>
                </div>
                <label style="margin-bottom: 20px;">Nutrition Facts</label>
                <div class="nutrition-facts">
                    <div class="nutrition-item">
                        <span class="nutrition-label">Calories</span>
                        <div class="value-container">
                            <input type="text" id="calories" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">g</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div class="nutrition-item">
                        <span class="nutrition-label">Total Fat</span>
                        <div class="value-container">
                            <input type="text" id="totalFat" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">g</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div class="nutrition-item">
                        <span class="nutrition-label">Saturated Fat</span>
                        <div class="value-container">
                            <input type="text" id="saturatedFat" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">g</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div class="nutrition-item">
                        <span class="nutrition-label">Cholesterol</span>
                        <div class="value-container">
                            <input type="text" id="cholesterol" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">mg</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div class="nutrition-item">
                        <span class="nutrition-label">Sodium</span>
                        <div class="value-container">
                            <input type="text" id="sodium" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">mg</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div class="nutrition-item">
                        <span class="nutrition-label">Potassium</span>
                        <div class="value-container">
                            <input type="text" id="potassium" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">mg</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div class="nutrition-item">
                        <span class="nutrition-label">Total Carbohydrate</span>
                        <div class="value-container">
                            <input type="text" id="totalCarbohydrate" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">g</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div class="nutrition-item">
                        <span class="nutrition-label">Sugars</span>
                        <div class="value-container">
                            <input type="text" id="sugars" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">g</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div class="nutrition-item">
                        <span class="nutrition-label">Protein</span>
                        <div class="value-container">
                            <input type="text" id="protein" class="nutrition-value" value="0" readonly>
                            <span class="unit-label">g</span>
                            <span class="edit-icon">&#9998;</span>
                        </div>
                    </div>
                    <div id="buttonContainer" style="display: none;">
                        <button class="btn btn-danger" id="cancelButton">Cancel</button>
                        <button id="saveButton">Save</button>
                    </div>
                </div>
                <div class="buttonWrapper">
                    <button class="btn btn-primary" id="backButton">Back</button>
                    <button class="btn btn-primary" id="thirdSectionBtn">Next Section</button>
                </div>
            </div>

            <!-- Third Section -->
            <div class="modal-section" id="thirdSection" style="display:none;">
                <div class="modal-header" id="thirdModalHeader">
                    <h2>Define Your Recipe</h2>
                    <span class="close">&times;</span>
                </div>
                <!-- Dietary Options -->
                <div class="form-section">
                    <label>Dietary Options</label>
                    <div id="modal-dietary-options" class="checkbox-group">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Vegan">
                            <label class="custom-control-label" for="Vegan">Vegan</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Vegetarian">
                            <label class="custom-control-label" for="Vegetarian">Vegetarian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Pescatarian">
                            <label class="custom-control-label" for="Pescatarian">Pescatarian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Paleolithic">
                            <label class="custom-control-label" for="Paleolithic">Paleolithic</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Low Carb">
                            <label class="custom-control-label" for="Low Carb">Low Carb</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Gluten-Free">
                            <label class="custom-control-label" for="Gluten-Free">Gluten-Free</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Dairy-Free">
                            <label class="custom-control-label" for="Dairy-Free">Dairy-Free</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Nut-Free">
                            <label class="custom-control-label" for="Nut-Free">Nut-Free</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="Sugar-Free">
                            <label class="custom-control-label" for="Sugar-Free">Sugar-Free</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input dietary-option" id="None">
                            <label class="custom-control-label" for="None">None</label>
                        </div>
                    </div>
                </div>

                <!-- Cuisines -->
                <div class="form-section">
                    <label>Cuisines</label>
                    <div id="modal-cuisine-options" class="checkbox-group">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="Italian">
                            <label class="custom-control-label" for="Italian">Italian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="Chinese">
                            <label class="custom-control-label" for="Chinese">Chinese</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="Indian">
                            <label class="custom-control-label" for="Indian">Indian</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="French">
                            <label class="custom-control-label" for="French">French</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="Spanish">
                            <label class="custom-control-label" for="Spanish">Spanish</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="Japanese">
                            <label class="custom-control-label" for="Japanese">Japanese</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="Thai">
                            <label class="custom-control-label" for="Thai">Thai</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="Mexican">
                            <label class="custom-control-label" for="Mexican">Mexican</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="American">
                            <label class="custom-control-label" for="American">American</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input cuisine-option" id="Vietnamese">
                            <label class="custom-control-label" for="Vietnamese">Vietnamese</label>
                        </div>
                        <div class="cuisine-group" data-index="0" style="display: none;">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Turkish">
                                <label class="custom-control-label" for="Turkish">Turkish</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Korean">
                                <label class="custom-control-label" for="Korean">Korean</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Mediterranean">
                                <label class="custom-control-label" for="Mediterranean">Mediterranean</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Greek">
                                <label class="custom-control-label" for="Greek">Greek</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Lebanese">
                                <label class="custom-control-label" for="Lebanese">Lebanese</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Brazilian">
                                <label class="custom-control-label" for="Brazilian">Brazilian</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Moroccan">
                                <label class="custom-control-label" for="Moroccan">Moroccan</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Argentine">
                                <label class="custom-control-label" for="Argentine">Argentine</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="British">
                                <label class="custom-control-label" for="British">British</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="German">
                                <label class="custom-control-label" for="German">German</label>
                            </div>
                        </div>
                        <div class="cuisine-group" data-index="1" style="display: none;">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Peruvian">
                                <label class="custom-control-label" for="Peruvian">Peruvian</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Portuguese">
                                <label class="custom-control-label" for="Portuguese">Portuguese</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Russian">
                                <label class="custom-control-label" for="Russian">Russian</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Malaysian">
                                <label class="custom-control-label" for="Malaysian">Malaysian</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Indonesian">
                                <label class="custom-control-label" for="Indonesian">Indonesian</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Cuban">
                                <label class="custom-control-label" for="Cuban">Cuban</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Filipino">
                                <label class="custom-control-label" for="Filipino">Filipino</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Ethiopian">
                                <label class="custom-control-label" for="Ethiopian">Ethiopian</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="South African">
                                <label class="custom-control-label" for="South African">South African</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input cuisine-option" id="Caribbean">
                                <label class="custom-control-label" for="Caribbean">Caribbean</label>
                            </div>
                        </div>
                        <div class="load-more-less-btns" id="cuisine-load-more-less-btns">
                            <button class="load-more-cuisines-button">Load More</button>
                            <button class="load-less-cuisines-button" style="display: none;">Load Less</button>
                        </div>
                    </div>

                    <!-- Meal Type -->
                    <div class="form-section">
                        <label>Meal Type</label>
                        <div id="modal-meal-type-options" class="checkbox-group">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input meal-type-option" id="Breakfast">
                                <label class="custom-control-label" for="Breakfast">Breakfast</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input meal-type-option" id="Lunch">
                                <label class="custom-control-label" for="Lunch">Lunch</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input meal-type-option" id="Dinner">
                                <label class="custom-control-label" for="Dinner">Dinner</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input meal-type-option" id="Brunch">
                                <label class="custom-control-label" for="Brunch">Brunch</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input meal-type-option" id="Dessert">
                                <label class="custom-control-label" for="Dessert">Dessert</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input meal-type-option" id="Other">
                                <label class="custom-control-label" for="Other">Other</label>
                            </div>
                        </div>
                    </div>

                    <!-- Recipe Difficulty -->
                    <div class="form-section">
                        <label>Recipe Difficulty</label>
                        <div id="modal-recipe-difficulty-options" class="checkbox-group">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" name="difficulty" class="custom-control-input recipe-difficulty"
                                    id="Beginner">
                                <label class="custom-control-label" for="Beginner">Beginner</label>
                            </div>
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" name="difficulty" class="custom-control-input recipe-difficulty"
                                    id="Medium">
                                <label class="custom-control-label" for="Medium">Medium</label>
                            </div>
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" name="difficulty" class="custom-control-input recipe-difficulty"
                                    id="Hard">
                                <label class="custom-control-label" for="Hard">Hard</label>
                            </div>
                        </div>
                    </div>

                    <!-- Preparation Time -->
                    <div class="form-group">
                        <label>Preparation Time</label>
                        <input type="range" class="form-control-range" min="5" max="240" id="modalPrepTime">
                        <output class="bubble" id="modalPrepTimeDisplay">5 min</output>
                    </div>

                    <!-- Cooking Time -->
                    <div class="form-group">
                        <label>Cooking Time</label>
                        <input type="range" class="form-control-range" min="5" max="480" id="modalCookTime">
                        <output class="bubble" id="modalCookTimeDisplay">5 min</output>
                    </div>

                    <div class="form-section">
                        <label>Cooking Method</label>
                        <div id="modal-cooking-method-options" class="checkbox-group">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Frying">
                                <label class="custom-control-label" for="Frying">Frying</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Broiling">
                                <label class="custom-control-label" for="Broiling">Broiling</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="SlowCooking">
                                <label class="custom-control-label" for="SlowCooking">Slow Cooking</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="PressureCooking">
                                <label class="custom-control-label" for="PressureCooking">Pressure Cooking</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Poaching">
                                <label class="custom-control-label" for="Poaching">Poaching</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Grilling">
                                <label class="custom-control-label" for="Grilling">Grilling</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Steaming">
                                <label class="custom-control-label" for="Steaming">Steaming</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Sautéing">
                                <label class="custom-control-label" for="Sautéing">Sautéing</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Roasting">
                                <label class="custom-control-label" for="Roasting">Roasting</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Braising">
                                <label class="custom-control-label" for="Braising">Braising</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Baking">
                                <label class="custom-control-label" for="Baking">Baking</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="Stir-frying">
                                <label class="custom-control-label" for="Stir-frying">Stir-frying</label>
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="buttonWrapper">
                            <button id="backButtonThird" class="btn btn-primary">Back</button>
                            <button id="uploadRecipeBtn" class="btn btn-primary">Upload Recipe</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/uploadRecipeModalScript.js"></script>
</body>

</html>