<?php
    if (session_status() == PHP_SESSION_NONE) //Start a session if it hasn't been started yet
        session_start();
    
    $errorMessage = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : "Default Error Message";
    $errorTitle = isset($_SESSION['errorTitle']) ? $_SESSION['errorTitle'] : "Default Error Title";
    
    //Unset the session variables to prevent errors
    unset($_SESSION['errorMessage']);
    unset($_SESSION['errorTitle']);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/header.php'; ?>
        <link href="/stylesheets/forms.css" rel="stylesheet" type="text/css">
        <title><?php echo htmlspecialchars($errorTitle); ?></title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-container">
                        <h1 class="form-header"><?php echo htmlspecialchars($errorTitle); ?></h1>
                        
                        <p class="label"><?php echo $errorMessage; /* Don't escape with htmlspecialchars so that custom HTML can be generated */ ?></p>
                    </div>
                </div>
            </div>
            
        </div>
        
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/footer-scripts.php'; ?>
    </body>
</html>