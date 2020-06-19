<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model NIOLAB\oauth2\models\Client */

$this->title = 'Create oAuth2 Client';
$this->params['breadcrumbs'][] = ['label' => 'oAuth2 Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
