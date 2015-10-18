/**
* Facebook functions
* ======================================
* Contents
* ======================================
* hfb001. fbinit
* hfb002. fb_getUserById
*/

/**
* hfb001.
* Get fb script
* @param method - define which function to call
* @param arg - json obj of arguments
*/
var fbinit = function(/*string*/ method ,/*json*/ arg){
	$.ajaxSetup({cache:true});
	$.getScript('//connect.facebook.net/en_US/sdk.js', function(){
	    FB.init({
	      appId: '487237444757903',
	      version: 'v2.3' // or v2.0, v2.1, v2.0
	    });    

	   	FB.getLoginStatus(function(response){
	   		//check login status to get accesstoken required for api calls
	   		if(response.status === 'connected'){
	   			if(method === "getuserbyid"){
			   		fb_getUserById(arg, response.authResponse.accessToken);
			   	}
	   		} else{
	   			console.log("[fbinit]: Requires user login.");
	   		}
			
	   	});

	   
  	});
}

/**
* hfb002.
* Get user obj by facebook id
* @param arg - json obj of arguments
* @return userobj
*/
var fb_getUserById = function(/*json*/ arg, /*string*/ accessToken){
	var userid = arg.fbid;
	var requestpath = "/" + userid;
	FB.api(requestpath, {access_token: accessToken}, function(response){
		var userobj = response;
		console.log(userobj);
		return userobj;
	});

}