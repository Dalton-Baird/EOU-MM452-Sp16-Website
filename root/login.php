<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . '/fragments/connect.php');
    
    //Variables used when rendering the document:    
    $errors = array(); //An array of errors to show the user
    $savedEmail = '';
    
    if (isset($_SESSION['loggedIn']) and $_SESSION['loggedIn'] == true)
    {
        //The user is already logged in, redirect to home page
        header("Location: /");
        exit;
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data, log in the user
    {
        if (!isset($_POST['email']) or $_POST['email'] == '')
            $errors[] = 'You must enter an email address.';
        
        if (!isset($_POST['password']) or $_POST['password'] == '')
            $errors[] = 'You must enter a password.';
        
        $savedEmail = $mysql -> real_escape_string($_POST['email']);
        
        if (empty($errors)) //No errors, log in!
        {            
            $loginQuery = $mysql -> query(
                "SELECT *
                 FROM Users
                 WHERE email = '" . $mysql -> real_escape_string($_POST['email']) . "'
                 AND password_hash = '" . hash('sha512', $_POST['password']) . "'"
            );
            
            if (!$loginQuery) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while logging in.  Please try again.';
                $errors[] = $mysql -> error;
            }
            else if ($loginQuery -> num_rows < 1) //No user with that email and password
            {
                $errors[] = 'No user exists with that email and password combination.';
            }
            else //No errors so far, and a user got returned from the database, log in!
            {
                $_SESSION['loggedIn'] = true;
                
                //Save some info about the user to the session
                while ($userRow = $loginQuery -> fetch_assoc())
                {
                    $_SESSION['user_id'] = $userRow['id'];
                    $_SESSION['user_name'] = $userRow['name'];
                    $_SESSION['user_email'] = $userRow['email'];
                    $_SESSION['user_level'] = $userRow['user_level'];
                }
                
                //Redirect the user to the forum page
                header('Location: /');
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/header.php'; ?>
        <link href="/stylesheets/forum.css" rel="stylesheet" type="text/css">
        <link href="/stylesheets/forms.css" rel="stylesheet" type="text/css">
        <title>EOU Login</title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-container">
                        
                        <h1 class="form-header">EOU Login</h1>
                        
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
                        
                    </div>
                </div>
            </div>
            
        </div>
        
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/footer-scripts.php'; ?>
    </body>
</html>