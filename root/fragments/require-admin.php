<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/UserUtils.php';
    
    //Redirect the user to the error page if they aren't an admin
    if (!UserUtils::isAdmin())
    {
        header("Location: /errors/adminRequired.php");
        exit;
    }
?>