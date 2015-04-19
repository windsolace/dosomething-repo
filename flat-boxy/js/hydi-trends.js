/*
* Hydi Trends Object
*/
	//Google Trends : http://techslides.com/hacking-the-google-trends-api
	//url: GET http://hawttrends.appspot.com/api/terms/
	//All regions: 0
	//SG: 5
	//MY: 34

var HydiTrends = function(){
	this.country = {
		SG: 5,
		MY: 34
	};
	this.trendlist = {};

	

}

HydiTrends["getListByCode"] = function(countryCode){

	$.ajax({
		url: 'http://hawttrends.appspot.com/api/terms/',
		type: 'GET',
		//jsonpCallback: 'jsonCallback',
		contentType:'application/json',
		dataType: 'jsonp',
		success:function(){
			console.log("successful");
		}
	});



}
