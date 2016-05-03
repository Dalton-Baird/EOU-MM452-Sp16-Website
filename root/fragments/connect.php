<?php
    /* This file needs to be included in every page that requires access to the MySQL database.
     * It establishes and tests the connection to the MySQL database.
     */
     
     //The MySQL credentials are stored in a separate file
     require_once $_SERVER['DOCUMENT_ROOT'] . '/fragments/sql-credentials.php';
    
    //Create MySQL Connection
    $mysql = new mysqli($mysql_server, $mysql_username, $mysql_password, $mysql_database);
    
    //Check MySQL Connection
    if ($mysql -> connect_error)
        die('Connection failed: ' . $mysql -> connect_error);
?>