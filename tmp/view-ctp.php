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

$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];
$immediateAssociations = $associations['BelongsTo'] + $associations['HasOne'];
$associationFields = collection($fields)
	->map(function($field) use ($immediateAssociations) {
		foreach ($immediateAssociations as $alias => $details) {
			if ($field === $details['foreignKey']) {
				return [$field => $details];
			}
		}
	})
	->filter()
	->reduce(function($fields, $value) {
		return $fields + $value;
	}, []);

$groupedFields = collection($fields)
	->filter(function($field) use ($schema) {
		return $schema->columnType($field) !== 'binary';
	})
	->groupBy(function($field) use ($schema, $associationFields) {
		$type = $schema->columnType($field);
		if (isset($associationFields[$field])) {
			return 'string';
		}
		if (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
			return 'number';
		}
		if (in_array($type, ['date', 'time', 'datetime', 'timestamp'])) {
			return 'date';
		}
		return in_array($type, ['text', 'boolean']) ? $type : 'string';
	})
	->toArray();

$groupedFields += ['number' => [], 'string' => [], 'boolean' => [], 'date' => [], 'text' => []];
$pk = "\$$singularVar->{$primaryKey[0]}";
?>
<div class="actions columns large-2 medium-3">
	<h3><CakePHPBakePhpOpenTag= __('Actions') CakePHPBakePhpCloseTag></h3>
	<ul class="side-nav">
		<li><CakePHPBakePhpOpenTag= $this->Html->link(__('Edit <?= $singularHumanName ?>'), ['action' => 'edit', <?= $pk ?>]) CakePHPBakePhpCloseTag> </li>
		<li><CakePHPBakePhpOpenTag= $this->Form->postLink(__('Delete <?= $singularHumanName ?>'), ['action' => 'delete', <?= $pk ?>], ['confirm' => __('Are you sure you want to delete # {0}?', <?= $pk ?>)]) CakePHPBakePhpCloseTag> </li>
		<li><CakePHPBakePhpOpenTag= $this->Html->link(__('List <?= $pluralHumanName ?>'), ['action' => 'index']) CakePHPBakePhpCloseTag> </li>
		<li><CakePHPBakePhpOpenTag= $this->Html->link(__('New <?= $singularHumanName ?>'), ['action' => 'add']) CakePHPBakePhpCloseTag> </li>
<?php
	$done = [];
	foreach ($associations as $type => $data) {
		foreach ($data as $alias => $details) {
			if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
?>
		<li><CakePHPBakePhpOpenTag= $this->Html->link(__('List <?= $this->_pluralHumanName($alias) ?>'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'index']) CakePHPBakePhpCloseTag> </li>
		<li><CakePHPBakePhpOpenTag= $this->Html->link(__('New <?= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) ?>'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'add']) CakePHPBakePhpCloseTag> </li>
<?php
				$done[] = $details['controller'];
			}
		}
	}
?>
	</ul>
</div>
<div class="<?= $pluralVar ?> view large-10 medium-9 columns">
	<h2><CakePHPBakePhpOpenTag= h($<?= $singularVar ?>-><?= $displayField ?>) CakePHPBakePhpCloseTag></h2>
	<div class="row">
<?php if ($groupedFields['string']) : ?>
		<div class="large-5 columns strings">
<?php foreach ($groupedFields['string'] as $field) : ?>
<?php if (isset($associationFields[$field])) :
			$details = $associationFields[$field];
?>
			<h6 class="subheader"><CakePHPBakePhpOpenTag= __('<?= Inflector::humanize($details['property']) ?>') CakePHPBakePhpCloseTag></h6>
			<p><CakePHPBakePhpOpenTag= $<?= $singularVar ?>->has('<?= $details['property'] ?>') ? $this->Html->link($<?= $singularVar ?>-><?= $details['property'] ?>-><?= $details['displayField'] ?>, ['controller' => '<?= $details['controller'] ?>', 'action' => 'view', $<?= $singularVar ?>-><?= $details['property'] ?>-><?= $details['primaryKey'][0] ?>]) : '' ?>" CakePHPBakePhpCloseTag></p>
<?php else : ?>
			<h6 class="subheader"><CakePHPBakePhpOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakePhpCloseTag></h6>
			<p><CakePHPBakePhpOpenTag= h($<?= $singularVar ?>-><?= $field ?>) CakePHPBakePhpCloseTag></p>
