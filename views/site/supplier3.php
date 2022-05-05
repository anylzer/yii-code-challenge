<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'GridView Export';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= $menu ?>
    <?= $table ?>
    </p>
</div>
