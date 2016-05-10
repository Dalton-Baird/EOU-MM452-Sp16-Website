<?php
    /** A utilty class for forums related stuff */
    class ForumUtils
    {
        /**
         * Finds the category path for the specified category ID.  If the specified category ID is null,
         * the returned path is 'Index'.
         * Param: mysql: The MySQLi object to use
         * Param: catID: The category ID to find the path for
         * Returns: The full path of the specified category
         */
        public static function findCategoryPath(mysqli $mysql, $catID)
        {
            //DEBUG
            //? ><span style="color: #FF6666;">< ?php echo 'ForumUtils::findCategoryPath(mysqli, ' . htmlspecialchars($catID) . ') called!'; ? ></span><br>< ?php
            
            $result = array('path'=>'', 'parentID'=>$catID);
            
            do
            {
                $result = ForumUtils::findCategoryPathHelper($mysql, $result['parentID'], $result['path']);
                
                //DEBUG
                /*? ><span style="color: #FF6666;">....returned [< ?php
                
                foreach ($result as $key => $value)
                {
                    echo '"' . htmlspecialchars($key) . '"=>"' . htmlspecialchars($value) . '", ';
                }
                
                ? >]</span><br>< ?php
                */
            }
            while (!$result['finished']);
            
            return $result['path'];
        }
        
        /**
         * Helper method for findCategoryPath().
         * Param: mysql: The MySQLi object to use
         * Param: catID: The category ID to find the path for
         * Param: currentSubPath: The current subpath
         * Returns: An associative array containing the current subpath as 'path', and the parent category ID as 'parentID'
         */
        private static function findCategoryPathHelper(mysqli $mysql, $catID, string $currentSubPath)
        {
            //DEBUG
            //? ><span style="color: #FF6666;">< ?php echo 'ForumUtils::findCategoryPathHelper(mysqli, ' . htmlspecialchars($catID) . ', "' . htmlspecialchars($currentSubPath) . '") called!'; ? ></span><br>< ?php
            
            if ($catID == null)
                return array('path'=>ForumUtils::generateCategoryPathLink('Index', '/category.php') . (empty($currentSubPath) ? '' : ' / ') . $currentSubPath, 'parentID'=>null, 'finished'=>true);
            
            $parentCatID = null;
            
            $catQuery = $mysql -> query(
                "SELECT name, id, parent_category
                FROM categories
                WHERE id=" . $mysql -> real_escape_string($catID) . "
                AND deleted=FALSE"
            );
            
            if ($catQuery and $catQuery -> num_rows > 0)
            {
                $catRow = $catQuery -> fetch_assoc();
                
                $parentCatID = $catRow['parent_category'];
                
                $currentSubPath = ForumUtils::generateCategoryPathLink($catRow['name'], '/category.php?id=' . $catRow['id']) . (empty($currentSubPath) ? '' : ' / ') . $currentSubPath;
            }
            
            return array('path'=>$currentSubPath, 'parentID'=>$parentCatID, 'finished'=>false);
        }
        
        /**
         * Generates a category path link, for use by findCategoryPathHelper().
         * Param: pathItem: The name of the path item to generate a link for
         * Param: link: The url to link to
         * Returns: A string containing the generated HTML code
         */
        private static function generateCategoryPathLink(string $pathItem, string $link)
        {
            return '<a href="' . htmlspecialchars($link) . '">' . htmlspecialchars($pathItem) . '</a>';
        }
        
        /**
         * Finds a post by its ID.  Returns the MySQL Row for the post, or null.
         * Param: id: The post's ID
         * Returns: The MySQL Row for the post, or null if no post exists with that ID.
         */
        public static function findPostByID(int $id)
        {
            global $mysql;
            
            $postQuery = $mysql -> query(
                "SELECT *
                 FROM Posts
                 WHERE id=" . $mysql -> real_escape_string($id)
            );
            
            if ($postQuery and $postQuery -> num_rows > 0)
            {
                $postRow = $postQuery -> fetch_assoc();
                
                return $postRow;
            }
            else
            {
                return null;
            }
        }
        
        /**
         * Finds the last post in the topic with the specified ID.  Returns the MySQL Row for the post, or null.
         * Param: id: The topic's ID
         * Returns: The MySQL Row for the last post, or null if there are no posts in the topic (should be impossible).
         */
        public static function findLastPostInTopicByTopicID(int $id)
        {
            global $mysql;
            
            $postQuery = $mysql -> query(
                "SELECT *
                 FROM Posts
                 WHERE topic=" . $mysql -> real_escape_string($id) . "
                 ORDER BY creation_date DESC
                 LIMIT 1"
            );
            
            if ($postQuery and $postQuery -> num_rows > 0)
            {
                $postRow = $postQuery -> fetch_assoc();
                
                return $postRow;
            }
            else
            {
                return null;
            }
        }
        
        /**
         * Finds a topic by its ID.  Returns the MySQL Row for the topic, or null.
         * Param: id: The topic's ID
         * Returns: The MySQL Row for the topic, or null if no topic exists with that ID.
         */
        public static function findTopicByID(int $id)
        {
            global $mysql;
            
            $topicQuery = $mysql -> query(
                "SELECT *
                 FROM Topics
                 WHERE id=" . $mysql -> real_escape_string($id)
            );
            
            if ($topicQuery and $topicQuery -> num_rows > 0)
            {
                $topicRow = $topicQuery -> fetch_assoc();
                
                return $topicRow;
            }
            else
            {
                return null;
            }
        }
        
        /**
         * Finds the last topic in the category with the specified ID.  Returns the MySQL Row for the topic, or null.
         * Param: id: The category's ID
         * Returns: The MySQL Row for the last topic, or null if there are no topic in the category.
         */
        public static function findLastTopicInCategoryByCategoryID(int $id)
        {
            global $mysql;
            
            $topicQuery = $mysql -> query(
                "SELECT *
                 FROM Topics
                 WHERE category=" . $mysql -> real_escape_string($id) . "
                 ORDER BY creation_date DESC
                 LIMIT 1"
            );
            
            if ($topicQuery and $topicQuery -> num_rows > 0)
            {
                $topicRow = $topicQuery -> fetch_assoc();
                
                return $topicRow;
            }
            else
            {
                return null;
            }
        }
    }
?>