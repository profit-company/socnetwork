<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>
use app\components\extend\Html;
use app\components\extend\Nav;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
 

$this->title = yii::$app->l->t('create', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('<?= Inflector::camel2words(StringHelper::basename($generator->modelClass))?>'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('create');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Create <?= Inflector::camel2words(StringHelper::basename($generator->modelClass))?>'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create"> 
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
