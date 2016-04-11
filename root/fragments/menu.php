<div class="row spacing-margin-top-1 spacing-margin-left-half spacing-height-3" id="menu-buttons-row">
    <div class="col-xs-4">
        <button class="menu-button color-white spacing-width-1 spacing-height-1"                       data-toggle="tooltip" data-placement="bottom" title="Search EOU" style="background-image: url('icons/search.svg')" onclick="$('.menu-hideable').not('#search-row').hide(); $('#search-row').toggle(); $('#searchbox').focus();"/>
        <button class="menu-button color-white spacing-width-1 spacing-height-1 spacing-margin-left-1" data-toggle="tooltip" data-placement="bottom" title="Students" style="background-image: url('icons/backpack.svg')" onclick="$('.menu-hideable').not('#menu-students').hide(); $('#menu-students').toggle();"/>
        <button class="menu-button color-white spacing-width-1 spacing-height-1 spacing-margin-left-1" data-toggle="tooltip" data-placement="bottom" title="Faculty" style="background-image: url('icons/instructor.svg')" onclick="$('.menu-hideable').not('#menu-faculty').hide(); $('#menu-faculty').toggle();"/>
        <button class="menu-button color-white spacing-width-1 spacing-height-1 spacing-margin-left-1" data-toggle="tooltip" data-placement="bottom" title="Academics" style="background-image: url('icons/cap.svg')" onclick="$('.menu-hideable').not('#menu-academics').hide(); $('#menu-academics').toggle();"/>
        <button class="menu-button color-white spacing-width-1 spacing-height-1 spacing-margin-left-1" data-toggle="tooltip" data-placement="bottom" title="Information" style="background-image: url('icons/info.svg')" onclick="$('.menu-hideable').not('#menu-info').hide(); $('#menu-info').toggle();"/>
        <button class="menu-button color-white spacing-width-1 spacing-height-1 spacing-margin-left-1" data-toggle="tooltip" data-placement="bottom" title="EOU Forum" style="background-image: url('icons/chat.svg')" onclick="window.location.href = 'TODO';"/>
        <button class="menu-button color-white spacing-width-1 spacing-height-1 spacing-margin-left-1" data-toggle="tooltip" data-placement="bottom" title="Other" style="background-image: url('icons/other.svg')" onclick="$('.menu-hideable').not('#menu-other').hide(); $('#menu-other').toggle();"/>
    </div>
</div>

<div class="row spacing-margin-left-half menu-hideable" id="search-row" style="display: none;">
    <div class="col-xs-4">
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
    <div class="col-xs-3 menu-hideable" id="menu-students" style="display: none">
        <div class="popdown-arrow-menu" style="left: calc(100vw * 3/47)"></div>
        <div class="menu" style="left: calc(100vw * 3/47)">
            <a class="menu-link" href="https://eou.instructure.com/">Canvas</a>
            <a class="menu-link" href="http://gmail.eou.edu/">Email</a>
            <a class="menu-link" href="https://banweb.ous.edu/eouprd/owa/twbkwbis.P_WWWLogin">Webster</a>
            <a class="menu-link" href="https://www.eou.edu/academics/">Academic Programs</a>
            <a class="menu-link" href="https://www.eou.edu/sse/">Student Services</a>
            <a class="menu-link" href="https://www.eou.edu/lcenter/">Learning Center</a>
            <a class="menu-link" href="https://www.eou.edu/advising/">Advising</a>
            <a class="menu-link" href="https://www.eou.edu/students/">More</a>
        </div>
    </div>
    <div class="col-xs-3 menu-hideable" id="menu-faculty" style="display: none">
        <div class="popdown-arrow-menu" style="left: calc(100vw * 5/47)"></div>
        <div class="menu" style="left: calc(100vw * 5/47)">
            <a class="menu-link" href="https://www.eou.edu/cas/directory/">Staff Directory</a>
            <a class="menu-link" href="https://www.eou.edu/cas/deansoffice/">Dean's Office</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="https://www.eou.edu/faculty/">More</a>
        </div>
    </div>
    <div class="col-xs-3 menu-hideable" id="menu-academics" style="display: none">
        <div class="popdown-arrow-menu" style="left: calc(100vw * 7/47)"></div>
        <div class="menu" style="left: calc(100vw * 7/47)">
            <a class="menu-link" href="https://banweb.ous.edu/eouprd/owa/bwckschd.p_disp_dyn_sched">Course Schedule</a>
            <a class="menu-link" href="https://www.eou.edu/academics/on-campus-majors-and-minors/">On Campus Programs</a>
            <a class="menu-link" href="https://www.eou.edu/academics/online-majors-and-minors/">Online Programs</a>
            <a class="menu-link" href="https://www.eou.edu/academics/graduate/">Graduate Programs</a>
            <a class="menu-link" href="https://drive.google.com/a/eou.edu/file/d/0B-844eoNzbWAOEt2amF5aFhudUU/view?usp=sharing">Academic Catalog</a>
            <a class="menu-link" href="https://www.eou.edu/advising/2016-17-program-checksheets/">Program Check Sheets</a>
            <a class="menu-link" href="https://www.eou.edu/academics/">More</a>
            <a class="menu-link" href="#"></a>
        </div>
    </div>
    <div class="col-xs-3 menu-hideable" id="menu-info" style="display: none">
        <div class="popdown-arrow-menu" style="left: calc(100vw * 9/47)"></div>
        <div class="menu" style="left: calc(100vw * 9/47)">
            <a class="menu-link" href="https://www.eou.edu/registrar/files/2012/05/Final-Exam-Schedule.pdf">Final Exam Schedule</a>
            <a class="menu-link" href="https://www.eou.edu/news/">News</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
        </div>
    </div>
    <div class="col-xs-3 menu-hideable" id="menu-other" style="display: none">
        <div class="popdown-arrow-menu" style="left: calc(100vw * 13/47)"></div>
        <div class="menu" style="left: calc(100vw * 13/47)">
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
            <a class="menu-link" href="#">Link</a>
        </div>
    </div>
</div>