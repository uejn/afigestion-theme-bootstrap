<div class="form-inline block-userlogin">
        <?php
        if (!$this->Session->read("Auth.User")) {
                $actionDondeNoseTieneQueMostrarElLogin = ['reset', 'forgot'];
                if (!in_array($this->action, $actionDondeNoseTieneQueMostrarElLogin)) {
                        echo $this->Form->create('AfiUser', array('url' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'login')), array('class' => 'form-inline'));
                        echo "<div class='row'>";
                        echo "<div class='col-sm-12'>";
                        echo $this->Form->text('username', array('placeholder' => 'Usuario', 'class' => 'form-control input-lg', 'autofocus' => true));
                        echo "&nbsp";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='row'>";
                        echo "<div class='col-sm-12'>";
                        echo $this->Form->text('password', array('type' => 'password', 'placeholder' => 'Contraseña', 'class' => 'form-control input-lg'));
                        echo "&nbsp";
                        echo "</div>";
                        echo "</div>";
                        echo $this->Form->submit('Ingresar', array('class' => 'btn btn-success btn-sm', 'div' => false));
                        echo $this->Form->end();

                        echo "  " . $this->Html->link(__d('croogo', 'No recordás tu contraseña?'), array(
                                'plugin' => 'users',
                                'controller' => 'users',
                                'action' => 'forgot',
                        ), array(
                                'class' => 'small'
                        ));
                }
        } else {
                $username = $this->Session->read("Auth.User.username");
                $rol      = $this->Session->read("Auth.User.Role.title");
                $nivel    = $this->Session->read("Auth.User.UsuarioNivel.0.nivel");
        ?>
                <span class="block-userlogin__sesion">
                        <span class="block-userlogin__usuario"><?php echo h($username); ?></span>
                        <span class="block-userlogin__rol"><?php echo h($rol); ?> &middot; Nivel <?php echo h($nivel); ?></span>
                        <?php echo $this->Html->link('Cerrar sesión', array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout'), array('class' => 'block-userlogin__salir')); ?>
                </span>
        <?php
        }
        ?>
</div>