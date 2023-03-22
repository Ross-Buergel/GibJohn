<!DOCTYPE html>
<?php
$page_title = "Login";
include ("../includes/header.php");
if (isset($errors) && !empty($errors))
{
    echo "<div class = 'standard-box'><div><p class = 'standard-box-text' id = 'err_msg'>Oops! There was a problem <br>";
    foreach ($errors as $msg)
    {
        echo " - $msg<br>";
    }
    echo "Please try again or <a href='register.php' class = 'standard-box-text'>Register</a></p></div></div>";
}
?>
<div class = "standard-box">
    <div>
        <form class = "form-layout" action="../User-Accounts/login_action.php" method = "POST">
            <br>
            <div class = "box"></div>
            <h1 class = "standard-box-title">Login</h1>
            <div class = "box"></div>
            <p class = "standard-box-text">
                Email Address: <br> <input type = "text" name = "email">
            </p>
            <p class = "standard-box-text">
                Password: <br> <input type = "password" name = "pass">
            </p>
            <p>
                <input type = "submit" value = "Login" class = "submit-button">
            </p>
            <div class = "box"></div>
            <p class = "standard-box-text">Don't have an account?<br>Create one<a href = "../User-Accounts/register.php" 
            class = "standard-box-text">Here</a>
            <div class = "box"></div>
        </form>
    </div>
</div>
<?php include ("../includes/footer.html");?>