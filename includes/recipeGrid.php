<div class="col-md-9" id="recipes-section">
    <div class="sort-by-container">
        <label for="sort-by">Sort by:</label>
        <select id="sort-by">
            <option value="popularity">Most Popular</option>
            <option value="newest">Newest</option>
            <option value="quickest">Quickest</option>
        </select>
    </div>
    <div class="row" id="recipes-grid">
        <?php for ($col = 0; $col < 3; $col++): ?>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <?php
                $start = $col * $recipesPerColumn;
                $end = min($start + $recipesPerColumn, $totalRecipes);
                for ($i = $start; $i < $end; $i++) {
                    createRecipeCards($mysqli_recipes, $recipePerCard, $i);
                }
                ?>
            </div>
        <?php endfor; ?>
        <?php $mysqli_recipes->close(); ?>
    </div>
</div>