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
                            
                            <?php if (UserUtils::isModerator() and $categoryID != null) { ?>
                                <a class="main-button color-white background-blue button-normal spacing-margin-left-half" href="/editCategory.php?id=<?php echo htmlspecialchars($categoryID); ?>">Edit Category</a>
                            <?php } ?>
                            
                        </div>
                        
                        <div>
                            <?php
                                if (!$categoryQuery)
                                {
                                    echo 'Couldn\'t retrieve categories from the database.  Please try again.<br>';
                                    echo '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                                }
                                else
                                {
                                    if ($categoryQuery -> num_rows < 1)
                                    {
                                        ?><div class="forum-no-categories">There are no topics or subcategories yet.</div><?php
                                    }
                                    //else
                                    if ($categoryQuery -> num_rows > 0 or UserUtils::isModerator())
                                    {
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
                                            ?>
                                            <tr>
                                                <td>
                                                    <a class="forum-table-name-link" href="/category.php?id=<?php echo htmlspecialchars($categoryRow['id']); ?>"><?php echo htmlspecialchars($categoryRow['name']); ?></a>
                                                    <br>
                                                    <span class="forum-table-name-description"><?php echo htmlspecialchars($categoryRow['description']); ?></span>
                                                </td>
                                                
                                                <td><a class="forum-table-name-link" href="#">TODO</a></td>
                                                <?php
                                                    if (UserUtils::isModerator())
                                                    {
                                                        ?><td><a class="main-button color-white background-blue button-normal" href="<?php echo '/editCategory.php?id=' . htmlspecialchars($categoryRow['id']); ?>">Edit</a></td><?php
                                                    }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                        
                                        if (UserUtils::isModerator()) //Add "Create New" controls
                                        {
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><a class="main-button color-white background-blue button-normal" href="<?php echo '/editCategory.php' . ($categoryID != null ? '?parent_category=' . htmlspecialchars($categoryID) : ''); ?>">New</a></td>
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