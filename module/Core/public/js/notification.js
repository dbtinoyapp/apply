
;(function ($) {
	
	$(function () 
	{
		setTimeout(
			function() { $('.ppt-notifications .alert').slideUp(250); },
			6000
		);
		$('.ppt-notifications .alert button').click(function(event) {
			$(event.target).parent().slideUp(250);
			return false;
		});
		
	});

})(jQuery);