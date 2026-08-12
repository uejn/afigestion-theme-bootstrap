<?php

$this->set('title_for_layout', __d('croogo', 'Ingresar'));

$this->start('post_header');
echo $this->Html->css('users_login');
?>
<div class="afi-login">
    <div class="afi-login__hero">
        <div class="afi-login__hero-inner">
            <?php echo $this->Html->image('/theme/afitheme/img/uejn_logo.png', array('alt' => 'UEJN', 'class' => 'afi-login__logo')); ?>
            <h1 class="afi-login__title"><?php echo h(Configure::read('Site.title')); ?></h1>
            <p class="afi-login__subtitle">
                <?php echo __d('croogo', 'Sistema de gestión de afiliados de la Unión de Empleados de la Justicia de la Nación.'); ?>
            </p>
            <ul class="afi-login__features">
                <li class="afi-login__feature">Afiliaciones</li>
                <li class="afi-login__feature">Legajos y padrón</li>
                <li class="afi-login__feature">Cuotas y descuentos</li>
                <li class="afi-login__feature">Beneficios</li>
                <li class="afi-login__feature">Elecciones</li>
            </ul>
        </div>
    </div>

    <div class="afi-login__panel">
        <div class="afi-login__card">
            <?php echo $this->Html->image('/theme/afitheme/img/uejn_logo.png', array('alt' => 'UEJN', 'class' => 'afi-login__card-logo')); ?>
            <?php if (!$this->Session->read('Auth.User')) { ?>
                <div class="afi-login__eyebrow"><?php echo __d('croogo', 'Acceso'); ?></div>
                <h2 class="afi-login__heading"><?php echo __d('croogo', 'Bienvenido'); ?></h2>
                <p class="afi-login__lead"><?php echo __d('croogo', 'Ingresá con tu usuario para continuar.'); ?></p>

                <?php echo $this->Form->create('AfiUser', array(
                    'url' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'),
                    'inputDefaults' => array('div' => false, 'label' => false),
                )); ?>
                <div class="afi-login__field">
                    <label class="afi-login__label" for="AfiUserUsername"><?php echo __d('croogo', 'Usuario'); ?></label>
                    <?php echo $this->Form->text('username', array(
                        'id' => 'AfiUserUsername',
                        'placeholder' => __d('croogo', 'Nombre de usuario'),
                        'autofocus' => true,
                        'autocomplete' => 'username',
                        'required' => true,
                    )); ?>
                </div>
                <div class="afi-login__field">
                    <label class="afi-login__label" for="AfiUserPassword"><?php echo __d('croogo', 'Contraseña'); ?></label>
                    <div class="afi-login__input-wrap">
                        <?php echo $this->Form->text('password', array(
                            'type' => 'password',
                            'id' => 'AfiUserPassword',
                            'placeholder' => '••••••••',
                            'autocomplete' => 'current-password',
                            'required' => true,
                        )); ?>
                        <button type="button" class="afi-login__toggle" id="afi-login-toggle" aria-label="<?php echo __d('croogo', 'Mostrar contraseña'); ?>">
                            <span class="glyphicon glyphicon-eye-open"></span>
                        </button>
                    </div>
                </div>
                <button type="submit" class="afi-login__submit"><?php echo __d('croogo', 'Ingresar'); ?></button>
                <?php echo $this->Form->end(); ?>

                <div class="afi-login__footer">
                    <?php echo $this->Html->link(
                        __d('croogo', '¿No recordás tu contraseña?'),
                        array('plugin' => 'users', 'controller' => 'users', 'action' => 'forgot'),
                        array('class' => 'afi-login__link')
                    ); ?>
                </div>
            <?php } else { ?>
                <h2 class="afi-login__heading"><?php echo __d('croogo', 'Ya iniciaste sesión'); ?></h2>
                <p class="afi-login__sesion">
                    <?php echo $this->Html->link(__d('croogo', 'Ir al inicio'), '/', array('class' => 'afi-login__link')); ?>
                </p>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function() {
        var boton = document.getElementById('afi-login-toggle');
        var campo = document.getElementById('AfiUserPassword');
        if (!boton || !campo) {
            return;
        }
        boton.addEventListener('click', function() {
            var oculto = campo.getAttribute('type') === 'password';
            campo.setAttribute('type', oculto ? 'text' : 'password');
            boton.firstElementChild.className = oculto ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open';
            campo.focus();
        });
    })();
</script>
<?php
$this->end();
