<!DOCTYPE html>
<html>
    <head>
        <?php include 'fragments/header.php'; ?>
        <title>Eastern Oregon University</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row nav-button-row2">
                <!-- Navigation Buttons (They used to be in their own row below, but that was blocking the page) -->
                <button class="nav-button nav-button-left  color-white button-with-icon background-red spacing-width-1 spacing-height-3" id="nav-left" style="background-image: url('icons/arrowLeft.svg')" /> <!--data-toggle="tooltip" data-placement="right" title="Hold to scroll left"-->
                <button class="nav-button nav-button-right color-white button-with-icon background-red spacing-width-1 spacing-height-3 pull-right" id="nav-right" style="background-image: url('icons/arrowRight.svg')" /> <!--data-toggle="tooltip" data-placement="left" title="Hold to scroll right"-->
            </div>

            <?php include 'fragments/menu.php'; ?>
            
            <div class="row title-row full-width">
                <div class="col-xs-12">
                    <span class="background-logo">Eastern Oregon University</span>
                </div>
            </div>

            <div class="row social-button-row-1">
                <div class="col-xs-3 social-button-row-offset">
                    <span class="tahoma-bold all-caps">Find us on:</span>
                </div>
            </div>

            <div class="row social-button-row-2">
                <div class="col-xs-3 social-button-row-offset">
                    <button class="main-button color-white button-with-icon background-blue spacing-width-1 spacing-height-1" onclick="window.location.href = 'http://www.facebook.com/eouadmissions';" style="background-image: url('icons/facebook.svg')" data-toggle="tooltip" data-placement="bottom" title="Facebook"/>
                    <button class="main-button color-white button-with-icon background-light-blue spacing-width-1 spacing-height-1 spacing-margin-left-1" onclick="window.location.href = 'https://twitter.com/eoumountaineers';" style="background-image: url('icons/twitter.svg')" data-toggle="tooltip" data-placement="bottom" title="Twitter"/>
                    <button class="main-button color-white button-with-icon background-brown spacing-width-1 spacing-height-1 spacing-margin-left-1" onclick="window.location.href = 'http://instagram.com/monty.mountaineer';" style="background-image: url('icons/instagram.svg')" data-toggle="tooltip" data-placement="bottom" title="Instagram"/>
                    <button class="main-button color-white button-with-icon background-orange spacing-width-1 spacing-height-1 spacing-margin-left-1" onclick="window.location.href = 'https://www.eou.edu/feed/';" style="background-image: url('icons/rss.svg')" data-toggle="tooltip" data-placement="bottom" title="RSS Feed"/>
                    <button class="main-button color-white button-with-icon background-red spacing-width-1 spacing-height-1 spacing-margin-left-1" onclick="window.location.href = 'http://www.youtube.com/eouniversity';" style="background-image: url('icons/youtube.svg')" data-toggle="tooltip" data-placement="bottom" title="YouTube"/>
                </div>
            </div>

            <div class="row copyright-row">
                <div class="col-xs-3 col-xs-offset-3">
                    <span class="copyright">Copyright &copy; 2016 Eastern Oregon University</span>
                </div>
            </div>

            <div class="row boxes-row">
                <div class="col-custom-vw-8">
                    <div class="box box-double">
                        <div class="title">Welcome to EOU</div>
                        <img src="images/Box1.jpg" class="box-img box-image-double spacing-margin-top-half">
                        <div class="image-caption background-blue">Small class sizes allow for a closer connection to the instructor, enhancing the learning experience.</div>
                        <button class="main-button color-white background-gold spacing-width-4 spacing-height-1 spacing-margin-top-half spacing-margin-left-1 pull-right">Learn More</button>
                        <button class="main-button color-white background-green spacing-width-4 spacing-height-1 spacing-margin-top-half pull-right">Apply Now</button>
                    </div>
                </div>

                <div class="col-custom-vw-4">
                    <div class="box">
                        <div class="title">Programs</div>
                        <img src="images/Box2.jpg" class="box-img spacing-margin-top-half">
                        <div class="image-caption background-orange">Discover the various academic programs that EOU offers.</div>
                        <button class="main-button color-white background-gold spacing-width-4 spacing-height-1 spacing-margin-top-half spacing-margin-left-1 pull-right">Learn More</button>
                    </div>
                </div>

                <div class="col-custom-vw-4">
                    <div class="box">
                        <div class="title">Community</div>
                        <img src="images/Box3.jpg" class="box-img spacing-margin-top-half">
                        <div class="image-caption background-gold">With close proximity to downtown La Grande, students can easily access many of the local shops and attractions.</div>
                        <button class="main-button color-white background-gold spacing-width-4 spacing-height-1 spacing-margin-top-half spacing-margin-left-1 pull-right">Learn More</button>
                    </div>
                </div>

                <div class="col-custom-vw-8">
                    <div class="box box-double">
                        <div class="title">Sports</div>
                        <img src="images/Box4.jpg" class="box-img box-image-double spacing-margin-top-half">
                        <div class="image-caption background-red">EOU football player about to score winning touchdown.</div>
                        <button class="main-button color-white background-gold spacing-width-4 spacing-height-1 spacing-margin-top-half spacing-margin-left-1 pull-right">Learn More</button>
                    </div>
                </div>

                <div class="col-custom-vw-4">
                    <div class="box">
                        <div class="title">Events &amp; Activities</div>
                        <img src="images/Box5.jpg" class="box-img spacing-margin-top-half">
                        <div class="image-caption background-orange">Art students create three-dimensional sculptures out of clay.</div>
                        <button class="main-button color-white background-gold spacing-width-4 spacing-height-1 spacing-margin-top-half spacing-margin-left-1 pull-right">Learn More</button>
                    </div>
                </div>

                <div class="col-custom-vw-12">
                    <div class="box">
                        <div class="row">
                            <div class="col-xs-3 col-xs-offset-2">
                                <div class="title">Where to next?</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-custom-vw-12">
                                <div class="box-footer background-blue">
                                    <div class="row"> <!-- Empty row for spacing -->
                                        &nbsp;
                                    </div>
                                    <div class="row"> <!-- ROW 1 -->
                                        <div class="col-xs-2"> <!-- Column offsets aren't working for some reason, so I'm using empty columns -->

                                        </div>
                                        <div class="col-xs-2">
                                            <div class="title">Helpful Links</div>
                                        </div>
                                        <div class="col-xs-4">

                                        </div>
                                        <div class="col-xs-2">
                                            <div class="title">Visiting EOU</div>
                                        </div>
                                    </div>
                                    <div class="row spacing-margin-top-half"> <!-- ROW 2 -->
                                        <div class="col-xs-2">

                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-gold spacing-width-6 spacing-height-1">Contact Us</button>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-orange spacing-width-6 spacing-height-1">Other Option</button>
                                        </div>
                                        <div class="col-xs-2">

                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-green spacing-width-6 spacing-height-1">Campus Tours</button>
                                        </div>
                                    </div>
                                    <div class="row spacing-margin-top-half"> <!-- ROW 3 -->
                                        <div class="col-xs-2">

                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-gold spacing-width-6 spacing-height-1">Emergency Info</button>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-orange spacing-width-6 spacing-height-1">Other Option</button>
                                        </div>
                                        <div class="col-xs-2">

                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-green spacing-width-6 spacing-height-1">Preview Day</button>
                                        </div>
                                    </div>
                                    <div class="row spacing-margin-top-half"> <!-- ROW 4 -->
                                        <div class="col-xs-2">

                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-gold spacing-width-6 spacing-height-1">Bookstore</button>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-orange spacing-width-6 spacing-height-1">Other Option</button>
                                        </div>
                                        <div class="col-xs-2">

                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-green spacing-width-6 spacing-height-1">Map &amp; Directions</button>
                                        </div>
                                    </div>
                                    <div class="row spacing-margin-top-half"> <!-- ROW 5 -->
                                        <div class="col-xs-2">

                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-gold spacing-width-6 spacing-height-1">Pierce Library</button>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-orange spacing-width-6 spacing-height-1">Other Option</button>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="title spacing-margin-top-half" style="letter-spacing: 0;">Get the EOU app now</div>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-green spacing-width-6 spacing-height-1">Galleries</button>
                                        </div>
                                    </div>
                                    <div class="row spacing-margin-top-half"> <!-- ROW 6 -->
                                        <div class="col-xs-2">

                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-gold spacing-width-6 spacing-height-1">Career Center</button>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-orange spacing-width-6 spacing-height-1">Other Option</button>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="app-button spacing-width-3 spacing-height-1" style="background-image: url('images/apple_app_store.png')" onclick="window.location.href = 'https://itunes.apple.com/us/app/goeou!/id1005618945?mt=8';"/>
                                            <button class="app-button spacing-width-3 spacing-height-1 spacing-margin-left-half" style="background-image: url('images/google_play.png')" onclick="window.location.href = 'https://play.google.com/store/apps/details?id=hr.apps.n206877150';"/>
                                        </div>
                                        <div class="col-xs-2">
                                            <button class="main-button color-white background-green spacing-width-6 spacing-height-1">Event Calendar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- END container div -->

        <?php include 'fragments/footer-scripts.php'; ?>

        <script> //Scrolling Script - By Dalton Baird
            var SCROLL_AMOUNT = 0.1;        //Amount of spaces to scroll
            var SCROLL_OFFSET = 0;          //The current scroll offset
            var SCROLL_MIN = -113.2;          //The minimum scroll offset
            var SCROLL_MAX = 0;             //The maximum scroll offset
            var SCROLL_FORCE = 0;           //The amount to scroll each frame
            var SCROLL_FORCE_MIN = -5;      //The minimum amount of scroll force
            var SCROLL_FORCE_MAX = 5;       //The maximum amount of scroll force
            var SCROLL_FRICTION = 0.825;    //The number to multiply the scroll force by each frame
            var SCROLL_FORCE_STOP = 0.01;   //When the absolute scroll force gets this small, it will be set to 0
            var LEFT_BUTTON_DOWN = false;   //If true, the left button is pressed
            var RIGHT_BUTTON_DOWN = false;  //If true, the right button is pressed
            var BUTTON_FORCE = 0.2;         //The amount of force to apply while a button is pressed

            /**
             * Handles scrolling
             * Params:
             *      delta: The change in mouse wheel, generally positive or negative
             *      row: The thing to scroll
             *      generateLeftCSSWithOffset: A function to call to generate the CSS for the left rule.
             *          The function takes the offset, which is the number of "spaces" to offset.  There
             *          are 47 spaces on the screen horizontally.  See site.css for details.
             */
            var scrollHandler = function (delta, row, generateLeftCSSWithOffset) {

                SCROLL_OFFSET += delta * SCROLL_AMOUNT; //Calculate the new scroll offset

                SCROLL_OFFSET = Math.max(SCROLL_MIN, Math.min(SCROLL_MAX, SCROLL_OFFSET)); //Clamp the offset
                var calcExpr = generateLeftCSSWithOffset(SCROLL_OFFSET); //Generate the CSS value (which is a calc() expression)
                row.css("left", calcExpr); //Set the CSS value
            }

            //Handle scrolling
            $(document).bind("wheel", function (e) {
                //console.log("\nScrolled.  Delta: " + e.originalEvent.wheelDelta);
                SCROLL_FORCE += e.originalEvent.wheelDelta;
            });

            //Handle left button
            $("#nav-left").mousedown(function () {
                LEFT_BUTTON_DOWN = true;
            });
            $("#nav-left").mouseup(function () {
                LEFT_BUTTON_DOWN = false;
            });
            $("#nav-left").mouseleave(function () {
                LEFT_BUTTON_DOWN = false;
            });

            //Handle right button
            $("#nav-right").mousedown(function () {
                RIGHT_BUTTON_DOWN = true;
            });
            $("#nav-right").mouseup(function () {
                RIGHT_BUTTON_DOWN = false;
            });
            $("#nav-right").mouseleave(function () {
                RIGHT_BUTTON_DOWN = false;
            });

            //Smooth scrolling
            window.setInterval(function () {
                //Handle buttons
                if (LEFT_BUTTON_DOWN)
                    SCROLL_FORCE += BUTTON_FORCE;
                if (RIGHT_BUTTON_DOWN)
                    SCROLL_FORCE -= BUTTON_FORCE;

                SCROLL_FORCE = Math.max(SCROLL_FORCE_MIN, Math.min(SCROLL_FORCE_MAX, SCROLL_FORCE)); //Clamp the scroll force

                //Scroll
                scrollHandler(SCROLL_FORCE, $(".boxes-row"), function (offset) { return "calc(100vw * (4 + " + offset + ")/47)" });
                scrollHandler(SCROLL_FORCE, $(".title-row"), function (offset) { return "calc(-1.4vw + (100vw * " + offset + "/47))" });

                SCROLL_FORCE *= SCROLL_FRICTION; //Apply friction
                if (Math.abs(SCROLL_FORCE) < SCROLL_FORCE_STOP) //Set the scroll force to 0 if it is small enough
                    SCROLL_FORCE = 0;
            }, 10);
        </script>
    </body>
</html>