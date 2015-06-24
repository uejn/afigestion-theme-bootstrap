<?php
$class = isset($class) ? "alert-".$class : "alert-success";
$escape = isset($escape) ? $escape : true;

if ($escape):
	$message = h($message);
endif;
?>
<div class="alert <?php echo $class; ?>"><?php echo $message; ?></div>
