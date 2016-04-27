<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/UserUtils.php';
    
    //Redirect the user to the error page if they aren't a moderator
    if (!UserUtils::isModerator())
    {
        header("Location: /errors/moderatorRequired.php");
        exit;
    }
?>