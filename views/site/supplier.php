<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'GridView Simple';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $table ?>
    </p>

    <code><?= __FILE__ ?></code>
</div>
