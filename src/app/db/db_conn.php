<?php
$db_servername  = "localhost";
$db_username    = "username";
$db_password    = "password";
$db_name        = "public_diary";

try {
    $conn = new PDO(
        "mysql:host=$db_servername;dbname=$db_name",
        $db_username,
        $db_password
    );
} catch (PDOException $e) {
    // echo $e->getMessage();
    exit();
}
