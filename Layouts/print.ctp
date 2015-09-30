<!DOCTYPE html>
<html lang="es">
	<head>
		<?php
		echo $this->Html->charset();
		?>
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
    </head>
    <body>
        <?php echo $content_for_layout; ?>
    </body>
</html>