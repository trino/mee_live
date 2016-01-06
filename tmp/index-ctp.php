<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         1.2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
	->filter(function($field) use ($schema) {
		return !in_array($schema->columnType($field), ['binary', 'text']);
	})
	->take(7);
?>
<div class="actions columns large-2 medium-3">
	<h3><CakePHPBakePhpOpenTag= __('Actions') CakePHPBakePhpCloseTag></h3>
	<ul class="side-nav">
		<li><CakePHPBakePhpOpenTag= $this->Html->link(__('New <?= $singularHumanName ?>'), ['action' => 'add']) CakePHPBakePhpCloseTag></li>
<?php
	$done = [];
	foreach ($associations as $type => $data):
		foreach ($data as $alias => $details):
			if ($details['controller'] != $this->name && !in_array($details['controller'], $done)):
?>
		<li><CakePHPBakePhpOpenTag= $this->Html->link(__('List <?= $this->_pluralHumanName($alias) ?>'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'index']) CakePHPBakePhpCloseTag> </li>
		<li><CakePHPBakePhpOpenTag= $this->Html->link(__('New <?= $this->_singularHumanName($alias) ?>'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'add']) CakePHPBakePhpCloseTag> </li>
<?php
				$done[] = $details['controller'];
			endif;
		endforeach;
	endforeach;
?>
	</ul>
</div>
<div class="<?= $pluralVar ?> index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
	<?php foreach ($fields as $field): ?>
		<th><CakePHPBakePhpOpenTag= $this->Paginator->sort('<?= $field ?>') CakePHPBakePhpCloseTag></th>
	<?php endforeach; ?>
		<th class="actions"><CakePHPBakePhpOpenTag= __('Actions') CakePHPBakePhpCloseTag></th>
		</tr>
	</thead>
	<tbody>
	<CakePHPBakePhpOpenTag foreach ($<?= $pluralVar ?> as $<?= $singularVar ?>): CakePHPBakePhpCloseTag>
		<tr>
<?php		foreach ($fields as $field) {
			$isKey = false;
			if (!empty($associations['BelongsTo'])) {
				foreach ($associations['BelongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
?>
			<td>
				<CakePHPBakePhpOpenTag= $<?= $singularVar ?>->has('<?= $details['property'] ?>') ? $this->Html->link($<?= $singularVar ?>-><?= $details['property'] ?>-><?= $details['displayField'] ?>, ['controller' => '<?= $details['controller'] ?>', 'action' => 'view', $<?= $singularVar ?>-><?= $details['property'] ?>-><?= $details['primaryKey'][0] ?>]) : '' CakePHPBakePhpCloseTag>
			</td>
<?php
						break;
					}
				}
			}
			if ($isKey !== true) {
				if (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
?>
			<td><CakePHPBakePhpOpenTag= h($<?= $singularVar ?>-><?= $field ?>) CakePHPBakePhpCloseTag></td>
<?php
				} else {
?>
			<td><CakePHPBakePhpOpenTag= $this->Number->format($<?= $singularVar ?>-><?= $field ?>) CakePHPBakePhpCloseTag></td>
<?php
				}
			}
		}

		$pk = '$' . $singularVar . '->' . $primaryKey[0];
?>
			<td class="actions">
				<CakePHPBakePhpOpenTag= $this->Html->link(__('View'), ['action' => 'view', <?= $pk ?>]) CakePHPBakePhpCloseTag>
				<CakePHPBakePhpOpenTag= $this->Html->link(__('Edit'), ['action' => 'edit', <?= $pk ?>]) CakePHPBakePhpCloseTag>
				<CakePHPBakePhpOpenTag= $this->Form->postLink(__('Delete'), ['action' => 'delete', <?= $pk ?>], ['confirm' => __('Are you sure you want to delete # {0}?', <?= $pk ?>)]) CakePHPBakePhpCloseTag>
			</td>
		</tr>

	<CakePHPBakePhpOpenTag endforeach; CakePHPBakePhpCloseTag>
	</tbody>
	</table>
	<div class="paginator">
		<ul class="pagination">
			<CakePHPBakePhpOpenTag= $this->Paginator->prev('< ' . __('previous')); CakePHPBakePhpCloseTag>
			<CakePHPBakePhpOpenTag= $this->Paginator->numbers(); CakePHPBakePhpCloseTag>
			<CakePHPBakePhpOpenTag=	$this->Paginator->next(__('next') . ' >'); CakePHPBakePhpCloseTag>
		</ul>
		<p><CakePHPBakePhpOpenTag= $this->Paginator->counter(); CakePHPBakePhpCloseTag></p>
	</div>
</div>
