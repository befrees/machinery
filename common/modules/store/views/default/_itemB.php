<?php
use common\modules\store\helpers\CartHelper;
use yii\helpers\Html;
use common\modules\file\helpers\StyleHelper;
use yii\helpers\ArrayHelper;
use kartik\rating\StarRating;
use common\modules\store\helpers\ProductHelper;

?>

<div class="item">

                    <?php if(($file = ArrayHelper::getValue($product->files, '0'))):?>
                        <?=Html::a(Html::img('/'.StyleHelper::getPreviewUrl($file, '130x130')),['/'.$product->url->alias],['class'=>'img']);?>
                    <?php else:?>
                        <?=Html::a(Html::img('/files/nophoto_100x100.jpg',['class' => 'img-responsive']),['/'.$product->url->alias],['class' => 'img']);?>
                    <?php endif;?>
                    <span class="product-status ">
                        <?php foreach(ProductHelper::getStatuses($product->terms) as $status):?>
                            <span class="<?=$status->transliteration;?>"><?=$status->name;?></span>
                        <?php endforeach;?>
                    </span>
                    <?=Html::a(Html::encode($product->title), ['/'.$product->url->alias],['class'=>'title']); ?>
                    <?= StarRating::widget([
                            'name' => 'rating_'.$product->id,
                            'value' => $product->rating,
                            'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']
                        ]);
                    ?>
                    <div class="price"><?php echo \Yii::$app->formatter->asCurrency($product->price); ?></div>
                    <?php if($product->old_price):?>
                        <div class="old_price"><?php echo \Yii::$app->formatter->asCurrency($product->old_price); ?></div>
                    <?php endif;?>
                    <?php echo CartHelper::getBuyButton($product);?>
</div> 