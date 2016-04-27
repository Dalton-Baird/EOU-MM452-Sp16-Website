<?php
    if (session_status() == PHP_SESSION_NONE) //Start a session if it hasn't been started yet
        session_start();
    
    //Redirect the user to the error page if they aren't an admin
    if (!isset($_SESSION['user_level']) or $_SESSION['user_level'] < 2)
    {
        header("Location: /errors/adminRequired.php");
        exit;
    }
?>