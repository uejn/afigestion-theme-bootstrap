<header class="navbar navbar-default navbar-static-top no-print" role="navigation">
	<div class="container">
		<div class="row">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<div class="col-sm-3 col-xs-12">
			<?php echo $this->Html->link(Configure::read('Site.title'), '/', array('class' => 'navbar-brand')) ?>
			</div>

			<div class="col-sm-6 col-xs-12" style="padding-top: 10px;">
				<?php
				if ($this->Session->read('Auth.User.id')) {
					$rolAlias = CakeSession::read('Auth.User.Role.alias');
	                if( $rolAlias != AFIGESTION_ROL_SECCIONAL ){
			                echo $this->Form->create('Persona', array(
			                	'url'=> array(
			                		'plugin' => 'afigestion',
		                        'controller'=>'personas',
		                        'action'=>'index',
		                       // '?'=> $this->params->query
		                        ),
				                'id'=>'form-persona-find-header',
				                'class' =>'form-inline'
		                	)
			                );
		                	$autoFocus = true;
		                	/*if(  $this->params->controller == 'personas' && $this->params->action == 'advanced_search' ){*/
		                		/*$autoFocus = true;
		                	}*/
			                if( $this->action != 'home' ){
				                echo $this->Form->input('search', array(
			                        'label'=>false,
			                        'placeholder' => 'Nombre, Apellido, Documento, Legajo ...',
			                        'class'=>'form-control input-sm col-xs-11 autocomplete',
			                        'data-url' => $this->Html->url(array('plugin'=>'afigestion','controller'=>'personas','action'=>'fastSearch')),
									'style' => 'width:100%',
			                        'div' => array(
			                        	'style'=> 'width:80%',
			                        	'class' => '',
			                        	),
		                            'autofocus' => $autoFocus
		                        ));
				                echo  $this->Form->button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', array(
										'type'=>'submit',
										'class' => 'btn btn-default btn-sm',
										'escape'=>false));
			                }

			                echo  $this->Form->end();
	                }
	            }
	                ?>
			</div>

			<div class="col-sm-3 col-xs-12">
				<?php echo $this->element('userlogin'); ?>
			</div>
		</div>
</div>





	<div class="container">
		<div class="navbar-header">
			<div class="navbar-collapse collapse">
				<?php 
				echo $this->Custom->menu('main', array('dropdown' => true)); ?>
			</div>
		</div>
	</div>
</header>


<script type="text/javascript">
	
	$('.autocomplete').map(inicializacion);


	function inicializacion(index, el) {

		var url = $(el).data("url");
	}
</script>