<?php
$class = 'alert alert-info';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if(!empty($key)){
?>
<div id="<?php echo h($key) ?>Message" class="<?php echo h($class) ?>"><?php echo h($message) ?></div>
<?php
}