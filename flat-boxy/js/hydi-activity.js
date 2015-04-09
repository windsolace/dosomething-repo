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
}

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

	return result;
}
