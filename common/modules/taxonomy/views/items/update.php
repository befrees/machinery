<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\taxonomy\models\TaxonomyItems */

$this->title = 'Update Taxonomy Items: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Taxonomy Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="taxonomy-items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'parentTerm' => $parentTerm
    ]) ?>

</div>
