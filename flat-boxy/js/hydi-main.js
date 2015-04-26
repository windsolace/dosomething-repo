/*
* hydi-main.js
*/

function isLoggedIn(){
	$.ajax({
		url: ajaxurl,
		type: 'GET', 
		dataType:'json',        
		data: {
			requestPath: HYDI_API.SITE_LOGIN,
			params: {
				userid:12317263743
			},
			action: 'callHydiApi'
		},
		success:
			function(response){
				console.log("Successful check login status");
				console.log(response.isLoggedIn);
				isLogin = response.isLoggedIn;
			},
		error:
			function(e){
				console.log("Failed to check login status");
				console.log(e);
			}
	});
}