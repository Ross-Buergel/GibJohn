<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    require('../includes/connect_db.php');
    require('login_tools.php');
    list ($check,$data) = validate($dbc,$_POST['email'],$_POST['pass']);
    if ($check)
    {
        session_start();

        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['first_name'] = $data['first_name'];
        $_SESSION['last_name'] = $data['last_name'];
        $_SESSION['account_type'] = $data['account_type'];
        load('../index.php');
    }
    else
    {
        $errors = $data;
    }
    mysqli_close($dbc);
}
include('login.php')
?>