<?php endif; ?>
<?php endforeach; ?>
		</div>
<?php endif; ?>
<?php if ($groupedFields['number']) : ?>
		<div class="large-2 columns numbers end">
<?php foreach ($groupedFields['number'] as $field) : ?>
			<h6 class="subheader"><CakePHPBakePhpOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakePhpCloseTag></h6>
			<p><CakePHPBakePhpOpenTag= $this->Number->format($<?= $singularVar ?>-><?= $field ?>) CakePHPBakePhpCloseTag></p>
<?php endforeach; ?>
		</div>
<?php endif; ?>
<?php if ($groupedFields['date']) : ?>
		<div class="large-2 columns dates end">
<?php foreach ($groupedFields['date'] as $field) : ?>
			<h6 class="subheader"><?= "<?= __('" . Inflector::humanize($field) . "') ?>" ?></h6>
			<p><CakePHPBakePhpOpenTag= h($<?= $singularVar ?>-><?= $field ?>) CakePHPBakePhpCloseTag></p>
<?php endforeach; ?>
		</div>
<?php endif; ?>
<?php if ($groupedFields['boolean']) : ?>
		<div class="large-2 columns booleans end">
<?php foreach ($groupedFields['boolean'] as $field) : ?>
			<h6 class="subheader"><CakePHPBakePhpOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakePhpCloseTag></h6>
			<p><CakePHPBakePhpOpenTag= $<?= $singularVar ?>-><?= $field ?> ? __('Yes') : __('No'); CakePHPBakePhpCloseTag></p>
<?php endforeach; ?>
		</div>
<?php endif; ?>
	</div>
<?php if ($groupedFields['text']) : ?>
<?php foreach ($groupedFields['text'] as $field) : ?>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><CakePHPBakePhpOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakePhpCloseTag></h6>
			<CakePHPBakePhpOpenTag= $this->Text->autoParagraph(h($<?= $singularVar ?>-><?= $field ?>)); CakePHPBakePhpCloseTag>

		</div>
	</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<?php
$relations = $associations['HasMany'] + $associations['BelongsToMany'];
foreach ($relations as $alias => $details):
	$otherSingularVar = Inflector::variable($alias);
	$otherPluralHumanName = Inflector::humanize($details['controller']);
	?>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><CakePHPBakePhpOpenTag= __('Related <?= $otherPluralHumanName ?>') CakePHPBakePhpCloseTag></h4>
	<CakePHPBakePhpOpenTag if (!empty($<?= $singularVar ?>-><?= $details['property'] ?>)): CakePHPBakePhpCloseTag>
	<table cellpadding="0" cellspacing="0">
		<tr>
<?php foreach ($details['fields'] as $field): ?>
			<th><CakePHPBakePhpOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakePhpCloseTag></th>
<?php endforeach; ?>
			<th class="actions"><CakePHPBakePhpOpenTag= __('Actions') CakePHPBakePhpCloseTag></th>
		</tr>
		<CakePHPBakePhpOpenTag foreach ($<?= $singularVar ?>-><?= $details['property'] ?> as $<?= $otherSingularVar ?>): CakePHPBakePhpCloseTag>
		<tr>
<?php foreach ($details['fields'] as $field): ?>
			<td><CakePHPBakePhpOpenTag= h($<?= $otherSingularVar ?>-><?= $field ?>) CakePHPBakePhpCloseTag></td>
<?php endforeach; ?>

<?php $otherPk = "\${$otherSingularVar}->{$details['primaryKey'][0]}"; ?>
			<td class="actions">
				<CakePHPBakePhpOpenTag= $this->Html->link(__('View'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'view', <?= $otherPk ?>]) ?>

				<CakePHPBakePhpOpenTag= $this->Html->link(__('Edit'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'edit', <?= $otherPk ?>]) ?>

				<CakePHPBakePhpOpenTag= $this->Form->postLink(__('Delete'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'delete', <?= $otherPk ?>], ['confirm' => __('Are you sure you want to delete # {0}?', <?= $otherPk ?>)]) ?>

			</td>
		</tr>

		<CakePHPBakePhpOpenTag endforeach; CakePHPBakePhpCloseTag>
	</table>
	<CakePHPBakePhpOpenTag endif; CakePHPBakePhpCloseTag>
	</div>
</div>
<?php endforeach; ?>
