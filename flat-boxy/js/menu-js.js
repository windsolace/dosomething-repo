//javascript file
window.onload = function(){
	/*
	*Toggles whether the menu drawer is open or closed
	*/
	$('span#menu-icon').click(function(){
		if($('#navigation.open').length){
			$('#navigation').removeClass('open').toggleClass('closed');
			$('section#main').removeClass('fade').toggleClass('full');
		}
		else{
			$('#navigation').removeClass('closed').toggleClass('open');
			$('section#main').removeClass('full').toggleClass('fade');
		}
		
	});

	$('span.mobile-nav-icon').click(function(){
		if($('.mobile-nav-list-wrapper.open').length){
			$('.mobile-nav-list-wrapper').removeClass('open').toggleClass('closed');
			$('section#main').removeClass('fade').toggleClass('full');
		}
		else{
			$('.mobile-nav-list-wrapper').removeClass('closed').toggleClass('open');
			$('section#main').removeClass('full').toggleClass('fade');
		}
		
	});


};

function fb_login(){ 

    FB.login(function(response) {
        if (response.authResponse) {

            console.log('Welcome!  Fetching your information.... ');
            //console.log(response); // dump complete info
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID

            FB.api('/me', function(response) {
                user_name = response.name; //get user email
                console.log(response);
      			console.log('Successful login for: ' + user_name );
            });
            window.location = home_url;


        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');

        }
    }, {
        scope: 'public_profile'
    });
}

function fb_logout(){
	console.log("Running logout function");
	FB.logout(function(response){
		location.reload();
	});
}



$(document).ready(function(){
	
	/*
	*Sets when Log Out is clicked
	*/
	$('a').click(function(){		
		//Logout
		if($(this).find('.logout').length > 0){
			fb_logout();
		}
		else if($(this).find('.login').length > 0){
			//Do login only if the login page login button is clicked
			if($(this).children('.btn-block').length > 0){
				fb_login();
			}
			//Else redirect to login page
			else{
				window.location.replace(".../log-in")
			}
			
		}
	});

})





