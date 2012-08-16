<?php

//make sure the session is started
session_start();

//assign the value
$variable = $_POST['variable'];
$value = $_POST['value'];
$_SESSION[$variable] = $value;

//echo the value that has been assigned
echo $_SESSION[$variable];

?>
