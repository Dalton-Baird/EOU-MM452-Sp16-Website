<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/UserUtils.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/ForumUtils.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/ErrorUtils.php';

    $inputPostID = -1;
    $inputTopicID = -1;
    $inputPostContent = '';
    
    if (isset($firstPostTopicID) and $firstPostTopicID != null) //Load first post for editing
    {
        $loadPostQuery = $mysql -> query(
            "SELECT *
            FROM Posts
            WHERE topic = '" . $mysql -> real_escape_string($firstPostTopicID) . "'
            ORDER BY creation_date ASC
            LIMIT 1"
        );
        
        if (!$loadPostQuery) //If the query failed
        {
            $errors[] = 'Something went wrong while loading the first post in the topic.  Please try again.';
            $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
        }
        else if ($loadPostQuery -> num_rows < 1)
        {
            $errors[] = 'Failed to load data for first post for topic with id ' . htmlspecialchars($firstPostTopicID) . ', that topic or post was not found!';
        }
        else //It was loaded successfully
        {
            $row = $loadPostQuery -> fetch_assoc();
            
            $inputPostID = (int) $row['id'];
            $inputTopicID = (int) $row['topic'];
            $inputPostContent = $row['post_content'];
            
            $successes[] = 'First post for topic with id ' . htmlspecialchars($firstPostTopicID) . ' loaded successfully.';
        }
    }
    
    if (isset($editPostID) and $editPostID != null) //Load a specific post for editing
    {
        $loadPostQuery = $mysql -> query(
            "SELECT *
            FROM Posts
            WHERE id = '" . $mysql -> real_escape_string($editPostID) . "'"
        );
        
        if (!$loadPostQuery) //If the query failed
        {
            $errors[] = 'Something went wrong while loading the post.  Please try again.';
            $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
        }
        else if ($loadPostQuery -> num_rows < 1)
        {
            $errors[] = 'Failed to load data for post with id ' . htmlspecialchars($editPostID) . ', that post was not found!';
        }
        else //It was loaded successfully
        {
            $row = $loadPostQuery -> fetch_assoc();
            
            $inputPostID = (int) $row['id'];
            $inputTopicID = (int) $row['topic'];
            $inputPostContent = $row['post_content'];
            
            $successes[] = 'Post with id ' . htmlspecialchars($editPostID) . ' loaded successfully.';
        }
    }
    
    if (isset($postTopicID) and is_numeric($postTopicID)) //Create a post in a topic
    {
        $inputTopicID = (int) $postTopicID;
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data
    {
        //Load variables
        if (isset($_POST['postID']) and is_numeric($_POST['postID']))
        {
            $inputPostID = (int) $_POST['postID'];
            
            if ($inputPostID >= 0 and !UserUtils::canEditPost(ForumUtils::findPostByID($inputPostID)))
                ErrorUtils::redirectToCustomErrorPage("You are not allowed to edit that post!");
                //die("You are not allowed to edit that post! TODO: Make a better error message."); //TODO: Make a better error message
        }
        else
            $errors[] = 'Post ID must be set!';
        
        if (isset($_POST['postContent']))
            $inputPostContent = $_POST['postContent'];
        
        //Null / Empty Checks
        if (empty($inputPostContent))
            $errors[] = 'Your post cannot be empty!';
        
        //Valid Input Checks
        if (empty($errors))
        {
            //All variables except the non-required ones are guaranteed to be set at this point
            $maxPostLength = 2 ** 16 - 1; //65535
            
            if (strlen($inputPostContent) < 3 or strlen($inputPostContent) > $maxPostLength)
                $errors[] = 'Post must be 3 - ' . $maxPostLength . ' characters in length.';
        }
        
        //Check for database conflicts
        if (empty($errors))
        {            
            //Check if the post ID exists, but only if it is numeric and isn't the default -1.
            if (is_numeric($inputPostID) and $inputPostID >= 0)
            {
                $postIDQuery = $mysql -> query(
                    "SELECT *
                    FROM Posts
                    WHERE id = '" . $mysql -> real_escape_string($inputPostID) . "'"
                );
                
                if (!$postIDQuery) //If the query failed
                {
                    $errors[] = 'Something went wrong while checking for existing post id.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else if ($postIDQuery -> num_rows > 0) //That id exists
                {
                    $successes[] = 'Post with id ' . htmlspecialchars($inputPostID) . ' exists!';
                }
                else
                {
                    $errors[] = 'Cannot edit post with id ' . htmlspecialchars($inputPostID) . '. That post does not exist!';
                }
            }
        }
    }
?>