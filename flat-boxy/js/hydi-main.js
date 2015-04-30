/*
* hydi-main.js
*/
$(document).ready(function(){
	var CONST_MOBILE_WIDTH = 760;

	//initialize
	hydiInit();

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
				console.log("Successful check login status: isLogin: " + response.isLoggedIn);
				isLogin = response.isLoggedIn;
			},
		error:
			function(e){
				console.log("Failed to check login status");
				console.log(e);
			}
	});
}

//Activity Detail related functions
var activityDetailFn = function(){
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
			/* TO-DO:
			* 1) Write to activity (+1)
			* 2) Write to user -> upvote (+1)
			* 3) Hide and disable
			* 4) Show/Enable downvote IF disabled
			*/
		}
		//downvote
		else if($this.hasClass('down-arrow')){
			/* TO-DO:
			* 1) Write to activity (+1)
			* 2) Write to user -> downvote (+1)
			* 3) Hide and disable
			* 4) Show/Enable downvote IF disabled
			*/
		}
		//done
		else if($this.hasClass('tick-mark')){
			/* TO-DO:
			* 1) Write to activity (+1)
			* 2) Write to user -> Done (+1)
			* 3) Hide and disable
			* 4) Show/Enable downvote IF disabled
			*/
		}
	});

	var writeVote = function(/*string*/ objectid, /*string*/ type){
		//TO-DO: Write to activity table

		//TO-DO: Write to User table
	};
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
		}
		else if($this.hasClass('down-arrow')){
			clickedHeader = "DISLIKES";
			$('#past-list h2').removeClass().addClass('icon down-arrow-single');
		}
		else if($this.hasClass('tick-mark')){
			clickedHeader = "DONE";
			$('#past-list h2').removeClass().addClass('icon tick-mark-single');
		}
		//hide user's past list of activities
		$('#past-list').slideDown();

		$('#past-list h2').text(clickedHeader);
		$('#transit-up').show().prop('disabled', false);
	});
};

/*
* Trend function for trends template
*/
var trendsFn = function(jsonResponse){
	var trendList = HydiTrends.getTrendList(jsonResponse);
	var _topSearchesTpl = $('#top-searches-template').html();
	$("#top-searches").eq(0).append(_.template(_topSearchesTpl, {
        data: {
            list: trendList
        }
    }));
	console.log(trendList);
}