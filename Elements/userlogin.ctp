<div class="form-inline block-userlogin">
	<?php 
	if ( !CakeSession::read('Auth.User') ) {

		echo $this->Form->create('User', array('url'=> array('plugin'=>'users', 'controller'=>'users', 'action'=>'login')), array('class'=>'form-inline'));

		echo $this->Form->text('username', array('placeholder'=>'Usuario', 'class'=>'form-control'));
		echo "&nbsp";
		echo $this->Form->text('password', array('placeholder'=>'Contraseña', 'class'=>'form-control'));
		echo "&nbsp";
		echo $this->Form->submit('Ingresar', array('class'=>'btn btn-success', 'div'=>false));
		echo $this->Form->end();
	} else {
		?>
		<span>
			<?php 
			$logoutLink = $this->Html->link('Cerrar Sesión', array('plugin'=>'users', 'controller'=>'users', 'action'=>'logout'));
			echo "Estas logueado como <b>".CakeSession::read('Auth.User.username')." $logoutLink</b>"; ?>
		</span>
		<?php
	}
	
	?>
</div>