<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost:8080l');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'profdevtest');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect('localhost', 'root', '', 'profdevtest');
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>