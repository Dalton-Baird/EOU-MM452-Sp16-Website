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
        
        /** Helper function that returns true if the specified user is a moderator. */
        public static function isUserModerator($userRow)
        {
            return $userRow['user_level'] >= 1;
        }
        
        /** Helper function that returns true if the specified user is an admin. */
        public static function isUserAdmin($userRow)
        {
            return $userRow['user_level'] >= 2;
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
        
        /**
         * Finds out if the currently logged in user has permission to edit a post. They would
         * have permission if they are the user that created the post, or they are a modeator
         * or admin.
         * Param: postRow: The SQL row for the post that the user wants to edit
         * Returns: True if the user can edit the post, false otherwise
         */
        public static function canEditPost($postRow)
        {
            return UserUtils::isModerator() or $postRow['creation_user'] == $_SESSION['user_id'];
        }
        
        /**
         * Finds out if a user has permission to edit a post. They would have permission
         * if they are the user that created the post, or they are a moderator or admin.
         * Param: userRow: The SQL row for the user
         * Param: postRow: The SQL row for the post that the user wants to edit
         * Returns: True if the user can edit the post, false otherwise
         */
        public static function canUserEditPost($userRow, $postRow)
        {
            return UserUtils::isUserModerator($userRow) or $postRow['creation_user'] == $userRow['id'];
        }
        
        /**
         * Gets the MySQL row for the given user's user level.
         * Param: userRow: The SQL object for the user
         * Returns: The SQL object for the user's level, or null if it could not be found
         */
        public static function getUserLevel($userRow)
        {
            global $mysql;
            
            $userLevelQuery = $mysql -> query(
                "SELECT *
                 FROM UserLevels
                 WHERE level_number=" . $mysql -> real_escape_string($userRow['user_level'])
            );
            
            if (!$userLevelQuery or $userLevelQuery -> num_rows < 1)
                return null;
            
            return $userLevelQuery -> fetch_assoc();
        }
    }
?>