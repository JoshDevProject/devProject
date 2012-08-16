//display the database
function displayDatabase(vDisplayConfig)
{      
    myPost = $.post(
        'includes/libraries/databaseLibrary.php',
        { processor: 'displayTable', displayConfig: JSON.stringify(vDisplayConfig) },
        function(data){
            //place the data in the div
            $("#contactDisplay").html(data);
        }
    );
    
    myPost.done(function(){
        handleDatabaseInteraction();
    });
}

function handleDatabaseInteraction()
{
    //sorting by column
    $("#contactDisplay").delegate("table.userTable th", "click", function(){
        //ignore the Delete column
        if ($(this).html() != "Delete")
        {
            var vDisplayConfig = {
                "sortBy" : $(this).html()
            }
            displayDatabase(vDisplayConfig);
            exit;
        }
    });
    
    //handling number of contacts to display
    $("#numOfContacts").change(function(){
        var vDisplayConfig = {
            "contactsToShow" : $(this).val()
        }
        displayDatabase(vDisplayConfig);
        exit;
    });
    
    //delete user
    $("#contactDisplay").delegate("table.userTable td", "click", function(){
        if ($(this).html() == "X")
        {
            var username = $(this).closest("tr").find("#username").html();
            var login_id = $(this).closest("tr").find("#login_id").html
            
            var vUserInfo = {
              "username" : username,
              "login_id" : login_id
            };
            removeUser(vUserInfo);
            displayDatabase();
            exit;
        }
    });
}

function addUser(vUserInfo)
{
    myPost = $.post(
        'includes/libraries/databaseLibrary.php',
        { processor: 'addUser', userInfo: JSON.stringify(vUserInfo) } ,
        function(){
        }
    );
}

function removeUser(vUserInfo)
{
    myPost = $.post(
        'includes/libraries/databaseLibrary.php',
        { processor: 'removeUser', userInfo: JSON.stringify(vUserInfo) },
        function(){
        }
    );
}

//validates the user
function validateUser(vUserInfo)
{
    var access = 0;
    
    $.post(
        'includes/libraries/databaseLibrary.php',
        { processor: 'verifyUser', userInfo: JSON.stringify(vUserInfo) },
        function(accessLevel){
            access = accessLevel
        }
    ).done(function(){
            $("#permission").html("Access Level: " + access);
    });
}

//sets a session variable to a value
function setSessionVar(vSessionVar)
{
    $.post(
        'includes/libraries/sessionLibrary.php',
        { processor: 'setSessionVar', variable: vSessionVar },
        function(){
        }
    ).done(function(){
    
    });
}

//returns a session variable
function getSessionVar(vRequestedVariable)
{
    var sessionVar;
    $.post(
        'includes/libraries/sessionLibrary.php',
        { processor: 'getSessionVar', variable: vRequestedVariable },
        function(data) {
            sessionVar = data;
        }
    ).done(function(){
        return sessionVar;
    });
} 