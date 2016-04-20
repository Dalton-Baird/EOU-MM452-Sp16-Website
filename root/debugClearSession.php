<?php
    session_start();
    session_unset();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include '/fragments/header.php'; ?>
        <title>PHP Session Debug</title>
        
        <style>
            .container {
                margin-top: 20vh;
            }
            
            .content {
                background-color: #555555;
                color: #EEEEEE;
                padding: 5%;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <?php include '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="content">
                        <h1>PHP Session Variables Cleared!</h1>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include '/fragments/footer-scripts.php'; ?>
    </body>
</html>