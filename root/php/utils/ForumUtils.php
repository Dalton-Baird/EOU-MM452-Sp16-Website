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
    }
?>