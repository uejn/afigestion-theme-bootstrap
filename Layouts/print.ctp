<!DOCTYPE html>
<html lang="es">
	<head>
		<?php echo $this->Html->charset();?>
		<title><?php echo $title_for_layout; ?> &raquo; <?php echo Configure::read('Site.title'); ?></title>
        <?php
			echo $this->Meta->meta();
	                echo $this->Html->meta('icon', '/theme/afitheme/img/favicon.ico');
			echo $this->Html->meta(array(
				'name'    => 'viewport',
				'content' => 'width=device-width, initial-scale=1'
			));
	                
	        echo $this->Layout->js();
	        echo $this->Html->css('print', 'stylesheet', array('media' => 'print'));
	        echo $this->Html->css('print_screen');
			echo $this->fetch('css');
	        echo $this->Blocks->get('script');
        ?>

        <style>

        	@-webkit-keyframes blinker {  
			  from { opacity: 1.0; }
			  to { opacity: 0.0; }
			}

        	#loading{
        		margin: 100px auto auto auto;
        		font-size: 200%;
        		color: #333;
        		-webkit-animation-name: blinker;  
				  -webkit-animation-iteration-count: infinite;  
				  -webkit-animation-timing-function: cubic-bezier(.5, 0, 1, 1);
				  -webkit-animation-duration: 1.7s; 
        	}
        </style>
    </head>
    <body>
    	<div id="loading">
    		Cargando Reporte...
    	</div>
    	
    	<div id="print-content" style="display:none">
        	<?php echo $content_for_layout; ?>
        </div>

        <script type="text/javascript">
        	window.print();
        	document.getElementById('loading').style.display = 'none'; 
    		document.getElementById('print-content').style.display = 'block'; 
        </script>
    </body>
</html>