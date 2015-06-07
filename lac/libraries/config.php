<?php
$database="rajka381_lac";
$servername="localhost";
$passsword="ch@ng3m3";
$username="rajka381_lac";

$link = mysql_connect($servername, $username, $passsword);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($database);
?>