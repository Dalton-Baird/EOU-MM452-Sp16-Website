<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . '/fragments/connect.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/UserUtils.php';
            
    $categoryQuery = $mysql -> query(
        "SELECT *
         FROM categories
         WHERE deleted=FALSE");
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
                        
                        <div class="forum-description">
                            Welcome to the Eastern Oregon University Student Forum.
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
                                        echo 'There are no categories yet';
                                    }
                                    else
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
                                                    <a class="forum-table-name-link" href="#"><?php echo htmlspecialchars($categoryRow['name']); ?></a>
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
                                                <td><a class="main-button color-white background-blue button-normal" href="<?php echo '/editCategory.php'; ?>">New</a></td>
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