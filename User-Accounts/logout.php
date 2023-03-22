<?php
$page_title='Logout';

include("../includes/header.php");
echo "<div class = 'standard-box'><div>";
if(!isset($_SESSION['user_id']))
{
    echo'<h1 class = "standard-box-title">You are not logged in</h1>';
}
else
{
    $_SESSION=array();
    session_destroy();

    echo'<h1 class = "standard-box-title">You are now logged out</h1>';

}
echo "</div></div>";
include("../includes/footer.html");
?>