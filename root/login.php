<?php
    session_start();
    require_once('/fragments/connect.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fragments/header.php'; ?>
        <link href="stylesheets/forum.css" rel="stylesheet" type="text/css">
        <link href="stylesheets/login.css" rel="stylesheet" type="text/css">
        <title>EOU Forum Login</title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include 'fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="login-container">
                        
                        <h1 class="login-header">EOU Forum Login</h1>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="row">
                                    <div class="col-sm-4"><span class="label">EOU Email</span></div>
                                    <div class="col-sm-8"><input type="text" name="email" autofocus></div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-4"><span class="label">Password</span></div>
                                    <div class="col-sm-8"><input type="password" name="password"></div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-4 col-sm-offset-4"><input class="main-button color-white background-blue reset-font-size" type="submit" value="Log in"></div>
                                </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </body>
</html>