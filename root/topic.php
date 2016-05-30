<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fragments/connect.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/UserUtils.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/ForumUtils.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/ErrorUtils.php';
    
    $topicID = null;
    $topicName = "Unknown Topic";
    $topicLocked = true;
    $topicStickied = false;
    $topicCreationDate = "Unknown Date";
    $topicCreationUserID = null;
    $topicCreationUserName = "Unknown User";
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $topicID = isset($_GET['id']) ? $_GET['id'] : null;
    }
    
    if ($topicID != null) //Load topic
    {
        $topicQuery = $mysql -> query(
            "SELECT *
             FROM Topics
             WHERE id=" . $mysql -> real_escape_string($topicID)
        );
        
        if ($topicQuery and $topicQuery -> num_rows > 0)
        {
            $topicRow = $topicQuery -> fetch_assoc();
            
            $topicName = $topicRow['name'];
            $topicLocked = $topicRow['locked'] == true;
            $topicStickied = $topicRow['stickied'] == true;
            $topicCreationDate = $topicRow['creation_date'];
            $topicCreationUserID = $topicRow['creation_user'];
            
            $topicCreationUser = UserUtils::findUserByID($topicCreationUserID);
            
            if ($topicCreationUser != null)
            {
                $topicCreationUserName = $topicCreationUser['name'];
            }
        }
        else
        {
            ErrorUtils::redirectToCustomErrorPage("Topic with ID " . htmlspecialchars($topicID) . " not found!", "Topic Not Found");
        }
    }
    else
    {
        //die("Topic ID is null! (TODO: Show a better error)");
        ErrorUtils::redirectToCustomErrorPage("Topic ID is null!", "Invalid Topic URL");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/header.php'; ?>
        <link href="stylesheets/topic.css" rel="stylesheet" type="text/css">
        <link href="stylesheets/bbcode.css" rel="stylesheet" type="text/css">
        <title><?php echo htmlspecialchars($topicName); ?></title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row header-container">
                <div class="col-xs-6">
                    <div class="title"><?php echo htmlspecialchars($topicName); ?></div>
                </div>
                
                <div class="col.xs-6">
                    <div class="topic-above-content-controls">
                            
                        <?php if (UserUtils::isLoggedIn() and !$topicLocked) { ?>
                            <a class="main-button color-white background-blue button-normal spacing-margin-left-half" href="/editPost.php?topic=<?php echo htmlspecialchars($topicID); ?>">Reply</a>
                        <?php } ?>
                        
                        <?php if ((UserUtils::isModerator() or (UserUtils::isLoggedIn() and $topicCreationUserID == $_SESSION['user_id'])) and $topicID != null) { ?>
                            <a class="main-button color-white background-blue button-normal spacing-margin-left-half" href="/editTopic.php<?php echo $topicID != null ? '?id=' . htmlspecialchars($topicID) : ''; ?>">Edit Topic</a>
                        <?php } ?>
                        
                    </div>
                </div>
                
                
            </div>
            
            <?php
            
                $postsQuery = $mysql -> query(
                    "SELECT *
                     FROM Posts
                     WHERE topic=" . $mysql -> real_escape_string($topicID) . "
                     ORDER BY creation_date"
                );
                
                if ($postsQuery)
                {
                    $postNumber = 1;
                    
                    while ($postRow = $postsQuery -> fetch_assoc()) //Display posts
                    {
                        $postUser = UserUtils::findUserByID($postRow['creation_user']);
                        $postUpdateUser = UserUtils::findUserByID($postRow['update_user']);
                        
                        ?>
                        
                        <div class="row post-bootstrap-row" id="post-<?php echo htmlspecialchars($postNumber); ?>">
                            <div class="col-xs-3 post-user-bootstrap-column">
                                <div class="post-column post-user-column">
                                    <div class="post-user-name"><?php echo htmlspecialchars($postUser['name']) ?></div>
                                    <img class="post-user-profile-picture" src="<?php echo htmlspecialchars(UserUtils::findUserProfileImage($postUser['id'])); ?>" width="128" height="128" alt="<?php echo htmlspecialchars($postUser['name']); ?>'s Profile Picture">
                                    <div class="post-user-details">
                                        <?php echo '<div>User Level: ' . htmlspecialchars(UserUtils::getUserLevel($postUser)['name'] ?? 'Unknown') . '</div>'; ?>
                                        <?php if (!empty($postUser['major'])) echo '<div>Major: ' . htmlspecialchars($postUser['major']) . '</div>'; ?>
                                        <?php if (!empty($postUser['minor'])) echo '<div>Minor: ' . htmlspecialchars($postUser['minor']) . '</div>'; ?>
                                        <?php if (!empty($postUser['position'])) echo '<div>Position: ' . htmlspecialchars($postUser['position']) . '</div>'; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xs-9 post-content-boostrap-column">
                                <div class="post-column post-content-column">
                                    
                                    <span class="post-header"><?php echo htmlspecialchars($postUser['name']) . ' at ' . htmlspecialchars($postRow['creation_date']) . ':'; ?></span>
                                    
                                    <div class="post-right-content">
                                        
                                        <?php if (UserUtils::canEditPost($postRow)) { ?>
                                        
                                            <a href="/editPost.php?id=<?php echo htmlspecialchars($postRow['id']); ?>">Edit</a>
                                        
                                        <?php } ?>
                                        
                                        <a href="#post-<?php echo htmlspecialchars($postNumber); ?>">#<?php echo htmlspecialchars($postNumber); ?></a>
                                    </div>
                                    
                                    <?php /* Since this div uses white-space: pre-wrap, there must be no additional whitespace
                                    from the PHP source file */ ?>
                                    <div class="post-content"><?php echo ForumUtils::parseBBCode($postRow['post_content']); ?></div>
                                    
                                    <?php /* <span class="post-footer"><? php echo 'Last edited by ' . htmlspecialchars($postUpdateUser['name']) . ' at ' . htmlspecialchars($postRow['update_date']); ? ></span> */ ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                        
                        $postNumber++; //Increment post number
                    }
                }
            
            ?>
            
            <div class="row">
                <div class="col-xs-12">
                    <div class="forum-footer">
                        
                    </div>
                </div>
            </div>
            
        </div>
        
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/footer-scripts.php'; ?>
    </body>
</html>