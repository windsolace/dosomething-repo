 /*
* Activity Object
*/
var HydiActivity = function(){
	this.raw = {};
	this.name = "";
	this.description = "";
	this.country = "";
	this.contact = {
		address: "",
		longitude: "",
		latitude: "",
		region: "",
		phone: "",
		website: "",
	};
	
	this.minPax = 0;
	this.maxPax = 0;
	this.averagePrice = 0.00;
	this.timeRange = 0;
	this.reviews = {
		upvotes:0,
		downvotes:0,
		done:0,
	};
}

/*
* Parse JSON object into HydiActivity object
*/
HydiActivity['parse'] = function(json){
	var result = new HydiActivity();

	result.raw = json;
	result.name = json.name;
	result.description = json.description;
	result.country = json.country;

	//contact
	result.contact.address = json.address;
	result.contact.longitude = json.longitude;
	result.contact.latitude = json.latitude;
	result.contact.region = json.region;
	result.contact.phone = json.phone;
	result.contact.website = json.website;

	result.minPax = json.minPax;
	result.maxPax = json.maxPax;
	result.averagePrice = json.averagePrice;
	result.timeRange = json.timeRange;

	//reviews
	var rawReviews = json.reviews;
	result.reviews.upvotes = parseInt(rawReviews[0].upvotes);
	result.reviews.downvotes = parseInt(rawReviews[0].downvotes);
	result.reviews.done = parseInt(rawReviews[0].done);

	return result;
}

/*
* Upvote for an activity
* 0 = down, 1 = up
*/
HydiActivity['vote'] = function(type) {
	switch(type) {
		case 0:
			//DOWNVOTE
			break;
		case 1: 
			//UPVOTE
			break;
		default:
			break;

	}

	//POST to PHP for backend verification
	var data = {
		'voteType' : 1
	};
	/*
	$.post(ajaxurl, data, function(response){
		console.log("response from server " + response);
	});
	*/
	var auth = getCookie("HYDIAUTHKEY");
	var uid = sessionStorage.getItem("fbuid");
	$.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			requestPath: 'hydi/activity/reviews',
			data: data,
			voteType: data,
			userid: uid,
			auth: auth
		},
		success:
			function(response){
				console.log("Successful POST vote");
				console.log(response);
			},
		error:
			function(e){
				console.log("Failed to POST vote.");
				console.log(e);
			}
	});
 
}
