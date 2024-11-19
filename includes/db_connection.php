<?php
function createDatabaseConnection($config)
{
    $mysqli = new mysqli(
        $config['db_servername'],
        $config['db_username'],
        $config['db_password'],
        $config['db_name'],
        $config['db_port']
    );

    if ($mysqli->connect_error) {
        error_log("Connection failed: " . $mysqli->connect_error);
        exit();
    }

    return $mysqli;
}
?>