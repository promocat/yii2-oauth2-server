<?php

use yii\grid\GridView;
use yii\helpers\Html;

;

/* @var $this yii\web\View */
/* @var $searchModel NIOLAB\oauth2\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'oAuth2 Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">
    <div class="row">


        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <div class="col-lg-12">
            <?= Html::a('Create oAuth2 Client', ['create'], ['class' => 'btn btn-success']); ?>
            <br>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'identifier',
                    'name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('View', $url, ['class' => 'btn btn-xs btn-primary']);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

