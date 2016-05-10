<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . '/fragments/connect.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/fragments/require-login.php');
    
    //Variables used when rendering the document:
    $errors = array(); //An array of errors to show the user
    $successes = array(); //An array of success messages to show the user
    $inputID = -1;
    $inputName = '';
    $inputCategory = '';
    $inputLocked = false;
    $inputStickied = false;
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/post/postEditorValidationCode.php';
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') //Edit topic
    {
        $topicID = isset($_GET['id']) ? $_GET['id'] : null;
        $categoryID = isset($_GET['category']) ? $_GET['category'] : null;
        
        if ($topicID != null) //Load topic for editing
        {
            $loadTopicQuery = $mysql -> query(
                "SELECT *
                FROM Topics
                WHERE id = '" . $mysql -> real_escape_string($topicID) . "'"
            );
            
            if (!$loadTopicQuery) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while loading the topic.  Please try again.';
                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
            }
            else if ($loadTopicQuery -> num_rows < 1)
            {
                $errors[] = 'Failed to load data for topic with id ' . htmlspecialchars($topicID) . ', that topic was not found!';
            }
            else //It was loaded successfully
            {
                $row = $loadTopicQuery -> fetch_assoc();
                
                $inputID = (int) $row['id'];
                $inputName = $row['name'];
                $inputCategory = $row['category']; //If this is not numeric, then it is "nothing"
                $inputLocked = $row['locked'] == true; //Make sure this converts to boolean
                $inputStickied = $row['stickied'] == true; //Make sure this converts to boolean
                
                $successes[] = 'Topic with id ' . htmlspecialchars($topicID) . ' loaded successfully.';
            }
        }
        else if ($categoryID != null) //Set category ID from URL
        {
            $inputCategory = $categoryID;
        }
    }    
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data
    {
        //Load variables
        if (isset($_POST['id']) and is_numeric($_POST['id']))
            $inputID = (int) $_POST['id'];
        
        if (isset($_POST['name']))
            $inputName = $_POST['name'];
        
        if (isset($_POST['category']))
            $inputCategory = $_POST['category'];
        
        $inputLocked = isset($_POST['locked']) and !empty($_POST['locked']);
        
        $inputStickied = isset($_POST['stickied']) and !empty($_POST['stickied']);
        
        //Null / Empty Checks
        if (empty($inputName))
            $errors[] = 'You must enter a topic name.';
        
        //Valid Input Checks
        if (empty($errors))
        {
            //All variables except the non-required ones are guaranteed to be set at this point
            
            if (strlen($inputName) < 3 or strlen($inputName) > 45)
                $errors[] = 'Topic name must be 3 - 45 characters in length.';
        }
        
        //Check for database conflicts
        if (empty($errors))
        {
            $categoryID = -1; //-1 default, should be set to null
            
            if (is_numeric($inputCategory)) //The category is an ID
            {
                //Check if category exists
                $categoryQuery0 = $mysql -> query(
                    "SELECT *
                    FROM Categories
                    WHERE id = '" . $mysql -> real_escape_string($inputCategory) . "'"
                );
                
                if (!$categoryQuery0) //If the query failed
                {
                    //die($mysql -> error_get_last());
                    $errors[] = 'Something went wrong while finding the category.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else if ($categoryQuery0 -> num_rows > 0) //That name is already taken
                {
                    $successes[] = 'That category was found!';
                    $categoryID = $categoryQuery0 -> fetch_assoc()['id'];
                }
                else
                {
                    $errors[] = 'That category cannot be found! (maybe it was deleted).';
                }
            }
            else
            {
                $errors[] = 'Invalid category "' . htmlspecialchars($inputCategory) . '"';
            }
            
            //Check if name is taken
            $topicAlreadyExistsQuery = $mysql -> query(
                "SELECT *
                 FROM Topics
                 WHERE name = '" . $mysql -> real_escape_string($inputName) . "'
                 AND category " . ($categoryID == -1 ? "IS NULL" : "= '" . $mysql -> real_escape_string($categoryID) . "'") . "
                 AND id <> " . $mysql -> real_escape_string($inputID)
            );
            
            if (!$topicAlreadyExistsQuery) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while checking for existing topic names.  Please try again.';
                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
            }
            else if ($topicAlreadyExistsQuery -> num_rows > 0) //That name is already taken
            {
                $errors[] = 'That topic name has already been taken for your specified category.';
            }
            else
            {
                $successes[] = 'That topic name is available!';
            }
            
            //Check if ID exists, but only if it is numeric and isn't the default -1.
            if (is_numeric($inputID) and $inputID >= 0)
            {
                $topicIDExistsQuery = $mysql -> query(
                    "SELECT *
                    FROM Topics
                    WHERE id = '" . $mysql -> real_escape_string($inputID) . "'"
                );
                
                if (!$topicIDExistsQuery) //If the query failed
                {
                    $errors[] = 'Something went wrong while checking for existing topic id.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else if ($topicIDExistsQuery -> num_rows > 0) //That id exists
                {
                    $successes[] = 'Topic with id ' . htmlspecialchars($inputID) . ' exists!';
                }
                else
                {
                    $errors[] = 'Cannot edit topic with id ' . htmlspecialchars($inputID) . '. That topic does not exist!';
                }
            }
        }
        
        if (empty($errors)) //No validation errors, create/edit topic!
        {
            if (is_numeric($inputID) and $inputID >= 0) //Edit topic
            {                
                $updateStatement = $mysql -> query("
                    UPDATE Topics
                    SET
                        update_date=NOW(),
                        update_user=" . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        category=" . $mysql -> real_escape_string($inputCategory) . ",
                        name='" . $mysql -> real_escape_string($inputName) . "',
                        locked=b'" . $mysql -> real_escape_string($inputLocked) . "',
                        stickied=b'" . $mysql -> real_escape_string($inputStickied) . "'
                    WHERE id=" . $mysql -> real_escape_string($inputID));
                
                if (!$updateStatement)
                {
                    $errors[] = 'Something went wrong while updating the topic.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else
                {
                    $successes[] = 'Topic updated!';
                    $inputTopicID = $inputID; //Set the insertTopicID for the post DB code
                }
            }
            else //Create Topic
            {                
                $insertStatement = $mysql -> query("
                    INSERT INTO
                        Topics (creation_date, update_date, creation_user, update_user, category, name, locked, stickied)
                    VALUES (
                        NOW(),
                        NOW(),
                        " . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        " . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        " . $mysql -> real_escape_string($inputCategory) . ",
                        '" . $mysql -> real_escape_string($inputName) . "',
                        b'" . $mysql -> real_escape_string($inputLocked) . "',
                        b'" . $mysql -> real_escape_string($inputStickied) . "'
                    )");
                
                if (!$insertStatement)
                {
                    $errors[] = 'Something went wrong while creating the topic.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else
                {
                    $successes[] = 'Topic created!';
                    $inputTopicID = $mysql -> insert_id; //Set the insertTopicID for the post DB code
                    
                    if ($inputTopicID === 0) //0 is returned by default if there was no insert
                        $inputTopicID = -1;
                }
            }
        }
        
        if (empty($errors)) //Only run the post DB code if topic creation was successful
        {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/post/postEditorDBCode.php';
        }
    }
        
    //Calculate other variables for the page to use
    $pageAction = (is_numeric($inputID) and $inputID >= 0) ? 'Update' : 'Create';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/header.php'; ?>
        <link href="/stylesheets/forum.css" rel="stylesheet" type="text/css">
        <link href="/stylesheets/forms.css" rel="stylesheet" type="text/css">
        <link href="/stylesheets/post-editor.css" rel="stylesheet" type="text/css">
        <title>Create Topic</title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-container">
                        
                        <h1 class="form-header"><?php echo "$pageAction Topic"; ?></h1>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($inputID); ?>">
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Name</span></div>
                                <div class="col-sm-8"><input type="text" name="name" autofocus value="<?php echo htmlspecialchars($inputName); ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Category</span></div>
                                <div class="col-sm-8">
                                    <select name="category" data-toggle="tooltip" title="Choose which category this topic will be in">
                                                                                
                                        <?php
                                            $categoryQuery = $mysql -> query(
                                                "SELECT name, id
                                                FROM categories
                                                WHERE deleted=FALSE"
                                            );
                                            
                                            if (!$categoryQuery)
                                            {
                                                $errors[] = 'Couldn\'t retrieve categories from the database!  Try reloading the page.';
                                                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                                            }
                                            else
                                            {
                                                while ($categoryRow = $categoryQuery -> fetch_assoc())
                                                {                                                    
                                                    echo '<option value="' . htmlspecialchars($categoryRow['id']) . '"' . ($categoryRow['id'] == $inputCategory ? ' selected' : '') . '>' . htmlspecialchars($categoryRow['name']) . '</option>';
                                                }
                                            }
                                        ?>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Locked</span></div>
                                <div class="col-sm-8"><input type="checkbox" name="locked" <?php if ($inputLocked) echo 'checked'; ?> data-toggle="tooltip" title="If checked, the topic cannot be replied to"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Stickied</span></div>
                                <div class="col-sm-8"><input type="checkbox" name="stickied" <?php if ($inputStickied) echo 'checked'; ?> data-toggle="tooltip" title="If checked, the topic will be pinned at the top of the category"></div>
                            </div>
                            
                            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/post/postEditorHTML.php'; ?>
                            
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3"><input class="main-button color-white background-blue reset-font-size" type="submit" value="<?php echo "$pageAction Topic"; ?>"></div>
                            </div>
                        </form>
                        
                        <div class="form-errors">
                            <?php
                                if (!empty($errors))
                                {
                                    echo 'Errors editing topic:';
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