<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include '/fragments/header.php'; ?>
        <title>PHP Session Debug</title>
        
        <style>
            .container {
                margin-top: 20vh;
            }
            
            .content {
                background-color: #555555;
                color: #EEEEEE;
                padding: 5%;
            }
            
            table {
                background-color: #888888;
                width: 100%;
            }
            
            th {
                background-color: #666666;
                border-color: #606060 !important;
            }
            
            table, th, td {
                border: 2px solid #666666;
                padding: 2.5%;
                font-size: 1.1em;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <?php include '/fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="content">
                        <h1>PHP Session Variables:</h1>
                        
                        <table>
                            <tr>
                                <th>Variable Name</th>
                                <th>Type</th>
                                <th>Value</th>
                            </tr>
                            <?php
                                foreach ($_SESSION as $key => $value)
                                {
                                    echo '<tr>';
                                    
                                    echo '<td>' . htmlspecialchars($key) . '</td>';
                                    echo '<td>' . htmlspecialchars(gettype($value)) . '</td>';
                                    echo '<td>' . htmlspecialchars($value) . '</td>';
                                    
                                    echo '</tr>';
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include '/fragments/footer-scripts.php'; ?>
    </body>
</html>