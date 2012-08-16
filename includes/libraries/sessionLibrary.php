<?php

session_start();

//add handling for ajax calls to this file
//make sure a function was picked for processing data
if (isset($_POST['processor']))
{
    switch($_POST['processor'])
    {
        case 'getSessionVar':
            break;
        case 'setSessionVar':
            break;
        default:
            echo "No matching function: " . $_POST['processor'];
    }
}
else echo "No function assigned for processing.";

function getSessionVar()
{
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
}

function setSessionVar()
{       
    //assign the value
    $variable = $_POST['variable'];
    $value = $_POST['value'];
    $_SESSION[$variable] = $value;

    //echo the value that has been assigned
    echo $_SESSION[$variable];
}