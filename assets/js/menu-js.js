//javascript file
window.onload = function(){
	/*
	*Toggles whether the menu drawer is open or closed
	*/
	$('span#menu-icon').click(function(){
		if($('#navigation.open').length){
			$('#navigation').removeClass('open').toggleClass('closed');
			$('section#main').removeClass('fade').toggleClass('full');
		}
		else{
			$('#navigation').removeClass('closed').toggleClass('open');
			$('section#main').removeClass('full').toggleClass('fade');
		}
		
	});

	$('span.mobile-nav-icon').click(function(){
		if($('.mobile-nav-list-wrapper.open').length){
			$('.mobile-nav-list-wrapper').removeClass('open').toggleClass('closed');
			$('section#main').removeClass('fade').toggleClass('full');
		}
		else{
			$('.mobile-nav-list-wrapper').removeClass('closed').toggleClass('open');
			$('section#main').removeClass('full').toggleClass('fade');
		}
		
	});


}

