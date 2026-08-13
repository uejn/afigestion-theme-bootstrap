<header class="navbar navbar-default navbar-static-top no-print afi-header" role="navigation">
	<div class="container-fluid afi-header__bar">
		<?php echo $this->Html->link(Configure::read('Site.title'), '/', array('class' => 'navbar-brand')) ?>

		<div class="afi-header__search">
			<?php
			if ($this->Session->read('Auth.User.id')) {
				$rolAlias = CakeSession::read('Auth.User.Role.alias');
				if ($rolAlias != AFIGESTION_ROL_SECCIONAL && $this->action != 'home') {
					echo $this->Form->create('Persona', array(
						'url' => array(
							'plugin' => 'afigestion',
							'controller' => 'personas',
							'action' => 'index',
						),
						'id' => 'form-persona-find-header',
						'class' => 'form-inline'
					));
					echo $this->Form->input('search', array(
						'label' => false,
						'placeholder' => 'Nombre, Apellido, Documento, Legajo ...',
						'class' => 'form-control input-sm autocomplete',
						'data-url' => $this->Html->url(array('plugin' => 'afigestion', 'controller' => 'personas', 'action' => 'fastSearch')),
						'div' => array('class' => 'afi-header__search-field'),
						'autofocus' => true
					));
					echo $this->Form->button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', array(
						'type' => 'submit',
						'class' => 'btn btn-default btn-sm',
						'escape' => false
					));
					echo $this->Form->end();
				}
			}
			?>
		</div>

		<div class="afi-header__user">
			<?php echo $this->element('userlogin'); ?>
		</div>

		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>

	<div class="container-fluid afi-header__nav">
		<div class="navbar-collapse collapse">
			<?php echo $this->Custom->menu('main', array('dropdown' => true)); ?>
		</div>
	</div>
</header>


<script type="text/javascript">
	$('.autocomplete').map(inicializacion);

	function inicializacion(index, el) {
		var url = $(el).data("url");
	}
</script>