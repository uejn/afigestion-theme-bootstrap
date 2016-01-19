/**
 * Javascript General Para Todo el Proyecto
 */
$(document).ready(function(){
    if ($("body").height() > $(window).height()) {
        $('#footer').removeClass('footer-fixeado');
        $('#footer').addClass('footer-relativo');
    }
    $('#PersonaLegajoEstadoId1').prop('checked', true);
    
    if($("#PersonaLegajoEstadoId2").is(':checked')) { 
        $('#PersonaLegajoEstadoId1').prop('checked', false);
    }
    
    $('.combobox').combobox();
  
});