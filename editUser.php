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

<div id="editUserStatus"></div>

<div class="editUser_wrapper">
    <form id="edit_form" method="post">
        <label>Login_ID: </label><div id="login_id_display"></div>
        <label>Username: </label><input id="usernameEdit" type="text">
        <label>Password: </label><input id="passwordEdit" type="text">
        <label>First Name: </label><input id="firstnameEdit" type="text">
        <label>Last Name: </label><input id="lastnameEdit" type="text">
        <label>Email: </label><input id="emailEdit" type="text">
        <div id="edit_error"></div>
        <input type="button" class="editUserBTN" value="Edit User">
        <input type="button" class="cancelEditBTN" value="Cancel">
    </form>
    
</div>

<script>
    
    $(document).ready(function(){
        //initially hide the edit user form and status message
        $("#edit_form").hide();
        $("#editUserStatus").slideUp();

            
    });  
    
</script>