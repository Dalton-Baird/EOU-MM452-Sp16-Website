<?php
    session_start();
    
    //Unset login related session variables, and log out
    $_SESSION['loggedIn'] = false;
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_level']);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/header.php'; ?>
        <link href="/stylesheets/forms.css" rel="stylesheet" type="text/css">
        <title>EOU Logout</title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="form-container">
                        <h1 class="form-header">Logged out!</h1>
                        
                        <p class="label">You can now <a href="/">return to the home page</a>.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/footer-scripts.php'; ?>
    </body>
</html>