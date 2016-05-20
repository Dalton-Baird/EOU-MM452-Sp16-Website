<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . '/fragments/connect.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/fragments/require-login.php');
    
    //Variables used when rendering the document:
    $errors = array(); //An array of errors to show the user
    $successes = array(); //An array of success messages to show the user_error
    $editPostID = null;
    $postTopicID = null;
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') //Edit post
    {
        $editPostID = isset($_GET['id']) ? $_GET['id'] : null;
        $postTopicID = isset($_GET['topic']) ? $_GET['topic'] : null;
        
        if ($editPostID == null and $postTopicID == null)
            die('ERROR: Post ID or Topic ID must be specified! TODO: Make a better error page.'); //TODO: Make a better error page
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data
    {
        if (isset($_POST['topicID']) and is_numeric($_POST['topicID']))
            $postTopicID = $_POST['topicID'];
        
        if (!isset($_POST['topicID']) or !is_numeric($_POST['topicID']) or $_POST['topicID'] < 0)
            die('Topic ID must be specified!');
    }
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/post/postEditorValidationCode.php';
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/post/postEditorDBCode.php';
    }
        
    //Calculate other variables for the page to use
    $pageAction = (is_numeric($inputPostID) and $inputPostID >= 0) ? 'Update' : 'Create';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/header.php'; ?>
        <link href="/stylesheets/forum.css" rel="stylesheet" type="text/css">
        <link href="/stylesheets/forms.css" rel="stylesheet" type="text/css">
        <link href="/stylesheets/post-editor.css" rel="stylesheet" type="text/css">
        <title><?php echo "$pageAction Post"; ?></title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-container">
                        
                        <h1 class="form-header"><?php echo "$pageAction Post"; ?></h1>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
                            <input type="hidden" name="topicID" value="<?php echo htmlspecialchars($inputTopicID); ?>">
                            
                            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/post/postEditorHTML.php'; ?>
                            
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3"><input class="main-button color-white background-blue reset-font-size" type="submit" value="<?php echo "$pageAction Post"; ?>"></div>
                            </div>
                        </form>
                        
                        <div class="form-errors">
                            <?php
                                if (!empty($errors))
                                {
                                    echo 'Errors editing post:';
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