/*
* Hydi Trends Object
*/

var HydiTrends = function(){
	this.topSearches = {
		country: {
			code:"",
			name:""
		},
		trendList:{}
	};
	this.twitterTrends = {
		timestamp: "",
		location:{
			code:"",
			name:""
		},
		trendList:{}
	};
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
	var trendList = [];


	$.each(json.topSearches, function(key, value){
		var trendObj = {};
		var valueArr = value.split("~");
		trendObj.title = valueArr[0];
		trendObj.pubDate = valueArr[1];
		trendObj.link = valueArr[2];
		trendList.push(trendObj);
	});

	//Top Searches
	result.topSearches.trendList = trendList;
	result.topSearches.country.code = countryCode;
	result.topSearches.country.name = countryName;

	//Twitter Trends
	try{
		var epochtimestamp = new Date(json.twitterTrends[0].created_at);
		result.twitterTrends.timestamp = epochtimestamp.getTime()/1000;
		result.twitterTrends.location.code = json.twitterTrends[0].locations[0].woeid;
		result.twitterTrends.location.name = json.twitterTrends[0].locations[0].name;
		result.twitterTrends.trendList = json.twitterTrends[0].trends;
	}
	catch(e){
		console.log(json.twitterTrends.errors[0]);
	}


	return result;
}

/*
* GET List of Top Searches 
* @params json - JSON object from backend
* returns JSON trendList
*/
HydiTrends["getTopSearches"] = function(json){
	var topSearchesObj = {};
	var countryCode = json.code;
	var countryName = HydiTrends.getCountryName(countryCode);
	var trendList = [];

	$.each(json.results, function(key, value){
		var trendObj = {};
		var valueArr = value.split("~");
		trendObj.title = valueArr[0];
		trendObj.pubDate = valueArr[1];
		trendObj.link = valueArr[2];
		trendList.push(trendObj);
	});

	topSearchesObj.trendList = trendList;
	topSearchesObj.country.code = countryCode;
	topSearchesObj.country.name = countryName;

	return topSearchesObj;
}

/*
* GET Country Code by Code
* @params code - Code defined by Google Trends
* returns countryCode (default "ALL")
*/
HydiTrends["getCountryByCode"] = function(code){
	switch(code){
		case 0:
			return "GLOBAL";
			break;
		case 5:
			return "SG";
			break;
		case 8:
			return "AU";
			break;
		case 23: 
			return "KR";
			break;
		case 34: 
			return "MY";
			break;
		default:
			return "GLOBAL";
			break;
	}
}

/*
* GET Country Name by Country Code
* @params countryCode 
* returns countryName (default "All regions")
*/
HydiTrends["getCountryName"] = function(countryCode){
	if(countryCode == "GLOBAL"){
		return "Global";
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
	else if(countryCode == "KR"){
		return "Korea";
	}
}
