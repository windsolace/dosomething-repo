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
* @param uid
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
* @return uid, isLogin
*/
function getLoginStatus(uid, callback){
	//var uid = sessionStorage.getItem('fbuid');
	var auth = getCookie('HYDIAUTHKEY');
	if(auth){
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
	else{
		isLogin = false;
	}
}

/**
* Do a facebook login
* - Sets uid cookie
* - Redirect user to home_url after done
*/
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

/**
* Does a facebook logout
* - Clears uid cookie
* - Expires session cookie
*/
function fb_logout(){
	FB.getLoginStatus(function(response) {console.log(response);
		//if logged in
		if (response.status === 'connected') {
			console.log("Running logout function");
			isLogin = false;
			//sessionStorage.removeItem('fbuid');
			deleteCookie('uid', '/');
			deleteCookie('HYDIAUTHKEY', '/');
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

				//Generate google map
				var latitude = activityDetails.latitude;
				var longitude = activityDetails.longitude;
				if(latitude && longitude){
					$("#activity-gmap").show();
					getActivityMap(latitude, longitude);
				}

				//render reviews
				var _activityReviewsTpl = $('#activity-reviews').html();
				$("#review-content").eq(0).html(_.template(_activityReviewsTpl, {
			        data: {
			            reviews: activityDetails.reviews[0]
			        }
			    }));

			    //render reviews
				var _activityAddressTpl = $('#activity-address').html();
				$("#address-content").eq(0).html(_.template(_activityAddressTpl, {
			        data: {
			            address: activityDetails.address
			        }
			    }));

				getActivityImages(activityDetails.name.replace(/ /g,''));
			    
			    events();
			},
		error:
			function(e){
				console.log("Failed to get activity details");
				console.log(e);
			}
	});

	//Call API to get images
	var getActivityImages = function(hashtag){
		//GET user profile info
		$.ajax({
			url: ajaxurl,
			type: 'GET', 
			dataType:'json',        
			data: {
				requestPath: HYDI_API.ACTIVITY_IMAGES,
				params: {
					hashtag: hashtag //without #
				},
				action: 'callHydiApi'
			},
			success:
				function(response){
					console.log(response);
					//render user info
					
					var _imageParTpl = $('#activity-images').html();
					$("#image-gallery").eq(0).append(_.template(_imageParTpl, {
				        data: {
				            images: response
				        }
				    }));
				    events();
				},
			error:
				function(e){
					console.log("Failed to get images");
					console.log(e);
				}
		});
	}

	var events = function(){
		$(".group1").colorbox({rel:'group1',width:"75%", height:"75%"});
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
					activityDetailFn(objectid);
				},
			error:
				function(e){
					console.log("Failed to POST review");
					console.log(e);
				}
		});
	};

	var getActivityMap = function(latitude, longitude){
		var mapCanvas = $('#activity-gmap')[0];
		var activityMapMarker = new google.maps.LatLng(latitude, longitude)
		var mapOptions = {
	    	center: activityMapMarker,
			zoom: 18,
			mapTypeId: google.maps.MapTypeId.ROADMAP
	    }
	    var map = new google.maps.Map(mapCanvas, mapOptions);

	    var marker = new google.maps.Marker({
	    	position: activityMapMarker,
	    	map:map
	    });
	}
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

	//get fbid from query param, else use log in user else show guest
	//Try to get uid from query param
	var uid = getParameterByName('uid');
	if(!uid){
		uid = getCookie('uid');
	} 

	//var uid = sessionStorage.getItem('fbuid');
	var auth = getCookie('HYDIAUTHKEY');

	//GET user profile info
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
		console.log(userActivities);
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

/**
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

/**
 * New activity form
 */
