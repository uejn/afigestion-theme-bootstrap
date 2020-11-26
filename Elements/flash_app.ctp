<?php
$class = isset($class) ? "alert-".$class : "alert-success";
$escape = isset($escape) ? $escape : true;

if ($escape):
	$message = h($message);
endif;
?>
<div class="row center">
	<div class="col-md-12">
		<h3><?php echo $message; ?></h3>
	</div>
</div>
<div class="row center">
	<div class="col-md-12">
		<a href="https://play.google.com/store/apps/details?id=uejn.org.ar" class="btn btn-primary btn-lg">Iniciar sesión en la App</a>
	</div>
</div>
<?php return;