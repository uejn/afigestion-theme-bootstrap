
 		function mostrarModal( event ){
 			event.preventDefault();
 			console.info(event);
 			console.info(event.target.href);
 			$( "#ajaxModal .modal-body" ).load(  event.target.href );
 			$( "#ajaxModal").modal("show");
 			return false;
	    }



	    $(function(){
	    	$(document).on('click', '.ajax-modal', mostrarModal);
		    


		    $('#ajaxModal').on('hidden.bs.modal', function (e) {
			  	$( "#ajaxModal .modal-body" ).html("");
			});



			$('#loaderbar').hide();

		    var timeout;
			$( document ).ajaxComplete(function() {
				clearTimeout(timeout);
				$('#loaderbar').hide();
			    $('#loaderbar .progress-bar').css({'width': '100%'});
			});

			$( document ).ajaxStart(function() {
				$('#loaderbar').show();
				var load = 0;
				timeout = setTimeout(function(){
					console.info("incrementado loader %o", load);
					load = load + 10;
			  		$('#loaderbar .progress-bar').css({'width': load+'%'});
					if ( load = 100 ) {
						load = 0;
					}
				}, 100);
			});

	    });