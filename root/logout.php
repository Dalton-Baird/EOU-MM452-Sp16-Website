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
        <?php include '/fragments/header.php'; ?>
        <link href="stylesheets/logout.css" rel="stylesheet" type="text/css">
        <title>EOU Logout</title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="logout-container">
                        <h1 class="logout-header">Logged out!</h1>
                        <p>
                            You can now <a href="/">return to the home page</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include '/fragments/footer-scripts.php'; ?>
    </body>
</html>