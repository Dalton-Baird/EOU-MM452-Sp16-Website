<?php
    /** A utilty class for messing with users */
    class UserUtils
    {
        /** Helper function that returns true if the user is logged in. */
        public static function isLoggedIn()
        {
            if (session_status() == PHP_SESSION_NONE) //Start a session if it hasn't been started yet
                session_start();
            
            return isset($_SESSION['loggedIn']) and $_SESSION['loggedIn'];
        }
        
        /** Helper function that returns true if the user is logged in, and is a moderator. */
        public static function isModerator()
        {
            if (session_status() == PHP_SESSION_NONE) //Start a session if it hasn't been started yet
                session_start();
            
            return isset($_SESSION['user_level']) and $_SESSION['user_level'] >= 1;
        }
        
        /** Helper function that returns true if the user is logged in, and is an admin. */
        public static function isAdmin()
        {
            if (session_status() == PHP_SESSION_NONE) //Start a session if it hasn't been started yet
                session_start();
            
            return isset($_SESSION['user_level']) and $_SESSION['user_level'] >= 2;
        }
        
        /**
         * Finds a user by their ID.  Returns the MySQL Row for the user, or null.
         * Param: id: The user's ID
         * Returns: The MySQL Row for the user, or null if no user exists with that ID.
         */
        public static function findUserByID(int $id)
        {
            global $mysql;
            
            $userQuery = $mysql -> query(
                "SELECT *
                 FROM Users
                 WHERE id=" . $mysql -> real_escape_string($id)
            );
            
            if ($userQuery and $userQuery -> num_rows > 0)
            {
                $userRow = $userQuery -> fetch_assoc();
                
                return $userRow;
            }
            else
            {
                return null;
            }
        }
        
        /**
         * Finds the path to a user's profile image, given a user's ID. If no image
         * is found, it returns the default profile image.
         * Param: id: The user's ID
         * Returns: The path to the user's profile image
         */
        public static function findUserProfileImage(int $id)
        {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/profile_pictures/profile-$id.gif"))
            {
                return "/images/profile_pictures/profile-$id.gif";
            }
            else if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/profile_pictures/profile-$id.png"))
            {
                return "/images/profile_pictures/profile-$id.png";
            }
            else if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/profile_pictures/profile-$id.jpg"))
            {
                return "/images/profile_pictures/profile-$id.jpg";
            }
            else
            {
                return "/images/profile_pictures/default.png";
            }
        }
    }
?>