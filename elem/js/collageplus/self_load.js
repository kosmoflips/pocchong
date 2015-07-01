$(window).load(function () {
	$(document).ready(function(){
		collage();
		$('.Collage').collageCaption();
	});
});
function collage() {
	$('.Collage').removeWhitespace().collagePlus(
		{
			'allowPartialLastRow' : true,
			'fadeSpeed'     : 2000,
			'targetHeight'  : 350,
			// 'effect'        : 'default', other effect , need load css
			'direction'     : 'vertical'
		}
	);
};