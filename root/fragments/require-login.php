<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/UserUtils.php';
    
    //Redirect the user to the error page if they aren't logged in
    if (!UserUtils::isLoggedIn())
    {
        header("Location: /errors/loginRequired.php");
        exit;
    }
?>