<?php
// Adjusting content width
if ($this->Regions->blocks('left') and $this->Regions->blocks('right')) {
	$span = "col-md-6";
}
elseif ($this->Regions->blocks('left') xor $this->Regions->blocks('right')) {
	$span = "col-md-9";
}
else {
	$span = "col-md-12";
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<?php
		echo $this->Html->charset();
		?>
		<title><?php echo $title_for_layout; ?> &raquo; <?php echo Configure::read('Site.title'); ?></title>



		<!-- Other -->
		<?php
		echo $this->Meta->meta();
        echo $this->Html->meta('icon', '/theme/afitheme/img/favicon.ico');
		echo $this->Layout->feed();
		echo $this->Html->meta(array(
			'name'    => 'viewport',
			'content' => 'width=device-width, initial-scale=1'
		));
	    ?>

		<!-- styles -->
		<?php
		echo $this->Html->css('/theme/afitheme/css/bootstrap.min');
        echo $this->Html->css('/theme/afitheme/css/style');
        echo $this->Html->css('/theme/afitheme/css/print', 'stylesheet', array('media' => 'print'));

		echo $this->fetch('css');

		// Scripts for our layout
		echo $this->Html->script('/theme/afitheme/js/jquery.min');
		echo $this->Html->script('/theme/afitheme/js/bootstrap.min');
        
		echo $this->Blocks->get('script');
       	echo $this->Html->script('/theme/afitheme/js/ajax_modal');
		?>

		
        <!--ESTILOS ROLES-->
        <?php
            if(!empty(CakeSession::read('Auth')['User']['Role']['alias'])){
                echo $this->Html->css('/theme/afitheme/css/roles/style_'.CakeSession::read('Auth')['User']['Role']['alias']);
            }
        ?>
	</head>
	<body>

		<div id="loaderbar">
			<?php echo $this->Html->image("Afigestion.spinner.gif");?>
		</div>

		<!-- Example modal-->
		<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog">
			  <div class="modal-dialog modal-lg" role="document">
			    <div class="modal-content">
			      
			      <div class="modal-body"></div>

			      <!--
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      </div>
			      -->
			    </div>
			  </div>
		</div>
		<!-- -->
		<div class="body-wrapper">

			<?php echo $this->fetch('pre_header'); ?>
			<?php echo $this->Regions->blocks('pre_header'); ?>
			<?php 
			if ( $this->request->action != 'login') {
				echo $this->element('header'); 
			}
			?>



			<div class="container body-container">
				<?php
					echo $this->Layout->sessionFlash();					
				?>
			</div>



			<?php echo $this->fetch('post_header'); ?>
			<?php echo $this->Regions->blocks('post_header'); ?>

			<div class="container body-container">

				<?php echo $this->Regions->blocks('pre_content'); ?>
				<div class="row">
					<?php if ($this->Regions->blocks('left')): ?>
						<div class="col-md-3">
							<?php echo $this->Regions->blocks('left'); ?>
						</div>
					<?php endif; ?>

					<div <div class=" <?php echo $span; ?>">
						<?php echo $content_for_layout; ?>
					</div>

					<?php if ($this->Regions->blocks('right')): ?>
						<div class="col-md-3">
							<?php echo $this->Regions->blocks('right'); ?>
							<?php echo $this->element('accordion'); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php echo $this->Regions->blocks('post_content'); ?>
			</div>
                        
			
		</div>
                        
                <footer class="body-footer no-print" id="footer">
                            <div class="container">
                                    <div class="col-sm-2 center">	
                                        <?php echo $this->Html->image('/theme/Afitheme/img/uejn_logo.png', array('width'=>'150px'))?>
                                    </div>
                                    <div class="col-sm-2 center">				
                                            <?php 
                                                echo $this->Html->link(
                                                    'Contacto con Sistemas',
                                                    '/contact/contact/'
                                                );
                                            ?>
                                            <br/>
                                        Tel: 4381-9241 Interno 139
                                    </div>
                            </div>
                    </footer>



        <!-- scripts -->
        
		<?php
		// Croogo JavaScript
		echo $this->Layout->js();

		
        echo $this->fetch('scripts_for_layout');
        echo $this->fetch('script');

	
		echo $this->Blocks->get('scriptBottom');
		echo $this->Js->writeBuffer();
		?>


		 <!--script de seguimiento de google-->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-70967459-1', 'auto');
            ga('send', 'pageview');

        </script>
        
        <!---->


	</body>
</html>