/**
 * Javascript General Para Todo el Proyecto
 */
$(document).ready(function(){
    if ($("body").height() > $(window).height()) {
        $('#footer').removeClass('footer-fixeado');
        $('#footer').addClass('footer-relativo');
    }
    
    $('.combobox').combobox();
  
});