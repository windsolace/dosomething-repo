//main.js
$(document).ready(function(){
	//initialize
	hydiInit();

});

//init
var hydiInit = function(){
	activityDetailFn();
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