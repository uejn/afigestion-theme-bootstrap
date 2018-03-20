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
	                echo $this->Form->create('Persona', array(
	                	'url'=> array(
                        'controller'=>'personas',
                        'action'=>'index',
                       // '?'=> $this->params->query
                        ),
		                'id'=>'form-persona-find-header',
		                'class' =>'form-inline'
                	)
	                );
                	$autoFocus = false;
                	if(  $this->params->controller == 'personas' && $this->params->action == 'advanced_search' ){
                		$autoFocus = true;
                	}
	                
	                echo $this->Form->input('search', array(
	                        'label'=>false,
	                        'placeholder' => 'Nombre, Apellido, Documento, Legajo, Ubicación ...',
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

	                echo  $this->Form->end();
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
				<?php echo $this->Custom->menu('main', array('dropdown' => true)); ?>
			</div>
		</div>
	</div>
</header>


<script type="text/javascript">
	
	$('.autocomplete').map(inicializacion);

	var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
  'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
  'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
  'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
  'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
  'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
  'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
  'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
  'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
];


	function inicializacion(index, el) {

		var url = $(el).data("url");
		/*
		var listado = new Bloodhound({
		  datumTokenizer: Bloodhound.tokenizers.whitespace,
  			queryTokenizer: Bloodhound.tokenizers.whitespace,
		 // prefetch: '<?php $this->Html->url(array(""))?>',
		 local: states
*/
		 /*
		  remote: {
		    url: url,
		    wildcard: '%QUERY'
		  }
		  */
		});

		

		$(el).typeahead({
		  hint: true,
		  highlight: true,
		  minLength: 1
		},
		{
		  name: 'states',
		  source: states
		});
/*
		$(el).typeahead(null, {
		  name: 'best-pictures',
		  display: 'value',
		  source: listado
		});
		*/
	}
</script>