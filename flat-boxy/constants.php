<?php 
/*
* Constants.php
* Define constants
*/
define("SESSION_ID_LENGTH", 32);
define("HYDI_AUTH_KEY", "HYDIAUTHKEY");

define("HYDI_INSTA_ID", "");
define("HYDI_INSTA_SECRET", "");

define("TABLE_HYDI_ACTIVITY", "activity");
define("TABLE_HYDI_USERLIKES", "fb_user_likes");
define("TABLE_HYDI_USERS", "fb_user");

/*
* API URL CONSTANTS (ref hydi-api.js)
*/
//API 04
define("SITE_LOGIN", "/hydi/api/login");
//API 05
define("USER_PROFILE_INFO", "/hydi/api/user");
//API 02
define("ACTIVITY_DETAILS", "/hydi/api/activity");
//API 03
define("ALL_ACTIVITIES", "/hydi/api/allactivities");
//API 01
define("USER_ACTIVITY_VOTES", "/hydi/api/activity/users");
?>