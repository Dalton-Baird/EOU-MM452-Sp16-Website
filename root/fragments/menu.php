<?php
    /** Represents a menu item */
    class MenuItem
    {
        /** The title of the menu item */
        public $title;
        /** The icon name, will be set to the icon with the path /icons/$iconStr.svg */
        public $iconStr;
        /** The ID of the menu box.  Should probably be 'menu-something' */
        public $menuID;
        /** The width of the menu box, in columns */
        public $menuBoxWidth;
        /** An associative array of menu links.  The key is the name of the link, and the value is the link URL. */
        public $menuLinks;
        
        /** Constructs a MenuItem */
        function __construct(string $title, string $iconStr, string $menuID, int $menuBoxWidth, array $menuLinks)
        {
            $this -> title = $title;
            $this -> iconStr = $iconStr;
            $this -> menuID = $menuID;
            $this -> menuBoxWidth = $menuBoxWidth;
            $this -> menuLinks = $menuLinks;
        }
    }
    
    /** Represents a search bar menu item.  Rendering of this is currently hardcoded, so there are no properties to set. */
    class SearchBarMenuItem extends MenuItem
    {
        /** Constructs a SearchBarMenuItem */
        function __construct(string $title, string $iconStr, string $menuID, int $menuBoxWidth)
        {
            parent::__construct($title, $iconStr, $menuID, $menuBoxWidth, array());
        }
    }
    
    /** Represents a link menu item. */
    class LinkMenuItem extends MenuItem
    {
        /** The URL To go to when this menu item is clicked */
        public $linkURL;
        
        /** Constructs a LinkMenuItem */
        function __construct(string $title, string $iconStr, string $linkURL)
        {
            parent::__construct($title, $iconStr, '', 0, array());
            $this -> linkURL = $linkURL;
        }
    }
    
    //Initialize Menu Items
    $menuItems = array();
    
    $menuItems[] = new SearchBarMenuItem('Search EOU', 'search', 'search-row', 4);
    $menuItems[] = new LinkMenuItem('Home', 'home', '/');
    $menuItems[] = new LinkMenuItem('EOU Forum', 'chat', '/categories.php');
    $menuItems[] = new MenuItem('Students', 'backpack', 'menu-students', 3, array('Canvas'=>'https://eou.instructure.com/', 'Email'=>'http://gmail.eou.edu/', 'Webster'=>'https://banweb.ous.edu/eouprd/owa/twbkwbis.P_WWWLogin', 'Academic Programs'=>'https://www.eou.edu/academics/', 'Student Services'=>'https://www.eou.edu/sse/', 'Learning Center'=>'https://www.eou.edu/lcenter/', 'Advising'=>'https://www.eou.edu/advising/', 'More'=>'https://www.eou.edu/students/'));
    $menuItems[] = new MenuItem('Faculty', 'instructor', 'menu-faculty', 3, array('Staff Directory'=>'https://www.eou.edu/cas/directory/', 'Dean\'s Office'=>'https://www.eou.edu/cas/deansoffice/', 'More'=>'https://www.eou.edu/faculty/'));
    $menuItems[] = new MenuItem('Academics', 'cap', 'menu-acaemics', 3, array('Course Schedule'=>'https://banweb.ous.edu/eouprd/owa/bwckschd.p_disp_dyn_sched', 'On Campus Programs'=>'https://www.eou.edu/academics/on-campus-majors-and-minors/', 'Online Programs'=>'https://www.eou.edu/academics/online-majors-and-minors/', 'Graduate Programs'=>'https://www.eou.edu/academics/graduate/', 'Academic Catalog'=>'https://drive.google.com/a/eou.edu/file/d/0B-844eoNzbWAOEt2amF5aFhudUU/view?usp=sharing', 'Program Check Sheets'=>'https://www.eou.edu/advising/2016-17-program-checksheets/', 'More'=>'https://www.eou.edu/academics/'));
    $menuItems[] = new MenuItem('Information', 'info', 'menu-info', 3, array('Final Exam Schedule'=>'https://www.eou.edu/registrar/files/2012/05/Final-Exam-Schedule.pdf', 'News'=>'https://www.eou.edu/news/'));
    $menuItems[] = new MenuItem('Other', 'other', 'menu-other', 3, array('DEBUG: View PHP Session Variables'=>'/debugViewSession.php'));
