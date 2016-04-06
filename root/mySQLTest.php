<?php
    $servername = "localhost";
    $username = "root";
    $password = "***REMOVED***";
    
    //create connection
    $connection = new mysqli($servername, $username, $password);
    
    //Check connection
    if ($connection -> connect_error)
        die("Connection failed: " . $connection -> connect_error);
    
    echo "Connected successfully!";
?>