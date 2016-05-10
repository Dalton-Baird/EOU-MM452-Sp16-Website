<?php
    //Make sure the validation code runs before this.  The code should be included by the page including this.
    require_once $_SERVER['DOCUMENT_ROOT'] . '/post/postEditorValidationCode.php';
    
    if (!isset($inputTopicID)) //Default to -1
        $inputTopicID = -1;

    if (empty($errors)) //No validation errors, create/edit post!
        {
            if (is_numeric($inputPostID) and $inputPostID >= 0) //Edit post
            {                
                $updateStatement = $mysql -> query("
                    UPDATE Posts
                    SET
                        update_date=NOW(),
                        update_user=" . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        topic=" . $mysql -> real_escape_string($inputTopicID) . ",
                        post_content='" . $mysql -> real_escape_string($inputPostContent) . "'
                    WHERE id=" . $mysql -> real_escape_string($inputPostID));
                
                if (!$updateStatement)
                {
                    $errors[] = 'Something went wrong while updating the post.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else
                {
                    $successes[] = 'Post updated!';
                }
            }
            else //Create Post
            {                
                $insertStatement = $mysql -> query("
                    INSERT INTO
                        Posts (creation_date, update_date, creation_user, update_user, topic, post_content)
                    VALUES (
                        NOW(),
                        NOW(),
                        " . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        " . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        " . $mysql -> real_escape_string($inputTopicID) . ",
                        '" . $mysql -> real_escape_string($inputPostContent) . "'
                    )");
                
                if (!$insertStatement)
                {
                    $errors[] = 'Something went wrong while creating the post.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else
                {
                    $successes[] = 'Post created!';
                }
            }
        }
?>