?>

<div class="row spacing-margin-top-1 spacing-margin-left-half spacing-height-3" id="menu-buttons-row">
    <div class="col-xs-5 <?php if (basename($_SERVER['PHP_SELF']) != 'index.php') { echo 'menu-buttons-row-background'; } ?>">
        <?php
            
            foreach ($menuItems as $index => $menuItem) //Generate each menu item's button
            {
                echo '<button class="menu-button color-white spacing-width-1 spacing-height-1' . ($index == 0 ? '' : ' spacing-margin-left-1') . '" data-toggle="tooltip" data-placement="bottom" title="' . $menuItem -> title . '" style="background-image: url(\'icons/' . $menuItem -> iconStr . '.svg\')" onclick="';
                
                if ($menuItem instanceof SearchBarMenuItem) //Search Bar Menu Item
                {
                    echo '$(\'.menu-hideable\').not(\'#' . $menuItem -> menuID . '\').hide(); $(\'#' . $menuItem -> menuID . '\').toggle(); $(\'#searchbox\').focus();';
                }
                else if ($menuItem instanceof LinkMenuItem) //Link Menu Item
                {
                    echo 'window.location.href = \'' . $menuItem -> linkURL . '\';';
                }
                else if ($menuItem instanceof MenuItem) //Plain Old Menu Item (must be last)
                {
                    echo '$(\'.menu-hideable\').not(\'#' . $menuItem -> menuID . '\').hide(); $(\'#' . $menuItem -> menuID . '\').toggle();';
                }
                
                echo '"/>';
            }
            
        ?>
    </div>
</div>

<?php //Generate search bar (hardcoded) ?>
<div class="row spacing-margin-left-half menu-hideable" id="<?php echo $menuItems[0] -> menuID; ?>" style="display: none;">
    <div class="col-xs-<?php echo $menuItems[0] -> menuBoxWidth; ?>">
        <div class="popdown-arrow" style="left: calc(100vw * 1/47)"></div>
        <div class="searchbar">
            <form action="https://www.google.com/cse" method="GET">
                <input id="searchbox" class="searchbar-field" name="q" type="search" placeholder="Search EOU">
                <input type="hidden" name="cx" value="015310282007089969669:18cnoplcmm4">
                <input class="searchbar-submit" type="submit" style="background-image: url('icons/arrowSearch.svg')" value="" data-toggle="tooltip" data-placement="right" title="Search" >
            </form>
        </div>
    </div>
</div>

<div class="row" id="menu-row"> <!-- Menu Row -->
    <?php
        foreach ($menuItems as $index => $menuItem) //Generate each menu item's menu
        {
            //Skip MenuItems that are not plain old menu items.  THIS MUST BE UPDATED IF NEW TYPES ARE ADDED.
            if ($menuItem instanceof SearchBarMenuItem or $menuItem instanceof LinkMenuItem)
                continue;
            
            echo '<div class="col-xs-' . $menuItem -> menuBoxWidth . ' menu-hideable" id="' . $menuItem -> menuID . '" style="display: none">';
            
            $menuOffset = ($index * 2) + 1; //$index * 2 for the menu item width plus it's offset, + 1 for the initial offset
            
            echo '<div class="popdown-arrow-menu" style="left: calc(100vw * ' . $menuOffset . '/47)"></div>';
            
            echo '<div class="menu" style="left: calc(100vw * ' . $menuOffset . '/47)">';
            
            foreach ($menuItem -> menuLinks as $name => $url) //Generate the links
            {
                echo '<a class="menu-link" href="' . $url . '">' . $name . '</a>';
            }
            
            echo '</div>';
            
            echo '</div>';
        }
    ?>
</div>
