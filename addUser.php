<style type="text/css">
    #add_form {
        width: 500px;
        padding: 5px;
    }
    
    label {
        text-shadow: 2px 2px 2px #ccc;
        width: 50px;
    }
    
    input {
    }
    
    .adduserbtn {
        text-shadow: 2px 2px 2px #ccc;
    }
</style>

<div id="addUserStatus"></div>

<div class="addUser_wrapper">
    <form id="add_form" method="post">
        <label>Username: </label><input id="usernameAdd" type="text">
        <label>Password: </label><input id="passwordAdd" type="text">
        <label>First Name: </label><input id="firstnameAdd" type="text">
        <label>Last Name: </label><input id="lastnameAdd" type="text">
        <label>Email: </label><input id="emailAdd" type="text">
        <div id="login_error"></div>
    </form>
    <input type="button" class="addUserBTN" value="Add User">
</div>

<script>    
    $(document).ready(function(){
        //initially hide the add user form and add user status
        $("#add_form").hide();
        $("#addUserStatus").slideUp();
        
        //dropdown the form when clicking the add user button
        $(".addUserBTN").click(function(){
            //if we are adding a user
            if ($("#add_form").hasClass("addingUser"))
            {
                var firstname = $("#firstnameAdd").val();
                var lastname = $("#lastnameAdd").val();
                var email = $("#emailAdd").val();
                var username = $("#usernameAdd").val();
                var password = $("#passwordAdd").val();
                
                //add to database
                var vUserInfo = {
                    "firstName" : firstname,
                    "lastName" : lastname,
                    "email" : email,
                    "username" : username,
                    "password" : password
                };
                
                addUser(vUserInfo);
                
                //refresh the display
                displayDatabase();
                
                //cleanup the form
                $("#add_form").slideUp(function(){
                    //clear entries
                    //var username = $("#username").val();
                    $("#usernameAdd").val("");
                    $("#passwordAdd").val("");
                    $("#firstnameAdd").val("");
                    $("#lastnameAdd").val("");
                    $("#emailAdd").val("");
                    
                    //change user added message, slide down, pause, then slide up
                    $("#addUserStatus").html(username + " was added to the database").slideDown().delay(750).slideUp();
                    
                });
                
                //remove addingUser class
                $("#add_form").removeClass("addingUser");
            }
            else //we need to dropdown the form so a user can be added
            {
                //drop the form and add a class designating we are adding a user now
                $("#add_form").slideDown().addClass("addingUser");
                
            }
            
        });
    });
</script>