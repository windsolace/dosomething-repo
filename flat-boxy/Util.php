<?php 
/*
* Util functions for Hydi
*/

class Util{
	/**
	* GET Google Trend param by Country Code
	* @param $countryCode (e.g. SG)
	* @return 0
	* Get country 'pn' from http://www.google.com/trends/hottrends/atom	* All regions: 0
	* AU: 8
	* SG: 5
	* KR: 23
	* MY: 34
	*/
	function getParamByCountryCode($countryCode){
		//Google Trends dont have Global - return SG as default
		if($countryCode === "GLOBAL" || $countryCode === ""){
			return 5;
		}
		else if($countryCode === "AU"){
			return 8;
		}
		else if($countryCode === "SG"){
			return 5;
		}
		else if($countryCode === "KR"){
			return 23;
		}
		else if($countryCode === "MY"){
			return 34;
		}
		//Return SG as default
		else return 5;
	}

	/**
	* GET WOEID by Country Code
	* @param $countryCode (e.g. SG)
	* @return string $woeid
	* SG: 23424948
	*/
	function getWOEIDByCountryCode($countryCode){
		if($countryCode === "" || $countryCode === "GLOBAL"){
			return "1";
		}
		else if($countryCode === "AU"){
			return "23424748";
		}
		else if($countryCode === "SG"){
			return "23424948";
		}
		else if($countryCode === "KR"){
			return "23424868";
		}
		else if($countryCode === "MY"){
			return "23424901";
		}
		else return "1";
	}
}
?>