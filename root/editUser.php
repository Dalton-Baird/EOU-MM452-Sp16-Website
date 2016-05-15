<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fragments/connect.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fragments/require-login.php';
    
    /**
     * Deletes the user's existing profile images
     * Param: $userID: The user's ID
     */
    function deleteUserProfileImages(int $userID)
    {
        $targetFile = $_SERVER['DOCUMENT_ROOT'] . '/images/profile_pictures/profile-' . htmlspecialchars($userID);
        $fileActionsOK = true;
        
        if (file_exists($targetFile . '.jpg'))
        {
            echo 'File to delete: ' . htmlspecialchars($targetFile) . '.jpg';
            
            // if (!unlink($targetFile . '.jpg'))
            // {
            //     $fileActionsOK = false;
            // }
        }
        if (file_exists($targetFile . '.png'))
        {
            echo 'File to delete: ' . htmlspecialchars($targetFile) . '.png';
            
            // if (!unlink($targetFile . '.png'))
            // {
            //     $fileActionsOK = false;
            // }
        }
        if (file_exists($targetFile . '.gif'))
        {
            echo 'File to delete: ' . htmlspecialchars($targetFile) . '.gif';
            
            // if (!unlink($targetFile . '.gif'))
            // {
            //     $fileActionsOK = false;
            // }
        }
        
        return $fileActionsOK;
    }
    
    //Variables used when rendering the document:    
    $errors = array(); //An array of errors to show the user
    $successes = array(); //An array of success messages to show the user
    $inputID = -1;
    $inputName = '';
    $inputEmail = '';
    $inputCurrentPassword = '';
    $inputNewPassword = '';
    $inputNewPasswordConfirm = '';
    $inputMajor = '';
    $inputMinor = '';
    $inputPosition = '';
    $inputUserLevel = -1;
    $wantsToChangePassword = false;
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') //Edit user
    {
        $userID = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($userID != null) //Load user for editing
        {
            $loadUserQuery = $mysql -> query(
                "SELECT *
                FROM Users
                WHERE id = '" . $mysql -> real_escape_string($userID) . "'"
            );
            
            if (!$loadUserQuery) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while loading user information.  Please try again.';
                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
            }
            else if ($loadUserQuery -> num_rows < 1)
            {
                $errors[] = 'Failed to load data for user with id ' . htmlspecialchars($userID) . ', that user was not found!';
            }
            else //It was loaded successfully
            {
                $row = $loadUserQuery -> fetch_assoc();
                
                $inputID = (int) $row['id'];
                $inputName = $row['name'];
                $inputEmail = $row['email'];
                $inputMajor = $row['major'];
                $inputMinor = $row['minor'];
                $inputPosition = $row['position'];
                $inputUserLevel = $row['user_level'];
                
                //Check if editing user has permission to edit this user
                if ($inputID != $_SESSION['user_id'] or $_SESSION['user_level'] < 1)
                {
                    die('You do not have permission to edit that user! (TODO: Show a better error message)');
                }
                
                $successes[] = 'User with id ' . htmlspecialchars($userID) . ' loaded successfully.';
            }
        }
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data, update the user
    {
        //Load variables
        if (isset($_POST['id']) and is_numeric($_POST['id']))
            $inputID = (int) $_POST['id'];
        
        if (isset($_POST['name']))
            $inputName = $_POST['name'];
        
        if (isset($_POST['email']))
            $inputEmail = $_POST['email'];
        
        if (isset($_POST['currentPassword']))
        {
            $inputCurrentPassword = $_POST['currentPassword'];
        }
        
        if (isset($_POST['newPassword']))
        {
            $inputNewPassword = $_POST['newPassword'];
            if (!empty($inputNewPassword))
                $wantsToChangePassword = true;
        }
        
        if (isset($_POST['newPasswordConfirm']))
        {
            $inputNewPasswordConfirm = $_POST['newPasswordConfirm'];
            if (!empty($inputNewPasswordConfirm))
                $wantsToChangePassword = true;
        }
        
        if (isset($_POST['major']))
            $inputMajor = $_POST['major'];
        
        if (isset($_POST['minor']))
            $inputMinor = $_POST['minor'];
        
        if (isset($_POST['position']))
            $inputPosition = $_POST['position'];
        
        if (isset($_POST['user_level']))
            $inputUserLevel = $_POST['user_level'];
        
        //Null / Empty Checks            
        if (empty($inputNewPasswordConfirm) and !empty($inputNewPassword))
            $errors[] = 'You must confirm your new password.';
            
        if ($wantsToChangePassword and empty($inputCurrentPassword))
            $errors[] = 'You must enter your current password before you can enter a new one.';
        
        //Valid Input Checks
        if (empty($errors))
        {            
            $usernameRegex = '/^([ \\x{00c0}-\\x{01ff}a-zA-Z\\\'\-.])+$/u';
            
            if (!preg_match($usernameRegex, $inputName)) //Raw Regex: ^([ \u00c0-\u01ffa-zA-Z'\-.])+$
                $errors[] = 'Your name does not match the required format.<br>Format Regex: <pre style="display: inline;">' . htmlspecialchars($usernameRegex) . '</pre>';
            
            if (strlen($inputName) < 3 or strlen($inputName) > 45)
                $errors[] = 'Your name must be 3 - 45 characters in length.';
            
            if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL) === false)
                $errors[] = 'Your email address is invalid.';
            
            if (strlen($inputEmail) < 5 or strlen($inputEmail) > 45)
                $errors[] = 'Your email address must be 5 - 45 characters in length.';
            
            if ($wantsToChangePassword)
            {
                if ($inputNewPassword != $inputNewPasswordConfirm)
                    $errors[] = 'Your passwords do not match.';
            }
            
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
                 WHERE name = '" . $mysql -> real_escape_string($inputName) . "'
                 AND id <> '" . $mysql -> real_escape_string($inputID) . "'"
            );
            
            if (!$userQuery) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while checking for existing usernames.  Please try again.';
                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
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
                 WHERE email = '" . $mysql -> real_escape_string($inputEmail) . "'
                 AND id <> '" . $mysql -> real_escape_string($inputID) . "'"
            );
            
            if (!$emailQuery) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while checking for existing email registrations.  Please try again.';
                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
            }
            else if ($emailQuery -> num_rows > 0) //That email is already taken
            {
                $errors[] = 'That email has already been registered.';
            }
            else
            {
                $successes[] = 'That email is able to be registered!';
            }
            
            //Check if current password is correct
            if ($wantsToChangePassword)
            {
                $checkCurrentPasswordQuery = $mysql -> query(
                    "SELECT *
                    FROM Users
                    WHERE id=" . $mysql -> real_escape_string($inputID) . "
                    AND password_hash='" . hash("sha512", $inputCurrentPassword) . "'"
                );
                
                if (!$checkCurrentPasswordQuery) //If the query failed
                {
                    $errors[] = 'Something went wrong while checking if your current password was correct.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else if ($checkCurrentPasswordQuery -> num_rows < 1)
                {
                    $errors[] = 'Your entered current password is incorrect.';
                }
                else
                {
                    $successes[] = 'Current password is correct.';
                }
            }
        }
        
        if (empty($errors)) //No errors, update user!
        {            
            $successes[] = 'TODO: Updating code is not yet implemented!';
        }
        
        //The user wants to change their profile picture
        if (isset($_FILES['userImage']['size']) and $_FILES['userImage']['size'] > 0)
        {            
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/images/profile_pictures/';
            $targetFile = $targetDir . basename($_FILES['userImage']['name']);
            $imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
            $targetFile = $targetDir . 'profile-' . htmlspecialchars($inputID) . '.' . pathinfo($targetFile, PATHINFO_EXTENSION);
            $uploadOK = true;
            
            //Check if image is really an image
            $check = getimagesize($_FILES['userImage']['tmp_name']);
            
            if ($check == true)
            {
                $uploadOK = true;
            }
            else
            {
                $errors[] = 'Uploaded profile image was not an image!';
                $uploadOK = false;
            }
            
            if ($check[0] > 128 or $check[1] > 128)
            {
                $uploadOK = false;
                $errors[] = "Uploaded profile image's dimensions were too large. Dimensions: " . htmlspecialchars($check[0]) . 'x' . htmlspecialchars($check[1]) . '. Maximum allowed: 128x128';
            }
            
            //Check file size
            if ($_FILES['userImage']['size'] > (1024 ** 2))
            {
                $errors[] = 'File was too large';
                $uploadOK = false;
            }
            
            //Check file extension
            if ($imageFileType != 'jpg' and $imageFileType != 'png' and $imageFileType != 'gif')
            {
                $errors[] = "File wasn't a JPG, PNG, or GIF.";
                $uploadOK = false;
            }
            
            //Check if upload is ok
            if ($uploadOK == false)
            {
                $errors[] = 'Uploaded file had errors, profile image not set.';
            }
            else
            {
                if (deleteUserProfileImages($inputID) and move_uploaded_file($_FILES['userImage']['tmp_name'], $targetFile))
                {
                    $successes[] = 'Profile picture was successfully updated!';
                }
                else
                {
                    $errors[] = 'There was an error updating your profile picture.';
                }
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
        <title>Edit User Account</title>
        <style>
            .form-container {
                margin-bottom: 10vh; <?php /* TODO: Find out how to do this automatically for all forms */ ?>
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-container">
                        
                        <h1 class="form-header">Edit User Account</h1>
                        <div class="form-header">The password and profile picture will only be changed if you enter new ones.</div>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($inputID); ?>">
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">First And Last Name</span></div>
                                <div class="col-sm-8"><input type="text" name="name" value="<?php echo htmlspecialchars($inputName); ?>" autofocus></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">EOU Email</span></div>
                                <div class="col-sm-8"><input type="email" name="email" value="<?php echo htmlspecialchars($inputEmail); ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Current Password</span></div>
                                <div class="col-sm-8"><input type="password" name="currentPassword"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">New Password</span></div>
                                <div class="col-sm-8"><input type="password" name="newPassword"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Confirm New Password</span></div>
                                <div class="col-sm-8"><input type="password" name="newPasswordConfirm"></div>
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
                                <div class="col-sm-4"><span class="label">User Level</span></div>
                                <div class="col-sm-8">
                                    <select name="user_level" data-toggle="tooltip" title="Choose the user level">
                                        <?php
                                          
                                            $userLevelQuery = $mysql -> query(
                                                "SELECT level_number, name, description
                                                FROM UserLevels"
                                            );
                                            
                                            if (!$userLevelQuery)
                                            {
                                                $errors[] = 'Couldn\'t retrieve user levels from the database!  Try reloading the page.';
                                                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                                            }
                                            else
                                            {
                                                while ($userLevelRow = $userLevelQuery -> fetch_assoc())
                                                {
                                                    echo '<option value="' . htmlspecialchars($userLevelRow['level_number']) . '" ' . ($userLevelRow['level_number'] == $inputUserLevel ? 'selected ' : ' ') . 'title="' . htmlspecialchars($userLevelRow['description']) . '">' . htmlspecialchars($userLevelRow['name']) . '</option>';
                                                }
                                            }
                                        
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4">
                                    <span class="label">Profile Image</span>
                                    <div class="label-details">Must be 128x128 JPG, PNG, or GIF, Max 2 MB</div>
                                </div>
                                <div class="col-sm-8">
                                    <input type="file" name="userImage" accept="image/*" size="1" onchange="onSelectFile()">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4"><input class="main-button color-white background-blue reset-font-size" type="submit" value="Update"></div>
                            </div>
                        </form>
                        
                        <div class="form-errors">
                            <?php
                                if (!empty($errors))
                                {
                                    echo 'Errors while updating user account:';
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
        
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/footer-scripts.php'; ?>
    </body>
</html>