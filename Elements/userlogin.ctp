<div class="form-inline block-userlogin">
	<?php 
            if ( !CakeSession::read('Auth.User') ) {
                $actionDondeNoseTieneQueMostrarElLogin = ['reset','forgot'];
                if( !in_array($this->action, $actionDondeNoseTieneQueMostrarElLogin) ){
                    echo $this->Form->create('AfiUser', array('url'=> array('plugin'=>'users', 'controller'=>'users', 'action'=>'login')), array('class'=>'form-inline'));
                    echo "<div class='row'>";
                    echo $this->Form->text('username', array('placeholder'=>'Usuario', 'class'=>'form-control input-sm','autofocus'=>true));
                    echo "&nbsp";
                    echo "</div>";
                    echo "<div class='row'>";
                    echo $this->Form->text('password', array('type'=>'password','placeholder'=>'Contraseña', 'class'=>'form-control input-sm'));
                    echo "&nbsp";
                    echo "</div>";
                    echo $this->Form->submit('Ingresar', array('class'=>'btn btn-success btn-sm', 'div'=>false));
                    echo $this->Form->end();

                    echo "  ".$this->Html->link(__d('croogo', 'No recordás tu contraseña?'), array(
                            'plugin' => 'users',
                            'controller' => 'users', 'action' => 'forgot',
                            ), array(
                                    'class'=>'small'
                                    ));
                }
            } else {
                    ?>
                    <span>
                            <?php 
                            $logoutLink = $this->Html->link('Cerrar Sesión', array('plugin'=>'users', 'controller'=>'users', 'action'=>'logout'));
                            echo "Estas logueado como <b>".CakeSession::read('Auth.User.username')."<br/>$logoutLink</b>"; ?>
                    </span>
                    <?php
            }
	?>
</div>