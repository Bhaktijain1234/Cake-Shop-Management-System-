<?php
$mysqli = new mysqli("localhost", "root", "", "bakery");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
