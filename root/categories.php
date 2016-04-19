<?php
    session_start();
    require_once('/fragments/connect.php');
            
    $categoryQuery = $mysql -> query(
        "SELECT *
         FROM categories
         WHERE deleted=FALSE");
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fragments/header.php'; ?>
        <link href="stylesheets/forum.css" rel="stylesheet" type="text/css">
        <title>EOU Forum</title>
    </head>
    <body>
        <div class="container-fluid">
            <?php include 'fragments/menu.php'; ?>
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
                                    echo '[DEBUG]: MySQL Error: ' . $mysql -> error;
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
                                        echo '
                                        <table border="1" cellspacing="0">
                                            <tr>
                                                <th>Category</th>
                                                <th>Last Topic</th>
                                            </tr>
                                        ';
                                        
                                        while ($categoryRow = $categoryQuery -> fetch_assoc())
                                        {
                                            echo '<tr>';
                                                echo '<td>';
                                                    echo $categoryRow;
                                                echo '</td>';
                                                echo '<td>';
                                                    echo 'TODO';
                                                echo '</td>';
                                        }
                                        
                                        //End displaying categories
                                        echo '</table>';
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
        
        <?php include 'fragments/footer-scripts.php'; ?>
    </body>
</html>