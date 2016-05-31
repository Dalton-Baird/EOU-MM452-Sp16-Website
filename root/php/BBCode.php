<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/JBBCode/Parser.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/JBBCode/DefaultCodeDefinitionSet.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/JBBCode/CodeDefinitionBuilder.php';
    //require_once $_SERVER['DOCUMENT_ROOT'] . '/php/CustomBBCode/CodeDefinitionQuote.php';
    //require_once $_SERVER['DOCUMENT_ROOT'] . '/php/CustomBBCode/CodeDefinitionCode.php';
    //require_once $_SERVER['DOCUMENT_ROOT'] . '/php/CustomBBCode/visitors/EmoticonVisitor.php';
    
    use JBBCode\Parser;
    use JBBCode\DefaultCodeDefinitionSet;
    use JBBCode\CodeDefinitionBuilder;
    
    class BBCode
    {
        /** The BB Code Parser */
        public static $parser;
        
        /** Keeps track of whether the static variables have been initialized */
        private static $initialized = false;
        
        public static function init()
        {
            if (self::$initialized) //Don't initialize again
                return;
            
            //Set up JBBCode Parser
            self::$parser = new Parser();
            self::$parser -> addCodeDefinitionSet(new DefaultCodeDefinitionSet());
            //self::$parser -> addCodeDefinition(new CodeDefinitionCode(false));
            //self::$parser -> addCodeDefinition(new CodeDefinitionCode(true));
            //self::$parser -> addCodeDefinition(new CodeDefinitionQuote());
            
            //[s] strikethrough tag
            $builder = new CodeDefinitionBuilder('s', '<strike>{param}</strike>');
            self::$parser -> addCodeDefinition($builder -> build());
            
            //[size] text size tag
            $builder = new CodeDefinitionBuilder('size', '<span style="font-size: {option}pt">{param}</span>');
            $builder -> setUseOption(true);
            
            $builder -> setOptionValidator(new class implements JBBCode\InputValidator
            {
                public function validate($input)
                {
                    return is_numeric($input) and $input >= 6 and $input <= 50;
                }
            });
            
            self::$parser -> addCodeDefinition($builder -> build());
            
            //[center] center tag
            $builder = new CodeDefinitionBuilder('center', '<span style="text-align: center; display: block;">{param}</span>');
            self::$parser -> addCodeDefinition($builder -> build());
            
            //[noparse] no bbcode tag
            $builder = new CodeDefinitionBuilder('noparse', '{param}');
            $builder -> setParseContent(false);
            self::$parser -> addCodeDefinition($builder -> build());
            
            //[youtube] tag
            $builder = new CodeDefinitionBuilder('youtube', '<iframe type="text/html" width="640" height="390" src="https://www.youtube.com/embed/{param}" frameborder="0"></iframe>');
            $builder -> setParseContent(false);
            
            $builder -> setBodyValidator(new class implements JBBCode\InputValidator //PHP 7 Anonymous class
            {
                public function validate($input)
                {
                    //Regex for youtube video ID (mostly)
                    return preg_match('/^([^\?&"\'[\] >]+)$/', $input) == true;
                }
            });
            
            self::$parser -> addCodeDefinition($builder -> build());
            
            //[spoiler] tag
            $builder = new CodeDefinitionBuilder('spoiler',
            '<div class="spoiler">' .
                '<div class="spoiler-header" onclick="$(this).next().toggle(300);" title="Click to show / hide contents">Spoiler</div>' .
                '<div class="spoiler-content" style="display: none;">{param}</div>' .
            '</div>'
            );
            
            $builder -> setParseContent(true);
            self::$parser -> addCodeDefinition($builder -> build());
            
            self::loadVisitors();
            
            self::$initialized = true;
        }
        
        private static function loadVisitors()
        {
            //self::$parser -> accept(new EmoticonVisitor());
        }
    }
?>