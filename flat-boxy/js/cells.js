//javascript file
$(document).ready(function(){
    //$("body").css("display","none");
    //$("body").fadeIn(1000);
    $('#searchform').find('label.screen-reader-text').text('');
    /*
    *Effects when cell is clicked
    */
    $('div.homecell').click(function(){ 
        var clickedCell = $(this);
        var homecellArr = $('.homecell');
        for(var i = 0; i < homecellArr.length; i++){
            var currentLoopEle = homecellArr[i];
            if(currentLoopEle.className != clickedCell[0].className){
                //$(currentLoopEle).css('-webkit-animation', 'cellclick 1s');
                $(currentLoopEle).fadeTo('slow', 0);
            }
        }
        fadeScreen(500);    
    });

    $('.cell-content span, #breadcrumbs span').click(function(){
        //fadeScreen(0);
    });

    function fadeScreen(timing){
        if(timing >= 0){
            $("html").delay(timing).fadeOut();  
        }
    }
});

window.onload = function() {
    $('span#menu-icon').click(function() {
        if ($('#navigation.open').length) {
            $('#navigation').removeClass('open').toggleClass('closed');
            $('section#main').removeClass('fade').toggleClass('full');
        } 
        else {
            $('#navigation').removeClass('closed').toggleClass('open');
            $('section#main').removeClass('full').toggleClass('fade');
        }
    });
    $('span.mobile-nav-icon').click(function() {
        if ($('.mobile-nav-list-wrapper.open').length) {
            $('.mobile-nav-list-wrapper').removeClass('open').toggleClass('closed');
            $('section#main').removeClass('fade').toggleClass('full');
        } 
        else {
            $('.mobile-nav-list-wrapper').removeClass('closed').toggleClass('open');
            $('section#main').removeClass('full').toggleClass('fade');
        }
    });
};