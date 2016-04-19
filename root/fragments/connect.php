<?php
    /* This file needs to be included in every page that requires access to the MySQL database.
     * It establishes and tests the connection to the MySQL database.
     */

    $server = 'localhost';
    $username = 'root';
    $password = '***REMOVED***';
    $database = 'eou_website';
    
    //Create MySQL Connection
    $mysql = new mysqli($server, $username, $password, $database);
    
    //Check MySQL Connection
    if ($mysql -> connect_error)
    die('Connection failed: ' . $mysql -> connect_error);
?>