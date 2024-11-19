<?php
include('public/session_start.php');
$_SESSION = array();
session_destroy();
header('Location: public/homepage.php');
exit;
?>