<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\modules\store\helpers\CatalogHelper;
use frontend\modules\cart\helpers\CartHelper;

$this->title = Html::encode('Сравнение');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
<h1><?=$this->title;?></h1>

<?php foreach($terms as $term):?>
    <a class="cmpare-category <?=($current->id == $term->id) ? 'active':'';?>" href="/store/compare?id=<?=$term->id?>"><?=$term->name?></a>
<?php endforeach; ?>


    <table class="compare-table characteristic">
    <tr>
        <td></td>
        <?php foreach($compares as $item):?>
        <?php if($item->term_id != $current->id ){
            continue;
        }?>
        <td><?=$this->render('_item', ['product' => $compareModels[$item->entity_id], 'item' => $item]); ?></td>
        <?php endforeach;?>   
    </tr>
<?php foreach(CatalogHelper::compareFeatures($compareModels) as $title => $items):?>
    <tr class="h">
        <td class="lb"><h3><?=$title;?></h3></td>
        <?php for($i=0; $i<count($compareModels); $i++):?> 
        <td></td>
        <?php endfor;?>
    </tr>
    <?php foreach($items as $name => $values):?>
    <tr class="h">
        <td><?=$name;?></td>
        <?php foreach($compareModels as $model):?>
        <td><?=isset($values[$model->id])?$values[$model->id]:'-';?></td>
        <?php endforeach;?>
    </tr>
    <?php endforeach;?>
<?php endforeach;?>
</table>    