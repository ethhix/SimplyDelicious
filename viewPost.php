<?php
$config = include("includes/config.php");
$mysqli = createDatabaseConnection($config['users_db']);
$post_id = $_GET['post_id'] ?? 0;

$postQuery = $mysqli->prepare("SELECT p.title, p.content, u.username, p.creation_date FROM posts p JOIN users u ON p.user_id = u.user_id WHERE p.post_id = ?");
$postQuery->bind_param("i", $post_id);
$postQuery->execute();
$postResult = $postQuery->get_result();
$post = $postResult->fetch_assoc();

echo "<h1>" . htmlspecialchars($post['title']) . "</h1>";
echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
echo "<small>Posted by " . htmlspecialchars($post['username']) . " on " . $post['creation_date'] . "</small>";

include 'comments_section.php';
?>