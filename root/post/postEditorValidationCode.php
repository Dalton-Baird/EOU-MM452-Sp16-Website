<?php
    $inputPostID = -1;
    $inputTopicID = -1;
    $inputPostContent = '';
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data
    {
        //Load variables
        if (isset($_POST['postID']) and is_numeric($_POST['postID']))
            $inputPostID = (int) $_POST['postID'];
        
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