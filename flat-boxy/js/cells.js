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
function fb_login() {
    FB.login(function(response) {
        if (response.authResponse) {
            console.log('Welcome!  Fetching your information.... ');
            access_token = response.authResponse.accessToken;
            user_id = response.authResponse.userID;
            FB.api('/me', function(response) {
                user_name = response.name;
                console.log(response);
                console.log('Successful login for: ' + user_name);
            });
            window.location = home_url;
        } else {
            console.log('User cancelled login or did not fully authorize.');
        }
    }, {scope: 'public_profile'});
}
function fb_logout() {
    console.log("Running logout function");
    FB.logout(function(response) {
        location.reload();
    });
}
$(document).ready(function() {
    $('a').click(function() {
        if ($(this).find('.logout').length > 0) {
            fb_logout();
        } 
        else if ($(this).find('.login').length > 0) {
            if ($(this).children('.btn-block').length > 0) {
                fb_login();
            } 
            else {
                window.location.replace(".../log-in")
            }
        }
    });
});
$(document).ready(function() {
    $('#searchform').find('label.screen-reader-text').text('');
    $('div.homecell').click(function() {
        var clickedCell = $(this);
        var homecellArr = $('.homecell');
        for (var i = 0; i < homecellArr.length; i++) {
            var currentLoopEle = homecellArr[i];
            if (currentLoopEle.className != clickedCell[0].className) {
                $(currentLoopEle).fadeTo('slow', 0);
            }
        }
        fadeScreen(500);
    });
    $('.cell-content span, #breadcrumbs span').click(function() {
    });
    function fadeScreen(timing) {
        if (timing >= 0) {
            $("html").delay(timing).fadeOut();
        }
    }
})
