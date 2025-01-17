<?php
/**
 * Controller bake template file
 *
 * Allows templating of Controllers generated from bake.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         1.3.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$defaultModel = $name;
?>
<CakePHPBakePhpOpenTag
namespace <?= $namespace ?>\Controller<?= $prefix ?>;

use <?= $namespace ?>\Controller\AppController;

/**
 * <?= $name ?> Controller
 *
 * @property <?= $namespace ?>\Model\Table\<?= $defaultModel ?>Table $<?= $defaultModel ?>

<?php foreach ($components as $component): ?>
 * @property <?= $component ?>Component $<?= $component ?>

<?php endforeach; ?>
 */
class <?= $name ?>Controller extends AppController {
<?php
echo $this->Bake->arrayProperty('helpers', $helpers, ['indent' => false]);
echo $this->Bake->arrayProperty('components', $components, ['indent' => false]);
echo $actions;
?>

}
