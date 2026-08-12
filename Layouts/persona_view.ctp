<?php

/**
 * Layout aislado en Bootstrap 5 para la ficha de Persona.
 * BS3 y BS5 no pueden convivir en la misma página, por eso esta acción no usa
 * el layout default. El markup heredado (navbar de Croogo, ajax_modal) sigue
 * funcionando gracias a bs5_compat.js.
 */
$seEstaActualizando = Configure::read("Site.estado_actualizacion");
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout; ?> &raquo; <?php echo Configure::read('Site.title'); ?></title>

	<meta name="robots" content="noindex,nofollow">
	<?php
	echo $this->Meta->meta();
	echo $this->Html->meta('icon', '/theme/afitheme/img/favicon.ico');
	echo $this->Html->meta('apple-touch-icon', '/theme/afitheme/img/favicon.ico');
	echo $this->Layout->feed();
	echo $this->Html->meta(array(
		'name'    => 'viewport',
		'content' => 'width=device-width, initial-scale=1'
	));
	?>

	<!-- styles -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap">
	<!-- display=block evita el flash del texto de la ligadura antes de que cargue el icono -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous">
	<?php
	echo $this->Html->css('/theme/afitheme/css/style');
	// Después de style.css para pisar el tema BS3, antes del CSS por rol para no tapar sus reglas de visibilidad.
	echo $this->Html->css('/theme/afitheme/css/persona_view_md');
	echo $this->Html->css('/theme/afitheme/css/print', 'stylesheet', array('media' => 'print'));

	if (!empty(CakeSession::read('Auth')['User']['Role']['alias'])) {
		echo $this->Html->css('/theme/afitheme/css/roles/style_' . CakeSession::read('Auth')['User']['Role']['alias']);
	}
	echo $this->fetch('css');
	?>

	<!-- scripts -->
	<?php
	echo $this->Html->script('/theme/afitheme/js/jquery.min');
	?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<?php
	echo $this->Html->script('/theme/afitheme/js/bs5_compat');
	echo $this->Html->script('/theme/afitheme/js/ajax_modal');
	echo $this->Html->script('/theme/afitheme/js/chartGoogle');
	echo $this->Html->script('Afigestion.afi_matomo');
	echo $this->Html->script('Afigestion.reiniciar_password');
	echo $this->Blocks->get('script');
	?>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
				new bootstrap.Tooltip(el);
			});
			document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function(el) {
				new bootstrap.Popover(el);
			});
		});
	</script>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-70967459-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', 'UA-70967459-1');
	</script>

	<?php
	$matomoUserId = $this->Session->read("Auth.User.matomo_id");
	$siteId = Configure::read("Matomo.siteId");
	if (FULL_BASE_URL != 'http://localhost') {
	?>
		<script type="text/javascript">
			var _paq = _paq || [];
			AfiMatomo.init(<?php echo $siteId ?>, "<?php echo $matomoUserId ?>");
			(function() {
				var u = '//matomo.uejn.org.ar/';
				_paq.push(['setTrackerUrl', u + 'piwik.php']);
				var d = document,
					g = d.createElement('script'),
					s = d.getElementsByTagName('script')[0];
				g.type = 'text/javascript';
				g.async = true;
				g.defer = true;
				g.src = u + 'piwik.js';
				s.parentNode.insertBefore(g, s);
			})();
		</script>
	<?php } ?>
</head>

<body>
	<?php if (!empty($seEstaActualizando) && !(empty(CakeSession::read('Auth')))): ?>
		<div class="alert alert-danger text-center mb-0"><b>En este momento se está realizando una actualización sobre los datos en Afigestion,
				se recomienda no modificar ningún dato hasta que dicha actualización termine</b></div>
	<?php endif; ?>

	<div id="loaderbar">
		<?php echo $this->Html->image("Afigestion.spinner.gif"); ?>
	</div>

	<!-- backdrop static: el click fuera no cierra, se cierra sólo con la X -->
	<div class="modal fade" id="ajaxModal" tabindex="-1" data-bs-backdrop="static">
		<div class="modal-dialog modal-lg modal-dialog-scrollable">
			<div class="modal-content">
				<button type="button" class="btn-close ajax-modal__cerrar" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				<div class="modal-body"></div>
			</div>
		</div>
	</div>

	<div class="body-wrapper">
		<?php echo $this->fetch('pre_header'); ?>
		<?php echo $this->Regions->blocks('pre_header'); ?>
		<?php echo $this->element('header'); ?>

		<div class="container body-container">
			<?php echo $this->Layout->sessionFlash(); ?>
		</div>

		<?php echo $this->fetch('post_header'); ?>
		<?php echo $this->Regions->blocks('post_header'); ?>

		<div class="container-fluid">
			<?php echo $this->Regions->blocks('pre_content'); ?>
			<?php echo $content_for_layout; ?>
			<?php echo $this->Regions->blocks('post_content'); ?>
		</div>
	</div>

	<footer class="body-footer no-print" id="footer">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-sm-2 text-center">
					<?php echo $this->Html->image('/theme/Afitheme/img/uejn_logo.png', array('width' => '150px')) ?>
				</div>
				<div class="col-sm-4 text-center">
					<?php if ($this->Session->read('Auth.User')) { ?>
						<b>Contacto con sistemas:</b> <br /><a href="mailto:sistemas@uejn.org.ar">sistemas@uejn.org.ar</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</footer>

	<?php
	echo $this->Layout->js();
	echo $this->fetch('scripts_for_layout');
	echo $this->fetch('script');
	echo $this->Blocks->get('scriptBottom');
	echo $this->Js->writeBuffer();
	?>
</body>

</html>