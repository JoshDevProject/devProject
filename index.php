<?php session_start(); ?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Index</title>
        <script src="includes/libraries/jquery.js"></script>
        <script src="includes/libraries/ajaxLibrary.js"></script>
    </head>
    
    <body>
        <div id="loginWrapper" style="border: 2px solid;">
            <?php include "login.php" ?>
        </div>
        
        <div id="menuWrapper">
            <?php include "menu.php" ?>
        </div>
        
        <div id="addUserWrapper" style="border: 2px solid;">
            <?php include "addUser.php" ?>
        </div>
        
        <div id="editUserWrapper" style="border: 2px solid;">
            <?php include "editUser.php" ?>
        </div>
        
        <div id="contactDisplayWrapper" style="border: 2px solid;">
            <h1>&nbsp&nbsp&nbsp&nbspContacts:</h1>
            <?php include "contactDisplay.php" ?>
        </div>

    </body>
    
</html>

