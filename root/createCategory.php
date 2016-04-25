<?php
    session_start();
    require_once('/fragments/connect.php');
    
    //Variables used when rendering the document:
    $errors = array(); //An array of errors to show the user
    $successes = array(); //An array of success messages to show the user
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include '/fragments/header.php'; ?>
        <link href="stylesheets/forum.css" rel="stylesheet" type="text/css">
        <link href="/stylesheets/forms.css" rel="stylesheet" type="text/css">
        <title>Create Category</title>
    </head>
    <body>
    <div class="container-fluid">
        <?php include 'fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-container">
                        
                        <h1 class="form-header">Create Forum Category</h1>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="row">
                                <div class="col-sm-4"><span class="label">EOU Email</span></div>
                                <div class="col-sm-8"><input type="email" name="email" autofocus value="<?php echo htmlspecialchars($savedEmail); ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Password</span></div>
                                <div class="col-sm-8"><input type="password" name="password"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4"><input class="main-button color-white background-blue reset-font-size" type="submit" value="Log in"></div>
                            </div>
                        </form>
                        
                        <div class="form-errors">
                            <?php
                                if (!empty($errors))
                                {
                                    echo 'Errors logging in:';
                                    echo '<ul>';
                                    
                                    foreach ($errors as $key => $value)
                                        echo '<li>' . $value . '</li>';
                                    
                                    echo '</ul>';
                                }
                            ?>
                        </div>
                        
                        <div class="form-successes">
                            <?php
                                if (!empty($successes))
                                {
                                    echo 'Good News:';
                                    echo '<ul>';
                                    
                                    foreach ($successes as $key => $value)
                                        echo '<li>' . $value . '</li>';
                                    
                                    echo '</ul>';
                                }
                            ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
        
        <?php include 'fragments/footer-scripts.php'; ?>
    </body>
</html>