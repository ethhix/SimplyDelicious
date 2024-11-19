<?php

$config = include("includes/config.php");
$mysqli_recipes = createDatabaseConnection($config['recipes_db']);
function fetchCategories($mysqli_recipes, $limit = 6)
{
    $sql = "SELECT categoryID, categoryName, categoryImagePath FROM categories LIMIT ?";
    $stmt = $mysqli_recipes->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $mysqli_recipes->error);
        return [];
    }
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    $stmt->close();
    return $categories;
}

$categories = fetchCategories($mysqli_recipes);
?>

<section id="popular-categories-section">
    <h2>Categories</h2>
    <div class="categories-container">
        <div class="category-card">
            <?php foreach ($categories as $category): ?>
                <a href="categoryRecipePage.php?category=<?= htmlspecialchars($category['categoryID']) ?>"
                    style="text-decoration: none;">
                    <!-- Use the image path from the database -->
                    <img src="<?= htmlspecialchars($category['categoryImagePath']) ?>"
                        alt="<?= htmlspecialchars($category['categoryName']) ?>" class="category-image" />
                    <h3 class="category-name"><?= htmlspecialchars($category['categoryName']) ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>