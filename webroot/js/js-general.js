/**
 * Javascript General Para Todo el Proyecto
 */
$(document).ready(function(){
	$(window).scroll(function(){
            if (window.pageYOffset >= 1) {
                $('#footer').removeClass('footer-fixeado');
                $('#footer').addClass()('footer-relativo');
            }
        });
});