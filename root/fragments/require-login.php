<?php
    if (session_status() == PHP_SESSION_NONE) //Start a session if it hasn't been started yet
        session_start();
    
    //Redirect the user to the error page if they aren't logged in
    if (!isset($_SESSION['loggedIn']) or !$_SESSION['loggedIn'])
    {
        header("Location: /errors/loginRequired.php");
        exit;
    }
?>