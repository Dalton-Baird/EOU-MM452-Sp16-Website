<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fragments/connect.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/UserUtils.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/ForumUtils.php';
    
    $categoryID = null;
    $categoryName = null;
    $categoryDescription = null;
    $categoryLocked = true;
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $categoryID = isset($_GET['id']) ? $_GET['id'] : null;
    }
            
    if ($categoryID == null)
    {
        $categoryQuery = $mysql -> query(
        "SELECT *
         FROM categories
         WHERE parent_category IS NULL
         AND deleted=FALSE");
         
         $categoryName = ForumUtils::findCategoryPath($mysql, null);
         $categoryDescription = 'Welcome to the Eastern Oregon University Student Forum.';
    }
    else
    {        
        $categoryQuery = $mysql -> query(
        "SELECT *
         FROM categories
         WHERE parent_category=" . $mysql -> real_escape_string($categoryID) . "
         AND deleted=FALSE");
         
         //Load information about the selected category
         $selectedCategoryQuery = $mysql -> query(
             "SELECT *
              FROM categories
              WHERE id=" . $mysql -> real_escape_string($categoryID) . "
              AND deleted=FALSE"
         );
         
         if ($selectedCategoryQuery and $selectedCategoryQuery -> num_rows > 0)
         {
             $selectedCategoryRow = $selectedCategoryQuery -> fetch_assoc();
             
             $categoryName = ForumUtils::findCategoryPath($mysql, $selectedCategoryRow['id']);
             //$categoryName = $selectedCategoryRow['name'];
             $categoryDescription = $selectedCategoryRow['description'];
             $categoryLocked = $selectedCategoryRow['locked'] == true;
         }
    }
    
    if ($categoryID == null)
    {
        $topicQuery = $mysql -> query(
            "SELECT *
             FROM Topics
             WHERE category IS NULL"
        );
    }
    else
    {
        $topicQuery = $mysql -> query(
            "SELECT *
             FROM Topics
             WHERE category=" . $mysql -> real_escape_string($categoryID)
        );
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/header.php'; ?>
        <link href="stylesheets/forum.css" rel="stylesheet" type="text/css">
        <title>EOU Forum</title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-xs-12">
                    <div class="inner-container">
                        
                        <div class="forum-category-title">
                            <?php echo $categoryName; //Don't escape with htmlspecialchars(), as the HTML links are generated ?>
                        </div>
                        
                        <div class="forum-description">
                            <?php echo htmlspecialchars($categoryDescription); ?>
                        </div>
                        
                        <div class="forum-above-content-controls">
                            
                            <?php if (UserUtils::isLoggedIn() and !$categoryLocked) { ?>
                                <a class="main-button color-white background-blue button-normal spacing-margin-left-half" href="/editTopic.php<?php echo $categoryID != null ? '?category=' . htmlspecialchars($categoryID) : ''; ?>">Create Topic</a>
                            <?php } ?>
                            
                            <?php if (UserUtils::isModerator()) { ?>
                                <a class="main-button color-white background-blue button-normal spacing-margin-left-half" href="/editCategory.php<?php echo $categoryID != null ? '?parent_category=' . htmlspecialchars($categoryID) : ''; ?>">Create Subcategory</a>
                            <?php } ?>
                            
                            <?php if (UserUtils::isModerator() and $categoryID != null) { ?>
                                <a class="main-button color-white background-blue button-normal spacing-margin-left-half" href="/editCategory.php?id=<?php echo htmlspecialchars($categoryID); ?>">Edit Category</a>
                            <?php } ?>
                            
                        </div>
                        
                        <div>
                            <?php
                                $hasCategories = false;
                                $hasTopics = false;
                            
                                if (!$categoryQuery)
                                {
                                    echo 'Couldn\'t retrieve categories from the database.  Please try again.<br>';
                                    echo '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                                }
                                else
                                {
                                    if ($categoryQuery -> num_rows < 1)
                                    {
                                        ?><div class="forum-no-categories">There are no subcategories yet.</div><?php
                                    }
                                    //else
                                    if ($categoryQuery -> num_rows > 0)
                                    {
                                        $hasCategories = true;
                                        
                                        //Prepare to display categories
                                        ?>
                                        <table class="category-table">
                                            <tr>
                                                <th class="category-table-header-category background-blue">Category</th>
                                                <th class="background-blue">Last Topic</th>
                                        <?php
                                        
                                        if (UserUtils::isModerator())
                                        {
                                            ?><th class="category-table-header-edit background-blue">Edit</th><?php
                                        }
                                        
                                        echo '</tr>';
                                        
                                        while ($categoryRow = $categoryQuery -> fetch_assoc())
                                        {
                                            $lastTopicID = -1;
                                            $lastTopicName = 'Unknown Topic';
                                            $lastTopicUserName = 'Unknown User';
                                            $lastTopicCreationDate = 'Unknown Date';
                                            
                                            //Find the last topic
                                            $lastTopic = ForumUtils::findLastTopicInCategoryByCategoryID($categoryRow['id']);
                                            
                                            if ($lastTopic != null)
                                            {
                                                $lastTopicID = $lastTopic['id'];
                                                $lastTopicName = $lastTopic['name'];
                                                
                                                $lastTopicCreationUser = UserUtils::findUserByID($lastTopic['creation_user']);
                                                
                                                if ($lastTopicCreationUser != null)
                                                    $lastTopicUserName = $lastTopicCreationUser['name'];
                                                
                                                $lastTopicCreationDate = $lastTopic['creation_date'];                                                
                                            }
                                            
                                            ?>
                                            <tr>
                                                <td>
                                                    <a class="forum-table-name-link" href="/category.php?id=<?php echo htmlspecialchars($categoryRow['id']); ?>"><?php echo htmlspecialchars($categoryRow['name']); ?></a>
                                                    <br>
                                                    <span class="forum-table-name-description"><?php echo htmlspecialchars($categoryRow['description']); ?></span>
                                                </td>
                                                
                                                <td>
                                                    <?php
                                                    if ($lastTopicID != -1)
                                                    { ?>
                                                        <a class="forum-table-name-link" href="/topic.php?id=<?php echo htmlspecialchars($lastTopicID); ?>"><?php echo htmlspecialchars($lastTopicName); ?></a>
                                                        <br>
                                                        <span class="forum-table-name-description">By <?php echo htmlspecialchars($lastTopicUserName); ?></span>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        echo 'No Topics';
                                                    } ?>
                                                </td>
                                                <?php
                                                    if (UserUtils::isModerator())
                                                    {
                                                        ?><td><a class="main-button color-white background-blue button-normal" href="<?php echo '/editCategory.php?id=' . htmlspecialchars($categoryRow['id']); ?>">Edit</a></td><?php
                                                    }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                        
                                        //End displaying categories
                                        echo '</table>';
                                        
                                        //TEST
                                        /*
                                        <button class="main-button color-white background-blue button-small">Test</button>
                                        <button class="main-button color-white background-blue button-normal">Test</button>
                                        <button class="main-button color-white background-blue button-large">Test</button>
                                        <button class="main-button color-white background-blue button-huge">Test</button>
                                        <a class="main-button color-white background-blue button-small">Test</a>
                                        <a class="main-button color-white background-blue button-normal">Test</a>
                                        <a class="main-button color-white background-blue button-large">Test</a>
                                        <a class="main-button color-white background-blue button-huge">Test</a>
                                        */
                                    }
                                }
                            ?>
                        </div>
                        
                        <div>
                            <?php
                                if (!$categoryLocked)
                                {
                                    if (!$topicQuery)
                                    {
                                        echo 'Couldn\'t retrieve topics from the database.  Please try again.<br>';
                                        echo '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                                    }
                                    else
                                    {
                                        if ($topicQuery -> num_rows < 1)
                                        {
                                            ?><div class="forum-no-categories">There are no topics yet.</div><?php
                                        }
                                        //else
                                        if ($topicQuery -> num_rows > 0)
                                        {
                                            $hasTopics = true;
                                            
                                            //Prepare to display categories
                                            ?>
                                            <table class="category-table">
                                                <tr>
                                                    <th class="category-table-header-category background-blue">Topic</th>
                                                    <th class="background-blue">Creation Date</th>
                                                    <th class="background-blue">Last Post By</th>
                                            <?php
                                            
                                            echo '</tr>';
                                            
                                            while ($topicRow = $topicQuery -> fetch_assoc())
                                            {
                                                $topicCreationUserName = 'Unknown User';
                                                $lastPostUserName = 'Unknown User';
                                                $lastPostCreationDate = 'Unknown Date';
                                                
                                                //Find information about the topic creation user
                                                $topicCreationUser = UserUtils::findUserByID($topicRow['creation_user']);
                                                
                                                if ($topicCreationUser != null)
                                                    $topicCreationUserName = $topicCreationUser['name'];
                                                
                                                //Find the last post in the topic
                                                $lastPost = ForumUtils::findLastPostInTopicByTopicID($topicRow['id']);
                                                
                                                if ($lastPost != null)
                                                {
                                                    $lastPostCreationUser = UserUtils::findUserByID($lastPost['creation_user']);
                                                    
                                                    if ($lastPostCreationUser != null)
                                                        $lastPostUserName = $lastPostCreationUser['name'];
                                                    
                                                    $lastPostCreationDate = $lastPost['creation_date'];
                                                }
                                                
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a class="forum-table-name-link" href="/topic.php?id=<?php echo htmlspecialchars($topicRow['id']); ?>"><?php echo htmlspecialchars($topicRow['name']); ?></a>
                                                        <br>
                                                        <span class="forum-table-name-description">By <?php echo htmlspecialchars($topicCreationUserName); ?></span>
                                                    </td>
                                                    
                                                    <td><?php echo htmlspecialchars($topicRow['creation_date']); ?></td>
                                                    <td><a class="forum-table-name-link" href="#"><?php echo htmlspecialchars($lastPostUserName); ?></a></td>
                                                </tr>
                                                <?php
                                            }
                                            
                                            //End displaying topics
                                            echo '</table>';
                                        }
                                    }
                                }
                                
                                if (!$hasCategories and !$hasTopics) //No categories or topics
                                {
                                    echo '<div>&nbsp;</div>'; //Extra div at the bottom to add padding
                                }
                            ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            
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