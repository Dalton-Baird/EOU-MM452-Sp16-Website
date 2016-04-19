<?php
    /* This file needs to be included in every page that requires access to the MySQL database.
     * It establishes and tests the connection to the MySQL database.
     */

    $server = 'localhost';
    $username = 'root';
    $password = '***REMOVED***';
    $database = 'eou_website';
    
    //Create Connection
    $connection = new mysqli($server, $username, $password, $database);
    
    //Check Connection
    if ($connection -> connect_error)
    die('Connection failed: ' . $connection -> connect_error);
?>