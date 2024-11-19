<?php
include('public/session_start.php');
$config = include("includes/config.php");
$mysqli = createDatabaseConnection($config['users_db']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['userId'])) {
    $userId = $_POST['userId'];
}

?>