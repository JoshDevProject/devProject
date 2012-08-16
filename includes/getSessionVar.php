<?php

//make sure the session is started
session_start();

//make sure a session variable was picked
if (isset($_POST['variable']))
{
    //verify that the session variable is defined
    if (isset($_SESSION[$_POST['variable']]))
        //then print it out
        echo $_SESSION[$_POST['variable']];
    //otherwise, send an error message
    else echo "Error: Session Variable: " . $_POST['variable'] . " is undefined.";
}
//otherwise, send an error message
else echo "Error: no session variable asked for.";

?>
