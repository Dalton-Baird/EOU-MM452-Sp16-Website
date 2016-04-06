<?php
    /** Custom object for testing purposes */
    class SomeObject
    {
        public $data;
        
        /** Prints this object's data */
        public function printData()
        {
            echo $this -> data;
        }
        
        /** Allows implicit casting to a string */
        public function __toString()
        {
            return "SomeObject(data: {$this -> data})";
        }
    }
    
    /** Prints a data type with the specifed name */
    function printTypeWithName($name, $var)
    {
        echo 'gettype(' . $name . '): ' . gettype($var) . '<br>';
    }

    /** Prints a data type */
    function printType($var)
    {
        printTypeWithName($var, $var);
    }

    /** Declares and prints various data types */
	function printTypes()
    {
        $myInt = 42;
        $myString = 'Hello World!';
        $myFloat = 123.456;
        $myBool = true;
        $myArray = array(1, 2, 3, 4, 5, 6);
        $myNullObj = null;
        $myObj = new SomeObject();
        $myObj -> data = 'Custom Data!';
        
        printType($myInt);
        printType($myString);
        printType($myFloat);
        printType($myBool);
        printTypeWithName('myArray', $myArray);
        printType($myNullObj);
        printType($myObj);
        
        echo '<br>';
        
        $myObj -> printData();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>MM 452 Assignment 2</title>
    </head>
    <body>
        <?php printTypes(); ?>
    </body>
</html>