<?php

session_start();
DB::initDBSession();

//add handling for ajax calls to this file
//make sure a function was picked for processing data
if (isset($_POST['processor']))
{
    switch($_POST['processor'])
    {
        case 'addUser':
            DB::addUser();
            break;
        case 'editUser':
            DB::editUser();
            break;
        case 'removeUser':
            DB::removeUser();
            break;
        case 'verifyUser':
            DB::verifyUser();
            break;
        case 'displayTable':
            DB::displayTable();
            break;
        default:
            echo "No matching function: " . $_POST['processor'];
    }
}
else echo "No function assigned for processing.";

//encapsulate database configuration into a static class
//only stores default config settings
//static objects are refreshed frequently so it can't be
//used to store in the same way as a session
class DBConfig
{
    //database authentication info
    public static $host = 'localhost';
    public static $username = 'josh';
    public static $password = 'password';
    public static $name = 'devproject';
    
    //tables within the database
    public static $tUser = 'users';
    public static $tPermissions = 'perms';
    
    //options for the dropdown to display number of contacts 
    public static $dropdownOptions = array(1, 5, 10, 25, 50, 100);
}

//put all DB calls in one class
class DB
{
    //simple query to the database
    static function queryDB($query)
    {
        $dbc = mysqli_connect(DBConfig::$host, DBConfig::$username, DBConfig::$password, DBConfig::$name) or die ("Error connection to MySQL server: " . mysqli_connect_error());
        $result = mysqli_query($dbc, $query);
        mysqli_close($dbc);
        return $result;
    }
    
    //adds a user to the database
    static function addUser()
    {
        if (isset($_POST['userInfo']))
        {
            //get the user info
            $userInfo = json_decode($_POST['userInfo']);
            if (isset($userInfo->firstName))
                $firstname = $userInfo->firstName;
            if (isset($userInfo->lastName))
                $lastname = $userInfo->lastName;
            if (isset($userInfo->email))
                $email = $userInfo->email;
            if (isset($userInfo->username))
                $username = $userInfo->username;
            if (isset($userInfo->password))
                $password = $userInfo->password;
            
            //insert the user to the database
            $query = "INSERT into " . DBConfig::$tUser . " (firstname,lastname,email,username,password) VALUES ('$firstname','$lastname','$email','$username','$password')";
            echo $query . "</br>";
            DB::queryDB($query);
            
            //retrieve the auto-incremented login_id
            $result = DB::queryDB("SELECT * FROM " . DBConfig::$tUser . " where username='$username'");
            $info = mysqli_fetch_assoc($result);
            $login_id = $info['login_id'];
            
            //define access for the new user and insert into the permissions table
            $access = '1';
            DB::queryDB("INSERT into " . DBConfig::$tPermissions . " (login_id,access) VALUES ('$login_id','$access')");
        }
    }
    
    //edits a user in the database
    static function editUser()
    {
        if (isset($_POST['userInfo']))
        {
            //get the user info
            $userInfo = json_decode($_POST['userInfo']);
            $login_id = $userInfo->login_id;
            $firstName = $userInfo->firstName;
            $lastName = $userInfo->lastName;
            $email = $userInfo->email;
            $username = $userInfo->username;
            $password = $userInfo->password;
            
            $result = DB::queryDB(
                "UPDATE " . DBConfig::$tUser . " " .
                "SET firstname = '$firstName', lastname = '$lastName', email = '$email', username = '$username', password = '$password'" . 
                "WHERE login_id = '$login_id'"
            );
        }
    }
    
    //removes a user from the database
    static function removeUser()
    {
        if (isset($_POST['userInfo']))
        {
            //get the user info
            $userInfo = json_decode($_POST['userInfo']);
            if (isset($userInfo->login_id))
            {
                $login_id = $userInfo->login_id;                
                
                //get the login_id of the user
                $result = DB::queryDB("SELECT * FROM " . DBConfig::$tUser . " where login_id='$login_id'");
                if ($result)
                {
                    $info = mysqli_fetch_assoc($result);
                    $login_id = $info['login_id'];

                    //delete the user from both database
                    DB::queryDB("DELETE FROM " . DBConfig::$tPermissions . " where login_id='$login_id'");
                    DB::queryDB("DELETE FROM " . DBConfig::$tUser . " where login_id='$login_id'");
                }
            }
        }
    }
    
