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
<html lang="en">
	<head>
		<?php
		echo $this->Html->charset();
		?>
		<title><?php echo $title_for_layout; ?> &raquo; <?php echo Configure::read('Site.title'); ?></title>


		<link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>

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
		echo $this->Html->css('bootstrap.min');
		

		echo $this->fetch('css');
		?>

		<!-- scripts -->
		<?php
		// Croogo JavaScript
		echo $this->Layout->js();

		// Scripts for our layout
		echo $this->Html->script('jquery.min');
		echo $this->Html->script('bootstrap.min');
		?>

		<!-- Plugins -->
		<?php
		echo $this->fetch('css');
		echo $this->Blocks->get('script');
		?>
        <!--ESTILO GENERAL-->
        <?php
            echo $this->Html->css('style');
            echo $this->Html->css('print', 'stylesheet', array('media' => 'print'));
        ?>
        <!---->
        <!--ESTILOS ROLES-->
        <?php
            if(!empty(CakeSession::read('Auth')['User']['Role']['alias'])){
                echo $this->Html->css('roles/style_'.CakeSession::read('Auth')['User']['Role']['alias']);
            }
        ?>
        <!---->
	</head>
	<body>
		<div class="body-wrapper">
			<?php echo $this->fetch('pre_header'); ?>
			<?php echo $this->Regions->blocks('pre_header'); ?>
			<?php echo $this->element('header'); ?>


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

			<footer class="body-footer no-print">
				<div class="container">
					<div class="pull-left">
						<?php echo $this->Html->image('/theme/Afitheme/img/uejn_logo.png', array('width'=>'100px'))?>Sistemas
					</div>
					<div class="pull-right">
						Puede comunicarse con el equipo de sistemas llamando al interno 136
					</div>
				</div>
			</footer>

		    <?php
				echo $this->Blocks->get('scriptBottom');
				echo $this->Js->writeBuffer();
			?>
		</div>
	</body>
</html>