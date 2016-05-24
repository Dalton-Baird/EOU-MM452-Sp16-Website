<?php
    /** A utilty class for handling errors */
    class ErrorUtils
    {
        /**
         * Sends an HTTP redirect header that redirects the user to a custom error page,
         * which shows a custom error message. This function will call die() if the HTTP
         * header has already been sent. This function never returns.
         * Param: message: The custom error message to show
         */
        public static function redirectToCustomErrorPage(string $message, string $title = "Error")
        {
            if (headers_sent())
                die("Failed to redirect user to custom error page: HTTP headers already sent!");
            
            if (session_status() == PHP_SESSION_NONE) //Start a session if it hasn't been started yet
                session_start();
            
            //Pass the error message and title to the error page with session variables
            $_SESSION['errorMessage'] = $message;
            $_SESSION['errorTitle'] = $title;
            
            header("Location: /errors/custom.php");
            exit;
            
            /* //Doesn't quite work
            $host = $_SERVER['SERVER_NAME'];
            $path = "/errors/custom.php";
            $data = "title=$title&message=$message";
            $dataEncoded = urlencode($data);
            
            header("POST $path HTTP/1.1\r\n");
            header("Host: $host\r\n");
            header("Content-type: application/x-www-form-urlencoded\r\n");
            header("Content-length: " . strlen($data) . "\r\n");
            header("Connection: close\r\n");
            header($data);
            exit;
            */
        }
        
        /**
         * Returns a string showing the last MySQL error and error number.
         */
        public static function getMySQLError()
        {
            global $mysql;
            
            return 'MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
        }
    }
?>