    //verifies a user is in the database
    //returns: access level of user (0 if not in the database)
    static function verifyUser()
    {
        if (isset($_POST['userInfo']))
        {
            //get the user info
            $userInfo = json_decode($_POST['userInfo']);
            if (isset($userInfo->username)  && isset($userInfo->password))
            {
                $username = $userInfo->username;
                $password = $userInfo->password;
            }
            
            //check if the user exists
            $query = "SELECT * FROM " . DBConfig::$tUser . " where username='$username' and password='$password'";
            $result = DB::queryDB($query);
            if ($result)
            {
                //get the info for the user
                $info = mysqli_fetch_assoc($result);
                $login_id = $info['login_id'];
                
                //get the permissions for the user
                $query = "SELECT * FROM " . DBConfig::$tPermissions . " where login_id='$login_id'";
                
                $result = DB::queryDB($query);
                if ($result)
                {
                    $info = mysqli_fetch_assoc($result);
                    //$access = $info['access'];
                    $_SESSION['access'] = $info['access'];
                    //$_SESSION['access'] == $access;
                    echo $_SESSION['access'];
                }
                else 
                {
                    $_SESSION['access'] = 0;
                    echo $_SESSION['access'];
                }
            }
            //if they arent, return access level 0
            else 
            {
                $_SESSION['access'] = 0;
                echo $_SESSION['access'];
            }
        }
    }
    
    static function initDBSession()
    {
        //only fixes session variables if they have not be initialized
        if (!isset($_SESSION['sortBy']))
            $_SESSION['sortBy'] = "login_id";
        if (!isset($_SESSION['isReversed']))
            $_SESSION['isReversed'] = 0;
        if (!isset($_SESSION['contactsToShow']))
            $_SESSION['contactsToShow'] = 50;
    }
    
    static function updateDBSession($config)
    {
        //forces session variables to the default config
        $_SESSION['sortBy'] = $config->sortBy;
        $_SESSION['isReversed'] = $config->isReversed;
        $_SESSION['contactsToShow'] = $config->contactsToShow;
    }
    
    static function displayTable()
    {
        //check for changes to the display
        if (isset($_POST['displayConfig']))
        {
            $displayConfig = json_decode($_POST['displayConfig']);
            
            if (isset($displayConfig->sortBy))
            {
                //if already sorted by the category
                if ($_SESSION['sortBy'] == $displayConfig->sortBy)
                {
                    //toggle isReversed
                    if ($_SESSION['isReversed'] == 0)
                        $_SESSION['isReversed'] = 1;
                    else
                        $_SESSION['isReversed'] = 0;
                }
                else
                {
                    $_SESSION['sortBy'] = $displayConfig->sortBy;
                    
                    //reset isReversed
                    $_SESSION['isReversed'] = 0;
                }
            }
            if (isset($displayConfig->reversed))
                $_SESSION['isReversed'] = $displayConfig->isReversed;
            if (isset($displayConfig->contactsToShow))
                $_SESSION['contactsToShow'] = $displayConfig->contactsToShow;
        }
        
        //build the db query
        $_SESSION['query'] = "SELECT * FROM " . DBConfig::$tUser . " ORDER BY " . $_SESSION['sortBy'];
        
        if ($_SESSION['isReversed'])
            $_SESSION['query'] .= " DESC";
        
        $_SESSION['query'] .= " LIMIT ";
        $_SESSION['query'] .= $_SESSION['contactsToShow'];
        
        echo "Displaying table of contacts with the following parameters: </br>";
        echo "Sorting By: " . $_SESSION['sortBy'] . "</br>";
        echo "Reversed: " . $_SESSION['isReversed'] . "</br>";
        echo "Limited by: " . $_SESSION['contactsToShow'] . "</br>";
        echo "Query: " . $_SESSION['query'] . "</br>";
        
        DB::htmlTableDisplay();
    }
    
    
    //echos out the html table of users
    static function htmlTableDisplay()
    {
        $result = DB::queryDB($_SESSION['query']);
        
        //print out dropdown selector to determine number of contacts
        echo '<select id="numOfContacts">';
        for ($x = 0; $x < count(DBConfig::$dropdownOptions); $x++)
        {
            echo '<option value="' . DBConfig::$dropdownOptions[$x] . '"';
            if ($_SESSION['contactsToShow'] == DBConfig::$dropdownOptions[$x])
                echo ' selected="seleted"';
            echo '>' . DBConfig::$dropdownOptions[$x] . "</option>";
        }
        echo '</select>';

        //print out the start of the table
        echo '<table class="userTable" border="1" width="100%" style="border-collapse: collapse;">
              <tr>
                <th id="login_id">login_id</th>
                <th id="username">username</th>
                <th id="password">password</th>
                <th id="firstname">firstname</th>
                <th id="lastname">lastname</th>
                <th id="email">email</th>
                <th id="delete">Delete</th>
              </tr>';

        //print out each user
        for($i = 1; $user = mysqli_fetch_assoc($result); $i++)
        {
            echo '<tr name=row'. $i .'>
                    <td id="login_id">'  . $user['login_id'] . '</td>
                    <td id="username">'  . $user['username'] . '</td>
                    <td id="password">'  . $user['password'] . '</td>
                    <td id="firstname">' . $user['firstname']. '</td>
                    <td id="lastname">'  . $user['lastname'] . '</td>
                    <td id="email">'     . $user['email']    . '</td>
                    <td name="delete" Align=center>X</td>
                 </tr>';
        }
        
        //print out the end of the table
        echo '</table>';
    }
}

?>