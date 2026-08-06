<?php
$this->set('title_for_layout', __d('croogo', 'Restablecer contraseña'));
?>
<style>
	.afi-recuperar-wrapper {
		display: flex;
		align-items: center;
		justify-content: center;
		min-height: 70vh;
		padding: 40px 15px;
	}

	.afi-recuperar-card {
		background: #ffffff;
		width: 100%;
		max-width: 430px;
		padding: 40px 35px 30px;
		border-radius: 18px;
		box-shadow: 0 18px 45px rgba(0, 0, 0, 0.28);
		text-align: center;
		animation: afiFadeIn .5s ease;
	}

	@keyframes afiFadeIn {
		from { opacity: 0; transform: translateY(12px); }
		to   { opacity: 1; transform: translateY(0); }
	}

	.afi-recuperar-card .afi-logo {
		max-width: 110px;
		margin: 0 auto 18px;
	}

	.afi-recuperar-card h2 {
		color: #874d1b;
		font-weight: 700;
		font-size: 24px;
		margin: 0 0 10px;
	}

	.afi-recuperar-card p.afi-sub {
		color: #6b6b6b;
		font-size: 15px;
		line-height: 1.5;
		margin: 0 0 24px;
	}

	.afi-recuperar-card .afi-input-group {
		position: relative;
		margin-bottom: 20px;
		text-align: left;
	}

	.afi-recuperar-card .afi-input-group label {
		display: block;
		font-size: 13px;
		font-weight: 600;
		color: #874d1b;
		margin-bottom: 6px;
	}

	.afi-recuperar-card .afi-input-group input {
		width: 100%;
		height: 46px;
		padding: 10px 14px;
		font-size: 16px;
		border: 1px solid #d9d9d9;
		border-radius: 10px;
		box-shadow: none;
		transition: border-color .2s ease, box-shadow .2s ease;
	}

	.afi-recuperar-card .afi-input-group input:focus {
		outline: none;
		border-color: #f09100;
		box-shadow: 0 0 0 3px rgba(240, 145, 0, 0.18);
	}

	.afi-recuperar-card .afi-requisitos {
		text-align: left;
		background: #fff6e9;
		border: 1px solid #f4d5a5;
		border-radius: 10px;
		padding: 14px 16px 14px 18px;
		margin-bottom: 22px;
	}

	.afi-recuperar-card .afi-requisitos strong {
		display: block;
		color: #874d1b;
		font-size: 13px;
		margin-bottom: 8px;
	}

	.afi-recuperar-card .afi-requisitos ul {
		margin: 0;
		padding-left: 18px;
	}

	.afi-recuperar-card .afi-requisitos li {
		color: #6b6b6b;
		font-size: 13px;
		line-height: 1.6;
	}

	.afi-recuperar-card .afi-btn {
		width: 100%;
		height: 48px;
		border: none;
		border-radius: 10px;
		background: #f09100;
		color: #ffffff;
		font-size: 16px;
		font-weight: 700;
		cursor: pointer;
		transition: background .2s ease, transform .1s ease;
	}

	.afi-recuperar-card .afi-btn:hover {
		background: #d97f00;
	}

	.afi-recuperar-card .afi-btn:active {
		transform: translateY(1px);
	}

	.afi-recuperar-card .afi-volver {
		display: inline-block;
		margin-top: 20px;
		color: #006294;
		font-size: 14px;
		text-decoration: none;
	}

	.afi-recuperar-card .afi-volver:hover {
		text-decoration: underline;
	}
</style>

<div class="afi-recuperar-wrapper">
	<div class="afi-recuperar-card">
		<?php echo $this->Html->image('/theme/afitheme/img/uejn_logo.png', array('alt' => 'UEJN', 'class' => 'afi-logo')); ?>
		<h2><?php echo __d('croogo', 'Creá tu nueva contraseña'); ?></h2>
		<p class="afi-sub"><?php echo __d('croogo', 'Ingresá y confirmá tu nueva contraseña para acceder a tu cuenta.'); ?></p>

		<div class="afi-requisitos">
			<strong><?php echo __d('croogo', 'La contraseña debe cumplir con:'); ?></strong>
			<ul>
				<li><?php echo __d('croogo', 'Tener al menos 6 caracteres.'); ?></li>
				<li><?php echo __d('croogo', 'Coincidir en ambos campos.'); ?></li>
			</ul>
		</div>

		<?php echo $this->Form->create('User', array(
			'url' => array('controller' => 'users', 'action' => 'reset', $username, $key),
			'inputDefaults' => array('div' => false, 'label' => false),
		)); ?>
			<div class="afi-input-group">
				<label for="UserPassword"><?php echo __d('croogo', 'Nueva contraseña'); ?></label>
				<?php echo $this->Form->input('password', array(
					'id' => 'UserPassword',
					'type' => 'password',
					'placeholder' => __d('croogo', 'Ingresá tu nueva contraseña'),
					'autofocus' => true,
					'required' => true,
					'minlength' => 6,
				)); ?>
			</div>
			<div class="afi-input-group">
				<label for="UserVerifyPassword"><?php echo __d('croogo', 'Repetir contraseña'); ?></label>
				<?php echo $this->Form->input('verify_password', array(
					'id' => 'UserVerifyPassword',
					'type' => 'password',
					'placeholder' => __d('croogo', 'Repetí tu nueva contraseña'),
					'required' => true,
					'minlength' => 6,
				)); ?>
			</div>
			<button type="submit" class="afi-btn"><?php echo __d('croogo', 'Guardar contraseña'); ?></button>
		<?php echo $this->Form->end(); ?>

		<?php echo $this->Html->link(
			__d('croogo', 'Volver al inicio de sesión'),
			array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'),
			array('class' => 'afi-volver')
		); ?>
	</div>
</div>
