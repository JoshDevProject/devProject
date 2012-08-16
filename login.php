<style type="text/css">
    #login_form {
        width: 500px;
        padding: 5px;
    }
    
    label {
        text-shadow: 2px 2px 2px #ccc;
        width: 50px;
    }
    
    input {
        display: block;
    }
    
    .adduserbtn {
        text-shadow: 2px 2px 2px #ccc;
        display: block;   
    }
</style>

<div id="statusWrapper">
    <div id="loginStatus"></div><div id="permission"></div>
    <a id="loginInit" href="">Click here to log in</a>
</div>

<div class="login_wrapper">
    <form id="login_form" method="post">
        <label>Username: </label>
        <input id="username" type="text" name="username">
        <label>Password: </label>
        <input id="password" type="text" name="password">
        <input type="button" class="loginUserBTN" value="Login">
        <div id="login_error"></div>
    </form>
</div>

<div class="login_data">
    <div id="permission"></div>
</div>

<script>

    $(document).ready(function(){

        //hide the form initially
        $(".login_wrapper").hide();
        
        $("#loginStatus").html("You are not currently logged in.");
        $("#loginInit").click(function(event){
            //disable the link
            event.preventDefault(); 
            
            //hide the initial login stuff
            $("#statusWrapper").slideUp(function(){
                //slide down the login form
                $(".login_wrapper").slideDown();
            });
        });
        
        $(".loginUserBTN").click(function(){
            //capture the username
            var username = $("#username").val();
            var password = $("#password").val();
            
            //validate user and handle wrong info
            var vUserInfo = {
                "username" : username,
                "password" : password
            };
            
            validateUser(vUserInfo);
            
            //slide up the login form
            $(".login_wrapper").slideUp(function(){
                
                //change the info in the status wrapper
                $("#loginStatus").html("You are logged in as: "  + username);
                $("#loginInit").html("Click here to log out.");
                
                //erase the input fields
                $("#username").val("");
                $("#password").val("");
                
                //slide down the status wrapper
                $("#statusWrapper").slideDown();
            });
        });
        
    });



</script>

