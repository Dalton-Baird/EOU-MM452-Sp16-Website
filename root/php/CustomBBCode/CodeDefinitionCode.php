<?php
    class ProgrammingLanguage
    {
        private $names;
        private $highlighterName;
        private $fileExtensions;
        
        public function __construct($names, string $highlighterName, $fileExtensions)
        {
            $this -> names = $names;
            $this -> highlighterName = $highlighterName;
            $this -> fileExtensions = $fileExtensions;
        }
        
        public function getName()
        {
            return $this -> names[0];
        }
        
        public function getHighlighterName()
        {
            return $this -> highlighterName;
        }
        
        public function hasAlias($inputName)
        {
            //return in_array($inputName, $this -> names);
            foreach ($this -> names as $name)
                if (strtolower($name) == strtolower($inputName))
                    return true;
            return false;
        }
    }
    
    class CodeDefinitionCode extends JBBCode\CodeDefinition
    {
        private static $languages = array();
        private static $hasInitializedLanguages = false;
        
        public function __construct($usesOption)
        {
            parent::__construct();
            
            if (!self::$hasInitializedLanguages)
            {
                //Initialize languages
                //self::$languages[] = new ProgrammingLanguage(array(''), '', array(''));
                self::$languages[] = new ProgrammingLanguage(array('Apache Config', 'Apache'), 'apache', array('conf'));
                self::$languages[] = new ProgrammingLanguage(array('Bash'), 'bash', array('sh'));
                self::$languages[] = new ProgrammingLanguage(array(json_decode('"C\u266F"'), 'C#', 'C Sharp'), 'cs', array('cs'));
                self::$languages[] = new ProgrammingLanguage(array('C++', 'C Plus Plus', 'Cpp'), 'cpp', array('c', 'cpp', 'h', 'hpp'));
                self::$languages[] = new ProgrammingLanguage(array('Cascading Style Sheets', 'CSS'), 'css', array('css'));
                self::$languages[] = new ProgrammingLanguage(array('CoffeeScript'), 'coffeescript', array('coffee', 'litcoffee'));
                self::$languages[] = new ProgrammingLanguage(array('Diff'), 'diff', array('diff'));
                self::$languages[] = new ProgrammingLanguage(array('HTML', 'HyperText Markup Language'), 'html', array('html', 'htm'));
                self::$languages[] = new ProgrammingLanguage(array('XML', 'EXtensible Markup Language'), 'xml', array('xml'));
                self::$languages[] = new ProgrammingLanguage(array('HTTP', 'HyperText Transfer Protocol'), 'http', array());
                self::$languages[] = new ProgrammingLanguage(array('Ini', 'Microsoft Ini'), 'ini', array('ini'));
                self::$languages[] = new ProgrammingLanguage(array('JSON', 'JavaScript Object Notation'), 'json', array('json'));
                self::$languages[] = new ProgrammingLanguage(array('Java'), 'java', array('java'));
                self::$languages[] = new ProgrammingLanguage(array('JavaScript', 'js'), 'js', array('js'));
                self::$languages[] = new ProgrammingLanguage(array('Makefile'), 'makefile', array());
                self::$languages[] = new ProgrammingLanguage(array('Markdown', 'md'), 'markdown', array('md'));
                self::$languages[] = new ProgrammingLanguage(array('Nginx'), 'nginx', array());
                self::$languages[] = new ProgrammingLanguage(array('Objective-C', 'Objective C'), 'objectivec', array('h', 'm', 'mm', 'C'));
                self::$languages[] = new ProgrammingLanguage(array('PHP', 'PHP: Hypertext Preprocessor', 'Personal Home Page'), 'php', array('php'));
                self::$languages[] = new ProgrammingLanguage(array('Perl'), 'perl', array('pl', 'pm', 't', 'pod'));
                self::$languages[] = new ProgrammingLanguage(array('Python'), 'python', array('py'));
                self::$languages[] = new ProgrammingLanguage(array('Ruby'), 'ruby', array('rb', 'rbw'));
                self::$languages[] = new ProgrammingLanguage(array('SQL', 'Structured Query Language'), 'sql', array('sql'));
                
                self::$languages[] = new ProgrammingLanguage(array('ARM-Assembly', 'ARM Assembly', 'armasm'), 'armasm', array());
                self::$languages[] = new ProgrammingLanguage(array('ActionScript'), 'actionscript', array('as'));
                self::$languages[] = new ProgrammingLanguage(array('Ada'), 'ada', array('adb', 'ads'));
                self::$languages[] = new ProgrammingLanguage(array('AppleScript'), 'applescript', array('scpt', 'scptd', 'AppleScript'));
                self::$languages[] = new ProgrammingLanguage(array('AutoHotkey'), 'autohotkey', array());
                self::$languages[] = new ProgrammingLanguage(array('Basic'), 'basic', array());
                self::$languages[] = new ProgrammingLanguage(array('CMake'), 'cmake', array());
                self::$languages[] = new ProgrammingLanguage(array('D'), 'd', array('d'));
                self::$languages[] = new ProgrammingLanguage(array('DOS Batch File', 'DOS .bat', 'Batch File', 'Batch', 'DOS'), 'dos', array('bat'));
                self::$languages[] = new ProgrammingLanguage(array('Delphi'), 'delphi', array());
                self::$languages[] = new ProgrammingLanguage(array(json_decode('"F\u266F"'), 'F#', 'F Sharp'), 'fsharp', array('fs', 'fsi', 'fsx', 'fsscript'));
                self::$languages[] = new ProgrammingLanguage(array('Fortran'), 'fortran', array('f', 'for', 'f90', 'f95'));
                self::$languages[] = new ProgrammingLanguage(array('GLSL', 'OpenGL Shading Language', 'GLslang'), 'glsl', array('glsl'));
                self::$languages[] = new ProgrammingLanguage(array('Gradle'), 'gradle', array('gradle'));
                self::$languages[] = new ProgrammingLanguage(array('Groovy'), 'groovy', array('groovy'));
                self::$languages[] = new ProgrammingLanguage(array('Haskell'), 'haskell', array('hs', 'lhs'));
                self::$languages[] = new ProgrammingLanguage(array('Intel x86 Assembly', 'x86asm'), 'x86asm', array());
                self::$languages[] = new ProgrammingLanguage(array('Less'), 'less', array('less'));
                self::$languages[] = new ProgrammingLanguage(array('Lisp'), 'lisp', array());
                self::$languages[] = new ProgrammingLanguage(array('LiveCode', 'Live Code'), 'livecode', array('livecode'));
                self::$languages[] = new ProgrammingLanguage(array('Lua'), 'lua', array('lua'));
                self::$languages[] = new ProgrammingLanguage(array('Mathematica'), 'mathematica', array());
                self::$languages[] = new ProgrammingLanguage(array('Matlab'), 'matlab', array());
                self::$languages[] = new ProgrammingLanguage(array('PowerShell', 'Power Shell', 'Windows Power Shell'), 'powershell', array('ps1'));
                self::$languages[] = new ProgrammingLanguage(array('SQF'), 'sqf', array('sqf'));
                self::$languages[] = new ProgrammingLanguage(array('Scala'), 'scala', array('scala'));
                self::$languages[] = new ProgrammingLanguage(array('TypeScript'), 'typescript', array('ts'));
                self::$languages[] = new ProgrammingLanguage(array('Visual Basic', 'VB.NET', 'Visual Basic .NET', 'vb', 'vbnet'), 'vbnet', array('vb'));
                self::$languages[] = new ProgrammingLanguage(array('VBScript'), 'vbscript', array('vbs'));
                self::$languages[] = new ProgrammingLanguage(array('YAML', 'YAML Ain\'t Markup Language', 'Yet Another Markup Language'), 'yaml', array('yaml'));
                
                self::$hasInitializedLanguages = true;
            }
            
            $this -> setTagName("code");
            $this -> setParseContent(false);
            $this -> setUseOption($usesOption);
            
            $this -> setReplacementText(
                '<div class="code">' .
                    '<div class="code-header' . ($this -> usesOption() ? ' language-{highlighterName}' : '') . '">' . ($this -> usesOption() ? '{languageName}' : 'Code') . '</div>' .
                    '<div class="code-content{codeContentClass}">{param}</div>' .
                '</div>'
            );
        }
        
        private static function getLanguage($alias)
        {
            foreach (self::$languages as $language)
                if ($language -> hasAlias($alias))
                    return $language;
            return null;
        }
        
        public function asHtml(JBBCode\ElementNode $el)
        {            
            if (!$this -> hasValidInputs($el))
                return $el -> getAsBBCode();
            
            $html = $this -> getReplacementText();
            
            if ($this -> usesOption())
            {
                $options = $el -> getAttribute();
                
                if (count($options) == 1)
                {                    
                    $values = array_values($options);
                    $value = reset($values);
                    
                    //Find the programming language by its alias
                    $language = self::getLanguage($value);
                    
                    //If the language is null, make a new one based on the supplied name
                    if (is_null($language))
                        $language = new ProgrammingLanguage(array($value), '', array(''));
                    
                    //Replace the language {tags} with the language properties
                    $html = str_ireplace('{languageName}', $language -> getName(), $html);
                    $html = str_ireplace('{highlighterName}', $language -> getHighlighterName(), $html);
                    
                    $html = str_ireplace('{codeContentClass}', (empty($language -> getHighlighterName()) ? ' nohighlight' : ''), $html);
                }
                else
                {                                  
                    foreach ($options as $key => $value)
                    {
                        $html = str_ireplace("{$key}", $value, $html);
                    }
                }
            }
            else
            {
                $html = str_ireplace('{codeContentClass}', ' nohighlight', $html);
            }
                
            $content = $this -> getContent($el);
            
            $html = str_ireplace('{param}', $content, $html);
            
            return $html;
        }
    }
?>