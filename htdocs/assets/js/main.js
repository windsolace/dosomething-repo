//main.js
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
	//TO-DO: add condition to detect when its mobile
	activityDetailMobileFn();
	activityDetailFn();

	userProfileFn();
};

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