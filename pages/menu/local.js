$(document).ready(function(){
	$.fn.prettyPhoto({
		default_width: 400,
		default_height: 130,
		animation_speed: 'fast', 	/* fast/slow/normal */
		show_title: false, 			/* true/false */
		theme: 'facebook', 			/* light_rounded / dark_rounded / light_square / dark_square / facebook */
		modal: false, 				/* If set to true, only the close button will close the window */
		overlay_gallery: false, 	/* If set to true, a gallery will overlay the fullscreen image on mouse over */
		keyboard_shortcuts: false, 	/* Set to false if you open forms inside prettyPhoto */
		social_tools: '' 			/* html or false to disable */
	});

	// $.prettyPhoto.close();
});