<?php
    session_start();
    require_once('/fragments/connect.php');
    
    //Variables used when rendering the document:    
    $errors = array(); //An array of errors to show the user
    $successes = array(); //An array of success messages to show the user
    $inputName = '';
    $inputEmail = '';
    $inputPassword = '';
    $inputPasswordConfirm = '';
    $inputMajor = '';
    $inputMinor = '';
    $inputPosition = '';
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data, log in the user
    {
        //Load variables
        if (isset($_POST['name']))
            $inputName = $_POST['name'];
        
        if (isset($_POST['email']))
            $inputEmail = $_POST['email'];
        
        if (isset($_POST['password']))
            $inputPassword = $_POST['password'];
        
        if (isset($_POST['password-confirm']))
            $inputPasswordConfirm = $_POST['password-confirm'];
        
        if (isset($_POST['major']))
            $inputMajor = $_POST['major'];
        
        if (isset($_POST['minor']))
            $inputMinor = $_POST['minor'];
        
        if (isset($_POST['position']))
            $inputPosition = $_POST['position'];
        
        //Null / Empty Checks
        if (empty($inputName))
            $errors[] = 'You must enter your name.';
        
        if (empty($inputEmail))
            $errors[] = 'You must enter an email address.';
        
        if (empty($inputPassword))
            $errors[] = 'You must enter a password.';
            
        if (empty($inputPasswordConfirm))
            $errors[] = 'You must confirm your password.';
        
        //Valid Input Checks
        if (empty($errors))
        {
            //All variables except the non-required ones are guaranteed to be set at this point
            
            //if (!ctype_graph($inputName))
            
            $usernameRegex = '/^([ \\x{00c0}-\\x{01ff}a-zA-Z\\\'\-.])+$/u';
            
            if (!preg_match($usernameRegex, $inputName)) //Raw Regex: ^([ \u00c0-\u01ffa-zA-Z'\-.])+$
                $errors[] = 'Your name does not match the required format.<br>Format Regex: <pre style="display: inline;">' . htmlspecialchars($usernameRegex) . '</pre>';
            
            if (strlen($inputName) < 3 or strlen($inputName) > 45)
                $errors[] = 'Your name must be 3 - 45 characters in length.';
            
            if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL) === false)
                $errors[] = 'Your email address is invalid.';
            
            if (strlen($inputEmail) < 5 or strlen($inputEmail) > 45)
                $errors[] = 'Your email address must be 5 - 45 characters in length.';
            
            if ($inputPassword != $inputPasswordConfirm)
                $errors[] = 'Your passwords do not match.';
            
            if (strlen($inputMajor) > 45)
                $errors[] = 'Your major must be 0 - 45 characters in length.';
            
            if (strlen($inputMinor) > 45)
                $errors[] = 'Your minor must be 0 - 45 characters in length.';
            
            if (strlen($inputPosition) > 45)
                $errors[] = 'Your position must be 0 - 45 characters in length.';
        }
        
        //Check if name and email are available
        if (empty($errors))
        {
            //Check if name is taken
            $userQuery = $mysql -> query(
                "SELECT *
                 FROM Users
                 WHERE name = '" . $mysql -> real_escape_string($inputName) . "'"
            );
            
            if (!$userQuery) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while checking for existing usernames.  Please try again.';
                $errors[] = $mysql -> error;
            }
            else if ($userQuery -> num_rows > 0) //That name is already taken
            {
                $errors[] = 'That name has already been taken.';
            }
            else
            {
                $successes[] = 'That name is available!';
            }
            
            //Check if email is taken
            $emailQuery = $mysql -> query(
                "SELECT *
                 FROM Users
                 WHERE email = '" . $mysql -> real_escape_string($inputEmail) . "'"
            );
            
            if (!$emailQuery) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while checking for existing email registrations.  Please try again.';
                $errors[] = $mysql -> error;
            }
            else if ($emailQuery -> num_rows > 0) //That email is already taken
            {
                $errors[] = 'That email has already been registered.';
            }
            else
            {
                $successes[] = 'That email is able to be registered!';
            }
        }
        
        if (empty($errors)) //No errors, register!
        {            
            $mysql -> query("START TRANSACTION");
            
            $insertStatement = $mysql -> query("
                INSERT INTO
                    Users (creation_date, update_date, email, name, password_hash, major, minor, position, user_level)
                VALUES (
                    NOW(),
                    NOW(),
                    '" . $mysql -> real_escape_string($inputEmail) . "',
                    '" . $mysql -> real_escape_string($inputName) . "',
                    '" . hash('sha512', $inputPassword) . "',
                    '" . $mysql -> real_escape_string($inputMajor) . "',
                    '" . $mysql -> real_escape_string($inputMinor) . "',
                    '" . $mysql -> real_escape_string($inputPosition) . "',
                    (SELECT level_number
                     FROM UserLevels
                     WHERE name='User'))"
            );
            
            if (!$insertStatement)
                $errors[] = '[DEBUG]: Insert Statement: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
            
            $updateStatement = $mysql -> query("
                UPDATE Users
                SET creation_user=LAST_INSERT_ID(), update_user=LAST_INSERT_ID()
                WHERE id=LAST_INSERT_ID()"
            );
            
            if (!$updateStatement)
                $errors[] = '[DEBUG]: Update Statement: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
            
            if ($insertStatement and $updateStatement)
            {
                $mysql -> query("COMMIT");
                $successes[] = 'Registration successful!  You may now <a href="/login.php">Log In</a>.';
            }
            else
            {
                //$errors[] = '[DEBUG]: Insert Statement: ' . ($insertStatement ? 'success' : 'failure'). ', Update Statement: ' . ($updateStatement ? 'success' : 'failure') . '.<br>MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                $rollbackStatement = $mysql -> query("ROLLBACK");
                $errors[] = '[DEBUG]: Insert Statement: ' . ($insertStatement ? 'success' : 'failure') . ', Update Statement: ' . ($updateStatement ? 'success' : 'failure') . '. Database rollback: ' . ($updateStatement ? 'success' : 'failure') . '.';
                $errors[] = 'Something went wrong while registering.  Please try again.';
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fragments/header.php'; ?>
        <link href="stylesheets/forum.css" rel="stylesheet" type="text/css">
        <link href="stylesheets/register.css" rel="stylesheet" type="text/css">
        <title>EOU Forum Registration</title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include 'fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="register-container">
                        
                        <h1 class="register-header">EOU Forum Registration</h1>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="row">
                                <div class="col-sm-4"><span class="label">First And Last Name</span></div>
                                <div class="col-sm-8"><input type="text" name="name" value="<?php echo htmlspecialchars($inputName); ?>" autofocus></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">EOU Email</span></div>
                                <div class="col-sm-8"><input type="email" name="email" value="<?php echo htmlspecialchars($inputEmail); ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Password</span></div>
                                <div class="col-sm-8"><input type="password" name="password"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Confirm Password</span></div>
                                <div class="col-sm-8"><input type="password" name="password-confirm"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Major</span><span class="not-required"> (Not Required)</span></div>
                                <div class="col-sm-8"><input type="text" name="major" value="<?php echo htmlspecialchars($inputMajor); ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Minor</span><span class="not-required"> (Not Required)</span></div>
                                <div class="col-sm-8"><input type="text" name="minor" value="<?php echo htmlspecialchars($inputMinor); ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Staff Position</span><span class="not-required"> (Not Required)</span></div>
                                <div class="col-sm-8"><input type="text" name="position" value="<?php echo htmlspecialchars($inputPosition); ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4"><input class="main-button color-white background-blue reset-font-size" type="submit" value="Register"></div>
                            </div>
                        </form>
                        
                        <div class="register-errors">
                            <?php
                                if (!empty($errors))
                                {
                                    echo 'Errors while registering:';
                                    echo '<ul>';
                                    
                                    foreach ($errors as $key => $value)
                                        echo '<li>' . $value . '</li>';
                                    
                                    echo '</ul>';
                                }
                            ?>
                        </div>
                        
                        <div class="register-successes">
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