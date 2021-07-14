<?php

/* @var $this yii\web\View */
/* @var $model app\models\Domain */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Domains'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->title,
    'url' => [
        'view',
        'id' => $model->id
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card">
		<div class="domain-update">
            <?=  \app\components\PageHeader::widget(['model' => $model]); ?>
        </div>
	</div>
	<div class="card">
        <?= $this->render ( '_form', [ 'model' => $model ] )?>
    </div>
</div>