
 		function mostrarModal( event ){
 			event.preventDefault();
 			$( "#ajaxModal .modal-body" ).load(  event.currentTarget.href );
 			$( "#ajaxModal").modal("show");
 			return false;
	    }



	    $(function(){
	    	$(document).on('click', '.ajax-modal', mostrarModal);
		    


		    $('#ajaxModal').on('hidden.bs.modal', function (e) {
			  	$( "#ajaxModal .modal-body" ).html("");
			});



			AfiLoader.hide();

			$( document ).ajaxComplete(function() {
				AfiLoader.hide();
			});

			$( document ).ajaxStart(function() {
				AfiLoader.show();
			});

	    });