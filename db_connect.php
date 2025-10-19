<?php

$sname = "localhost";
$username = "root";
$password = "";
$db_name = "resale-store";

$conn = mysqli_connect($sname, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed!" . mysqli_connect_error());
}

?>