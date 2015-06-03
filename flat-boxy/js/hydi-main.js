/*
* hydi-main.js
*/
$(document).ready(function(){
	var CONST_MOBILE_WIDTH = 760;

	//initialize
	hydiInit();
	//$.colorbox({href:"wp-content/themes/flat-boxy/img/profilepic1.jpg"});

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
				hydiLogin();
			}
			//Else redirect to login page
			else{
				window.location.replace(".../log-in")
			}
			
		}
	});

	//show all accordion contents that were hidden if width changes to > CONST_MOBILE_WIDTH
	$(window).resize(function(){
		if($(document).width() < CONST_MOBILE_WIDTH){
			$('.columns .content').show();
		}
	});

});

//init
var hydiInit = function(){
	//Moved to individual templates
	//activityDetailMobileFn();
	//activityDetailFn();
	//userProfileFn();

};

/**************************Authentication functions**************************/

/**
* Login function
*/
function hydiLogin(){
	//Check whether is session cookie
	var browserSession = getCookie('HYDIAUTHKEY');
	var uid = getCookie('uid');
	//var uid = sessionStorage.getItem('fbuid');

	//If no session, prompt user to login
	if(!uid || !browserSession){
		console.log("New login session");
		fb_login();
		//renewSession(uid);
	} else{
		//Verify against backend
		getLoginStatus(uid, function(status){
			if(status){
				console.log("You are already logged in! Renewing your session.");
				renewSession(uid);
			} else {
				console.log("You are not logged in yet. Please log in.");
				fb_login();
			}
		});
	}
}

/**
* Creates or renews sessionID
* @params uid
* @return sessionID
*/
function renewSession(uid){
	var auth = getCookie('HYDIAUTHKEY');
	$.ajax({
		url: ajaxurl,
		type: 'POST', 
		dataType:'json',        
		data: {
			requestPath: HYDI_API.SITE_LOGIN,
			params: {
				userid:uid,
				auth: auth
			},
			action: 'callHydiApi'
		},
		success:
			function(response){
				console.log("Session renewed");
				console.log(response);
			},
		error:
			function(e){
				console.log("[Error]: Failed to renew session");
				console.log(e);
			}
	});
}

/**
* Get user's login status
* @params uid
* @params callback
* @return uid, isLoggedIn
*/
function getLoginStatus(uid, callback){
	//var uid = sessionStorage.getItem('fbuid');
	var auth = getCookie('HYDIAUTHKEY');
	$.ajax({
		url: ajaxurl,
		type: 'GET', 
		dataType:'json',        
		data: {
			requestPath: HYDI_API.SITE_LOGIN,
			params: {
				userid:uid,
				auth: auth
			},
			action: 'callHydiApi'
		},
		success:
			function(response){
				console.log("Successful check login status: isLogin: " + response.isLoggedIn);
				isLogin = response.isLoggedIn;
				callback(isLogin);
				//return isLogin;
			},
		error:
			function(e){
				console.log("Failed to check login status");
				console.log(e);
			}
	});
}


