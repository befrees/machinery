<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use common\modules\store\CartAsset;
use yii\widgets\Breadcrumbs;
use common\modules\store\widgets\Filter\FilterWidget;

CartAsset::register($this);

$this->title = Html::encode($current->name);
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => '/catalog'];
$this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => '/'.$parent->transliteration];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
<h1 class="pull-left"><?=Html::encode($current->name);?></h1><div class="items-counter">Найдено: <?=$dataProvider->pagination->totalCount;?></div>
<div class="row catalog-list ">
    <div class="col-lg-8">
        <?=LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'nextPageLabel' => false,
            'prevPageLabel' => false,
           // 'maxButtonCount' => 4,
            ]); ?>
        <?php foreach($products as $product):?>
            <?=$this->render('_itemA',[
                'product' => $product,
            ]);?>
        <?php endforeach; ?>
        <?=LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'nextPageLabel' => false,
            'prevPageLabel' => false,
           // 'maxButtonCount' => 4,
            ]); ?>
       
    </div>
    <div class="col-lg-4">
        <?=FilterWidget::widget(['finder' => $finder, 'url' => $url]);?>
    </div>
</div>