var newActivityFormFn = function(){	
	var init = function(){
		events();
	}

	/**
	* events
	* 	event-1. Disable operating hours if 24-hrs is checked
	*	event-2. Populate address by postal code (Countries: SG)
	*	event-3. Change postalcode maxlength based on country select
	*	event-4. Custom form submit
	*/
	var events = function(){
		//event 1
		$('#chkBox_opHrs').change(function(){
			if ($('#chkBox_opHrs').is(':checked')) {
				document.getElementById("fromTime").disabled=true;
				document.getElementById("toTime").disabled=true;
			}
			else{
				document.getElementById("fromTime").disabled=false;
				document.getElementById("toTime").disabled=false;
			}
		});
		$('#fromTime').timepicker();
		$('#toTime').timepicker();
		
		//event-2
		$('input[name="postalcode"]').blur(function(){
			//use gothere.sg geo API if country selected is singapore
			if($('select[name="country"]').val().toLowerCase() === "singapore"){
				var postalCode = $('input[name="postalcode"]').val();
				var geocoder = new GClientGeocoder(); 
				geocoder.getLatLng(postalCode, function(response){
					geocoder.getLocations(response, function(places){
						var apiResponse = places.Placemark;

						//loop thorugh Placemark array and match postalcode entered
						$.each(apiResponse, function(i, placeMark){
							var tempArr = placeMark.address.split(',');

							//loop thru tempArr to find postalCode
							$.each(tempArr, function(j, addressObj){
								if(addressObj.indexOf(postalCode)> -1){
									var apiAddress = placeMark.address;
									$('textarea[name="address"]').text(apiAddress);
								}								
							});
						});
					});
				});
			}
		});
		
		//event-3
		$('select[name="country"]').change(function(){
			setFieldMaxLength($(this).val());
		});
		
		//event-4
		$('input[type="submit"]').on('click', function(e){
			e.preventDefault();
			console.log("submit clicked");
			$.ajax({
				url: ajaxurl,
				type: 'POST', 
				dataType:'json',        
				data: {
					requestPath: HYDI_API.NEW_ACTIVITY,
					params: {
						formData: $('#new-activities-form').serialize().replace(/\+/g,'%20')
					},
					action: 'callHydiApi'
				},
				success:
					function(response){
						//console.log("Successful retrieve user info.");
						console.log("success");
						console.log(response);
					},
				error:
					function(e){
						console.log("Error: Failed to POST new activity");				    
						console.log(e);
					}
			});
		});
	}
	
	/**
	* Sets postal code input field max length based on country selected
	* @param inputCountry - Country from select field
	* Singapore
	*	PostalCode: 6
	*	Phone: 8 
	*/
	var setFieldMaxLength = function(/*string*/ inputCountry){
		if(inputCountry.toLowerCase() === "singapore"){
			$('input[name="postal"]').show().prop('disabled', false);
			$('input[name="postal"]').attr('maxlength', '6');
			$('input[name="phone"]').attr('maxlength', '8');
		} else {
			$('input[name="postal"]').hide().prop('disabled', true);
		}
	}

	//validation
	var form_validation = function(){
		validationPassed = true;
		if($('input[name="category"]').length <= 0){
			validationPassed = false;
			console.log("Category checkboxes not checked!");
		}
		return validationPassed;
		/*
		if(callback)
			callback();
		*/
	};
	
	init();
}

function getCookie(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if (parts.length == 2) return parts.pop().split(";").shift();
}
function deleteCookie( name, path, domain ) {
	if( getCookie( name ) ) {
		document.cookie = name + "=" +
		((path) ? ";path="+path:"")+
		((domain)?";domain="+domain:"") +
		";expires=Thu, 01 Jan 1970 00:00:01 GMT";
	}
}

/**
* Search url for query param
* @param name - String to search for
* @return results
*/
function getParameterByName(name){
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var HydiUtil = {
	BROWSER_WIDTH: window.innerWidth || document.body.clientWidth,
	CONST_TRENDTILE_STRING_MAX_LENGTH : 25,

	truncateString :function(string, maxlength){
		if(!maxlength){
			maxlength = HydiUtil.CONST_TRENDTILE_STRING_MAX_LENGTH
		}
		if(string.length > maxlength){
			var string = string.substr(0,maxlength) + "...";
		}
		return string;
	}

}

function throwError(message){
	$.colorbox({
		html:"<p>"+message+"</p>"
	});
}