function fb_login(){ 

    FB.login(function(response) {
        if (response.authResponse) {

            console.log('Welcome!  Fetching your information.... ');
            //console.log(response); // dump complete info
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID
            renewSession(user_id);
            document.cookie="uid="+user_id+';path=/;';

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
	FB.getLoginStatus(function(response) {console.log(response);
		//if logged in
		if (response.status === 'connected') {
			console.log("Running logout function");
			isLogin = false;
			//sessionStorage.removeItem('fbuid');
			document.cookie = 'uid' + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			document.cookie = '<?php echo HYDI_AUTH_KEY ?>' + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			FB.logout();
		}
		
	});
}

/**************************End Authentication functions**************************/

//Activity Detail related functions
var activityDetailFn = function(objectid){
	$('#lower-content').hide();
	//Get activity reviews
	$.ajax({
		url: ajaxurl,
		type: 'GET', 
		dataType:'json',        
		data: {
			requestPath: HYDI_API.ACTIVITY_DETAILS,
			params: {
				objectid:objectid
			},
			action: 'callHydiApi'
		},
		success:
			function(response){
				//Activity Details Obj

				var activityDetails = response;
				console.log(activityDetails);

				//render reviews
				var _activityReviewsTpl = $('#activity-reviews').html();
				$("#review-content").eq(0).html(_.template(_activityReviewsTpl, {
			        data: {
			            reviews: activityDetails.reviews[0]
			        }
			    }));			    
			    events();
			},
		error:
			function(e){
				console.log("Failed to get activity details");
				console.log(e);
			}
	});

	var events = function(){
		//Clicked down
		$('#transit-down').on('click', function(){
			//hide upper
			$('#upper-content').slideUp();

			$('#lower-content').slideDown();
			$('#transit-up').show().prop('disabled', false);
			$(this).hide().prop('disabled', true);
		});

		//Clicked up
		$('#transit-up').on('click', function(){
			//hide lower
			$('#lower-content').slideUp();
			//show upper
			$('#upper-content').slideDown();
			$('#transit-down').show().prop('disabled', false);
			$(this).hide().prop('disabled', true);
		});

		//Icon clicked : upvote/downvote/done
		$('.icon').on('click', function(){
			var $this = $(this);
			//upvote
			if($this.hasClass('up-arrow')){
				writeVote(objectid, 1, false);
			}
			//downvote
			else if($this.hasClass('down-arrow')){
				writeVote(objectid, 0, false);
			}
			//done
			else if($this.hasClass('tick-mark')){
				writeVote(objectid, 1, true);
			}
			//$("#review-content").empty();
			activityDetailFn(objectid);

		});
	}

	var writeVote = function(/*string*/ objectid, /*string*/ type, /*boolean*/ doneFlag){
		$.ajax({
			url: ajaxurl,
			type: 'POST', 
			dataType:'',        
			data: {
				requestPath: HYDI_API.USER_ACTIVITY_VOTES,
				params: {
					objectid:objectid,
					userid: getCookie("uid"),
					voteType: type, 
					doneFlag: doneFlag,
					auth: getCookie('HYDIAUTHKEY')
				},
				action: 'callHydiApi'
			},
			success:
				function(response){
					//Activity Details Obj
					var activityDetails = response;
					console.log(activityDetails);
				},
			error:
				function(e){
					console.log("Failed to POST review");
					console.log(e);
				}
		});
	};
};

var activityDetailMobileFn = function(){
	$('.grid-header').on('click', function(){
		if($(document).width() < CONST_MOBILE_WIDTH){
			var thisAccordionContent = $(this).siblings('.content');

			//check if this accordion is being displayed in mobile
			var displayStatus = $(thisAccordionContent).css('display');
			if(displayStatus == 'block'){
				$(thisAccordionContent).slideUp();
			}
			else if(displayStatus == 'none'){
				$(thisAccordionContent).slideDown();
			}
		}
	});
}

//User Profile related functions
var userProfileFn = function(){
	//Get User Info
	var userProfile = {};

	//var uid = sessionStorage.getItem('fbuid');
	var uid = getCookie('uid');
	var auth = getCookie('HYDIAUTHKEY');

	$.ajax({
		url: ajaxurl,
		type: 'GET', 
		dataType:'json',        
		data: {
			requestPath: HYDI_API.USER_PROFILE_INFO,
			params: {
				userid:uid, //e.g. 12317263743
				auth:auth
			},
			action: 'callHydiApi'
		},
		success:
			function(response){
				//console.log("Successful retrieve user info.");
				//console.log(response);
				
				//User Profile Obj
				userProfile = response;
				console.log(userProfile);

				//render user info
				var _userProfileTpl = $('#user-profile-tpl').html();
				$("#profile-info").eq(0).append(_.template(_userProfileTpl, {
			        data: {
			            userProfile: userProfile
			        }
			    }));
			    events();
			},
		error:
			function(e){
				console.log("Failed to get user info");

				//Show Guest pic and advise to login
				var _userProfileTpl = $('#user-profile-tpl').html();
				
				$("#profile-info").eq(0).append(_.template(_userProfileTpl, {
			        data: {
			        	userProfile: ""
			        }
			    }));
			    
				console.log(e);
			}
	});

	//Display Activities
	var displayActivities = function(userActivities, type){
		//render past activities
		$('#past-activities').empty();
		var _pastActivitiesTpl = $('#past-activities-tpl').html();
		$("#past-activities").eq(0).append(_.template(_pastActivitiesTpl, {
	        data: {
	            activities: userActivities,
	            activityType: type
	        }
	    }));

	}

	//Events
	var events = function(){
		//Clicked up
		$('#transit-up').on('click', function(){
			//hide lower
			$('#past-list').slideUp();
			//show upper
			$('#upper-content').slideDown();
			$('#transit-up').show().prop('disabled', false);
			$(this).hide().prop('disabled', true);
		});

		//Clicked icon
		$('.icon').on('click', function(){
			var $this = $(this);
			var clickedHeader = "";
			if($this.hasClass('up-arrow')){
				clickedHeader = "LIKES";
				$('#past-list h2').removeClass().addClass('icon up-arrow-single');
				displayActivities(userProfile.activities.upvotes, "upvotes");
			}
			else if($this.hasClass('down-arrow')){
				clickedHeader = "DISLIKES";
				$('#past-list h2').removeClass().addClass('icon down-arrow-single');
				displayActivities(userProfile.activities.downvotes, "downvotes");
			}
			else if($this.hasClass('tick-mark')){
				clickedHeader = "DONE";
				$('#past-list h2').removeClass().addClass('icon tick-mark-single');
				displayActivities(userProfile.activities.done, "done");
			}
			//hide user's past list of activities
			$('#past-list').slideDown();

			$('#past-list h2').text(clickedHeader);
			$('#transit-up').show().prop('disabled', false);
		});
	}
};

/*
* Trend function for trends template
*/
var trendsFn = function(jsonResponse){
	var trendList = HydiTrends.getTrendList(jsonResponse);

	//Top Searches
	var _topSearchesTpl = $('#top-searches-template').html();
	$("#top-searches").eq(0).append(_.template(_topSearchesTpl, {
        data: {
            list: trendList.topSearches
        }
    }));

    //Trending on Twitter
    var _twitterTrendTpl = $('#twitter-trends-template').html();
	$("#twitter-trends").eq(0).append(_.template(_twitterTrendTpl, {
        data: {
            list: trendList.twitterTrends
        }
    }));

	console.log(trendList);
}

function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}