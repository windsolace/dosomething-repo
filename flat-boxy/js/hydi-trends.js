/*
* Hydi Trends Object
*/

var HydiTrends = function(){
	this.country = {
		code: "",
		name: ""
	};
	this.trendList = {};
}

/*
* GET JSON list of trends
* @params json - JSON object from backend
* returns JSON trendList
*/
HydiTrends["getTrendList"] = function(json){
	var result = new HydiTrends();
	var countryCode = json.code;
	var countryName = HydiTrends.getCountryName(countryCode);

	result.trendList = json.results;
	result.country.code = countryCode;
	result.country.name = countryName;

	return result;
}

/*
* GET Country Code by Code
* @params code - Code defined by Google Trends
* returns countryCode (default "ALL")
*/
HydiTrends["getCountryByCode"] = function(code){
	switch(code){
		case 0:
			return "ALL";
			break;
		case 5:
			return "SG";
			break;
		case 8:
			return "AU";
			break;
		case 34: 
			return "MY";
			break;
		default:
			return "ALL";
			break;
	}
}

/*
* GET Country Name by Country Code
* @params countryCode 
* returns countryName (default "All regions")
*/
HydiTrends["getCountryName"] = function(countryCode){
	if(countryCode == "ALL"){
		return "All regions";
	}
	else if(countryCode == "AU"){
		return "Australia";
	} 
	else if(countryCode == "SG"){
		return "Singapore";
	}
	else if(countryCode == "MY"){
		return "Malaysia";
	}
}
