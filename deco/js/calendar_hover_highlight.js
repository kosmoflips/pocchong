$(document).ready(function() {
	$(".day-link").hover(function() {
		var id = $(this).attr('href');//.replace(/#/, '');
		$('li.day-link-target').css({
			'background': 'none'
		});
		$(id).css({
			'background': 'rgba(201,142,255,0.4)'
		});
	});
});