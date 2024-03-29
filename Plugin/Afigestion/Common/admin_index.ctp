<?php
if (empty($modelClass)) {
	$modelClass = Inflector::singularize($this->name);
}
if (!isset($className)) {
	$className = strtolower($this->name);
}

$rowClass = $this->Layout->cssClass('row');
$columnFull = $this->Layout->cssClass('columnFull');
$tableClass = isset($tableClass) ? $tableClass : $this->Layout->cssClass('tableClass');

$showActions = isset($showActions) ? $showActions : true;
if( $this->name != 'AppSucursales' && $this->name != 'AppBeneficiosFotos' ){
	echo $this->Html->link('Nuevo '.$modelClass,['action'=>'add'],['class'=>'btn btn-success']);
}


if ($pageHeading = trim($this->fetch('page-heading'))):
	echo $pageHeading;
endif;
?>
<div class="<?php echo $rowClass; ?> hidden-lg hidden-md">
	<div class="<?php echo $columnFull; ?>">
		<h2>
			<?php if ($titleBlock = $this->fetch('title')): ?>
				<?php echo $titleBlock; ?>
			<?php else: ?>
				<?php
				echo!empty($title_for_layout) ? $title_for_layout : $this->name;
				?>
			<?php endif; ?>
		</h2>
	</div>
</div>



<?php
$tableHeaders = trim($this->fetch('table-heading'));
if (!$tableHeaders && isset($displayFields)):
	$tableHeaders = array();
	foreach ($displayFields as $field => $arr):
		if ($arr['sort']):
			$tableHeaders[] = $this->Paginator->sort($field, __d('croogo', $arr['label']));
		else:
			$tableHeaders[] = __d('croogo', $arr['label']);
		endif;
	endforeach;
	$tableHeaders[] = __d('croogo', 'Actions');
	$tableHeaders = $this->Html->tableHeaders($tableHeaders);
endif;

$tableBody = trim($this->fetch('table-body'));
if (!$tableBody && isset($displayFields)):
	$rows = array();
	if (!empty(${strtolower($this->name)})):
		foreach (${strtolower($this->name)} as $item):
			$actions = array();

			if (isset($this->request->query['chooser'])):
				$title = isset($item[$modelClass]['title']) ? $item[$modelClass]['title'] : null;
				$actions[] = $this->Croogo->adminRowAction(__d('croogo', 'Choose'), '#', array(
						'class' => 'item-choose',
						'data-chooser_type' => $modelClass,
						'data-chooser_id' => $item[$modelClass]['id'],
				));
			else:
				$actions[] = $this->Croogo->adminRowAction('', array('action' => 'edit', $item[$modelClass]['id']), array('icon' => $_icons['update'], 'tooltip' => __d('croogo', 'Edit this item'))
				);
				$actions[] = $this->Croogo->adminRowActions($item[$modelClass]['id']);
				$actions[] = $this->Croogo->adminRowAction('', array(
						'action' => 'delete',
						$item[$modelClass]['id'],
								), array(
						'icon' => $_icons['delete'],
						'tooltip' => __d('croogo', 'Remove this item')
								), __d('croogo', 'Are you sure?'));
			endif;
			$actions = $this->Html->div('item-actions', implode(' ', $actions));
			$row = array();
			foreach ($displayFields as $key => $val):
				extract($val);
				if (!is_int($key)) {
					$val = $key;
				}
				if (strpos($val, '.') === false) {
					$val = $modelClass . '.' . $val;
				}
				list($model, $field) = pluginSplit($val);
				$row[] = $this->Layout->displayField($item, $model, $field, compact('type', 'url', 'options'));
			endforeach;
			$row[] = $actions;
			$rows[] = $row;
		endforeach;
		$tableBody = $this->Html->tableCells($rows);
	endif;
endif;
$tableFooters = trim($this->fetch('table-footer'));

echo $this->fetch('content');
?>
<div class="<?php echo $rowClass; ?>">
	<div class="<?php echo $columnFull; ?>">
		<?php
		$searchBlock = $this->fetch('search');
		if (!$searchBlock):
			$searchBlock = $this->element('admin/search');
		endif;
		echo $searchBlock;

		?>

		<table class="<?php echo $tableClass; ?>">
			<?php
			echo $tableHeaders;
			echo $tableBody;
			if ($tableFooters):
				echo $tableFooters;
			endif;
			?>
		</table>

		<?php if ($bulkAction = trim($this->fetch('bulk-action'))): ?>
			<div class="<?php echo $rowClass; ?>">
				<div id="bulk-action" class="control-group">
					<?php echo $bulkAction; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		if ($formEnd = trim($this->fetch('form-end'))):
			echo $formEnd;
		?>

		<?php endif; ?>
	</div>
</div>

<?php
if ($pageFooter = trim($this->fetch('page-footer'))):
	echo $pageFooter;
endif;
?>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<?php
				if ($pagingBlock = $this->fetch('paging')):
					echo $pagingBlock;
				else:
					if (isset($this->Paginator) && isset($this->request['paging'])):
						echo $this->element('admin/pagination');
					endif;
				endif;
				?>
			</div>
		</div>