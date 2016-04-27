<?php
    session_start();
    require_once('/fragments/connect.php');
    
    //Variables used when rendering the document:
    $errors = array(); //An array of errors to show the user
    $successes = array(); //An array of success messages to show the user
    $inputID = -1;
    $inputName = '';
    $inputDescription = '';
    $inputParentCategory = '';
    $inputLocked = false;
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') //Edit category
    {
        $categoryID = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($categoryID != null) //Load category for editing
        {
            $loadCategoryQuery = $mysql -> query(
                "SELECT *
                FROM Categories
                WHERE id = '" . $mysql -> real_escape_string($categoryID) . "'"
            );
            
            if (!$loadCategoryQuery) //If the query failed
                {
                    //die($mysql -> error_get_last());
                    $errors[] = 'Something went wrong while loading the category.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else if ($loadCategoryQuery -> num_rows < 1)
                {
                    $errors[] = 'Failed to load data for category with id ' . htmlspecialchars($categoryID) . ', that category was not found!';
                }
                else //It was loaded successfully
                {
                    $row = $loadCategoryQuery -> fetch_assoc();
                    
                    $inputID = (int) $row['id'];
                    $inputName = $row['name'];
                    $inputDescription = $row['description'];
                    $inputParentCategory = $row['parent_category']; //If this is not numeric, then it is "nothing"
                    $inputLocked = $row['locked'] == true; //Make sure this converts to boolean
                    
                    $successes[] = 'Category with id ' . htmlspecialchars($categoryID) . ' loaded successfully.';
                }
        }
    }    
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') //Process form data
    {
        //Load variables
        if (isset($_POST['id']) and is_numeric($_POST['id']))
            $inputID = (int) $_POST['id'];
        
        if (isset($_POST['name']))
            $inputName = $_POST['name'];
        
        if (isset($_POST['description']))
            $inputDescription = $_POST['description'];
        
        if (isset($_POST['parentCategory']))
            $inputParentCategory = $_POST['parentCategory'];
        
        $inputLocked = isset($_POST['locked']) and !empty($_POST['locked']);
        
        //Null / Empty Checks
        if (empty($inputName))
            $errors[] = 'You must enter a category name.';
        
        //if (empty($inputDescription))
        //    $errors[] = 'You must enter a category description.';
        
        //Valid Input Checks
        if (empty($errors))
        {
            //All variables except the non-required ones are guaranteed to be set at this point
            
            if (strlen($inputName) < 3 or strlen($inputName) > 45)
                $errors[] = 'Category name must be 3 - 45 characters in length.';
            
            //if (strlen($inputDescription) < 3 or strlen($inputDescription) > 150)
            //    $errors[] = 'Category description must be 3 - 150 characters in length.';
            
            if (strlen($inputDescription) > 150)
                $errors[] = 'Category description must be 0 - 150 characters in length.';
        }
        
        //Check for database conflicts
        if (empty($errors))
        {
            $parentCategoryID = -1; //-1 default, should be set to null
            
            if (is_numeric($inputParentCategory)) //The parent category is an ID
            {
                //Check if parent category exists
                $categoryQuery0 = $mysql -> query(
                    "SELECT *
                    FROM Categories
                    WHERE id = '" . $mysql -> real_escape_string($inputParentCategory) . "'"
                );
                
                if (!$categoryQuery0) //If the query failed
                {
                    //die($mysql -> error_get_last());
                    $errors[] = 'Something went wrong while finding the parent category.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else if ($categoryQuery0 -> num_rows > 0) //That name is already taken
                {
                    $successes[] = 'That parent category was found!';
                    $parentCategoryID = $categoryQuery0 -> fetch_assoc()['id'];
                }
                else
                {
                    $errors[] = 'That parent category cannot be found! (maybe it was deleted).';
                }
            }
            else if ($inputParentCategory == 'None' or $inputParentCategory == 'none')
            {
                $inputParentCategory = null; //Set it to null, for the create / update code below
            }
            else
            {
                $errors[] = 'Invalid parent category "' . htmlspecialchars($inputParentCategory) . '"';
            }
            
            //Check if parent category ID is same ID
            if ($parentCategoryID != -1 and $parentCategoryID == $inputID)
            {
                $errors[] = 'You cannot set a category\'s parent category to itself!';
            }
            
            //Check if name is taken
            $categoryQuery1 = $mysql -> query(
                "SELECT *
                 FROM Categories
                 WHERE name = '" . $mysql -> real_escape_string($inputName) . "'
                 AND parent_category " . ($parentCategoryID == -1 ? "IS NULL" : "= '" . $mysql -> real_escape_string($parentCategoryID) . "'") . "
                 AND id <> " . $mysql -> real_escape_string($inputID)
            );
            
            if (!$categoryQuery1) //If the query failed
            {
                //die($mysql -> error_get_last());
                $errors[] = 'Something went wrong while checking for existing category names.  Please try again.';
                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
            }
            else if ($categoryQuery1 -> num_rows > 0) //That name is already taken
            {
                $errors[] = 'That category name has already been taken for your specified parent category.';
            }
            else
            {
                $successes[] = 'That category name is available!';
            }
            
            //Check if ID exists, but only if it is numeric and isn't the default -1.
            if (is_numeric($inputID) and $inputID >= 0)
            {
                $categoryQuery2 = $mysql -> query(
                    "SELECT *
                    FROM Categories
                    WHERE id = '" . $mysql -> real_escape_string($inputID) . "'"
                );
                
                if (!$categoryQuery2) //If the query failed
                {
                    //die($mysql -> error_get_last());
                    $errors[] = 'Something went wrong while checking for existing category id.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else if ($categoryQuery2 -> num_rows > 0) //That id exists
                {
                    $successes[] = 'Category with id ' . htmlspecialchars($inputID) . ' exists!';
                }
                else
                {
                    $errors[] = 'Cannot edit category with id ' . htmlspecialchars($inputID) . '. That category does not exist!';
                }
            }
        }
        
        if (empty($errors)) //No validation errors, create/edit category!
        {
            if (is_numeric($inputID) and $inputID >= 0) //Edit category
            {                
                $updateStatement = $mysql -> query("
                    UPDATE Categories
                    SET
                        update_date=NOW(),
                        update_user=" . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        parent_category=" . ($inputParentCategory == null ? "NULL" : $mysql -> real_escape_string($inputParentCategory)) . ",
                        name='" . $mysql -> real_escape_string($inputName) . "',
                        description='" . $mysql -> real_escape_string($inputDescription) . "',
                        locked=b'" . $mysql -> real_escape_string($inputLocked) . "'
                    WHERE id=" . $mysql -> real_escape_string($inputID));
                
                if (!$updateStatement)
                {
                    $errors[] = 'Something went wrong while updating the category.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else
                {
                    $successes[] = 'Category updated!';
                }
            }
            else //Create Category
            {                
                $insertStatement = $mysql -> query("
                    INSERT INTO
                        Categories (creation_date, update_date, creation_user, update_user, parent_category, name, description, locked)
                    VALUES (
                        NOW(),
                        NOW(),
                        " . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        " . $mysql -> real_escape_string($_SESSION['user_id']) . ",
                        " . ($inputParentCategory == null ? "NULL" : $mysql -> real_escape_string($inputParentCategory)) . ",
                        '" . $mysql -> real_escape_string($inputName) . "',
                        '" . $mysql -> real_escape_string($inputDescription) . "',
                        b'" . $mysql -> real_escape_string($inputLocked) . "'
                    )");
                
                if (!$insertStatement)
                {
                    $errors[] = 'Something went wrong while creating the category.  Please try again.';
                    $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                }
                else
                {
                    $successes[] = 'Category created!';
                }
            }
        }
    }
        
    //Calculate other variables for the page to use
    $pageAction = (is_numeric($inputID) and $inputID >= 0) ? 'Update' : 'Create';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include '/fragments/header.php'; ?>
        <link href="stylesheets/forum.css" rel="stylesheet" type="text/css">
        <link href="/stylesheets/forms.css" rel="stylesheet" type="text/css">
        <title>Create Category</title>
    </head>
    <body>
    <div class="container-fluid">
        <?php include 'fragments/menu.php'; ?>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-container">
                        
                        <h1 class="form-header"><?php echo "$pageAction Forum Category"; ?></h1>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($inputID); ?>">
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Name</span></div>
                                <div class="col-sm-8"><input type="text" name="name" autofocus value="<?php echo htmlspecialchars($inputName); ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Description</span></div>
                                <div class="col-sm-8"><textarea name="description"><?php echo htmlspecialchars($inputDescription); ?></textarea></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Parent Category</span></div>
                                <div class="col-sm-8">
                                    <select name="parentCategory" data-toggle="tooltip" title="Choose which category this category will be in">
                                        <option value="none">None</option>
                                        
                                        <?php
                                            $categoryQuery = $mysql -> query(
                                                "SELECT name, id
                                                FROM categories
                                                WHERE deleted=FALSE"
                                            );
                                            
                                            if (!$categoryQuery)
                                            {
                                                $errors[] = 'Couldn\'t retrieve categories from the database!  Try reloading the page.';
                                                $errors[] = '[DEBUG]: MySQL Error #' . $mysql -> errno . ': ' . $mysql -> error;
                                            }
                                            else
                                            {
                                                while ($categoryRow = $categoryQuery -> fetch_assoc())
                                                {
                                                    if ($categoryRow['id'] == $inputID) //Don't generate an option for the category we're editing
                                                        continue;
                                                    
                                                    echo '<option value="' . htmlspecialchars($categoryRow['id']) . '"' . ($categoryRow['id'] == $inputParentCategory ? ' selected' : '') . '>' . htmlspecialchars($categoryRow['name']) . '</option>';
                                                }
                                            }
                                        ?>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-4"><span class="label">Locked</span></div>
                                <div class="col-sm-8"><input type="checkbox" name="locked" <?php if ($inputLocked) echo 'checked'; ?> data-toggle="tooltip" title="If checked, new topics cannot be created in this category"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3"><input class="main-button color-white background-blue reset-font-size" type="submit" value="<?php echo "$pageAction Category"; ?>"></div>
                            </div>
                        </form>
                        
                        <div class="form-errors">
                            <?php
                                if (!empty($errors))
                                {
                                    echo 'Errors editing category:';
                                    echo '<ul>';
                                    
                                    foreach ($errors as $key => $value)
                                        echo '<li>' . $value . '</li>';
                                    
                                    echo '</ul>';
                                }
                            ?>
                        </div>
                        
                        <div class="form-successes">
                            <?php
                                if (!empty($successes))
                                {
                                    echo 'Good News:';
                                    echo '<ul>';
                                    
                                    foreach ($successes as $key => $value)
                                        echo '<li>' . $value . '</li>';
                                    
                                    echo '</ul>';
                                }
                            ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
        
        <?php include 'fragments/footer-scripts.php'; ?>
    </body>
</html>