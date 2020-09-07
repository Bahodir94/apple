<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apples';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <?= Html::encode($this->title) ?>
    <p>
         <?php $form = ActiveForm::begin(['action'=>Url::to('site/create')]); ?>
            <?=Html::input('text', 'count', '', ['class'=>'form-control', 'placeholder' => 'количество яблок']);?>
            <?= Html::submitButton('сгенерировать  яблок', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?> 
    </p>

    <?//Pjax::begin(); ?>
    <?//php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'color',
                'value' => function($data){
                    return "<span style='color:$data->color'>$data->color</span>";
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'date_of_apperance',
                'value' => function($data) {
                    if ($data->date_of_apperance != '')
                        return date('d.m.Y H:i:s', $data->date_of_apperance);
                }
            ],
            [
                'attribute' => 'date_of_fall',
                'value' => function($data) {
                    if ($data->date_of_fall != '')
                        return date('d.m.Y H:i:s', $data->date_of_fall);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($data){
                    if ($data->status == 1) $status = "висит на дереве";
                    if ($data->status == 0) {
                        if ($data->date_of_fall!='' && date('U') - $data->date_of_fall >= 18000 ) {
                            $status = "гнилое яблоко";
                            $css = "disabled";
                        }

                        else $status = "упало/лежит на земле";
                    };
                    return  $status;
                }
            ],
            [
                'attribute' => 'size',
                'value' => function($data) {
                    return number_format($data->size/100, 2, '.','');
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{eat} {fall}',
                'buttons' => [
                    'eat' => function ($url, $model, $key) { 
                        $css='';
                        if ($model->status == 0 && $model->date_of_fall!='' && date('U') - $model->date_of_fall >= 18000 ) {
                            $css = "disabled";
                        }
                         return Html::a(
                            'Съесть',
                            [
                                'eat',
                                'id' => $key
                            ],
                            [
                                'title' => 'Съесть', 
                                'class'=>"btn btn-info activity-view-link ".$css, 
                                'data-toggle' => "modal",
                                'data-target' => "#myModal",
                            ]
                        );
                    },
                    'fall' => function($url, $model, $key){ 
                            if ($model->status != 1) $status = "disabled";
                         return Html::a('Упасть', 
                            [
                                'fall', 
                                'id' => $key
                            ], 
                            [
                                'title' => 'Упасть',
                                'class'=>"btn btn-warning activity-view-link $status",
                            ]);
                    },
    //                 'delete' => function($url, $model, $key){ 
    //                      return Html::a('<i class="icon-md mdi mdi-delete" aria-hidden="true"></i>', $url, ['title' => 'Sil', 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
    // 'data-method' => 'post',]);
    //                 },
                 ],
            ],
        ],
    ]); ?>

    <?//Pjax::end(); ?>

</div>
<?php
    Modal::begin([
    'id' => 'myModal',
    'header' => "Съесть яблоко",
    'size' => 'modal-sm ',
    ]);
     Modal::end();
?>
<?php
$js = <<<JS
$('.activity-view-link').click(function() {
       var id = $(this).closest('tr').data('key');
       $.get(
            '/admin/site/eat',
           {
               id: id
           },
           function (data) {
               $('.modal-body').html(data);
               $('#activity-modal').modal();
           }  
       );
});
JS;
$this->registerJs($js);
?>