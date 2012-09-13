<?php

session_start();

if(isset($_SESSION['access']))
{
    if ($_SESSION['access'] > 0)
    {
        echo "You are logged in!";
    }
    else 
    {
        echo "Must be logged in to view page [Session initialized]";
    }
}
else
    echo "Must be logged in to view page [Session not initialized]";
?>
