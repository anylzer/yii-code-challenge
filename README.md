<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="60px">
    </a>
    <h1 align="center">Yii 2 code challenge Project</h1>
    <br>
</p>

Yii 2 Basic Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
rapidly creating small projects.

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![build](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.6.0.

INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer create-project --prefer-dist yiisoft/yii2-app-basic basic
~~~

CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii',
    'username' => 'root',
    'password' => 'MYSQL_PASSWORD',
    'charset' => 'utf8',
];
```
Create table supplier

```SQL
CREATE TABLE `supplier` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `code` char(3) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `t_status` enum('ok','hold') CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT 'ok',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
```
import test data

```
php yii supplier
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.

### Running

1. start server, default prot 8888

~~~
php yii serve --port=9999
~~~

2. browse in browser

~~~
http://localhost:9999/
~~~

3. beauty router

~~~php
vim yii-code-challenge/config/web.php

    'db' => $db,
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
        ],
    ],
~~~

4. create supplier model

~~~
 http://localhost:9999/gii/model
~~~

5. edit supplier view 

~~~html
// vim yii-code-challenge/views/layouts/main.php

    ['label' => 'Supplier', 'url' => ['/site/supplier']],

// vim yii-code-challenge/controllers/SiteController.php
    /**
     * Displays test page.
     *
     * @return string
     */
    public function actionSupplier()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Supplier::find(),
        ]);
        try {
            $table = GridView::widget([
                'dataProvider' => $dataProvider,

                'columns'=>[
                    'id',
                    'name',
                    'code',
                    't_status',
                ]
            ]);
        } catch(\Exception $e) {
            echo "error:". $e->getMessage() ."\n";
        }
        return $this->render('supplier', ['table' => $table]);
    }
// touch views/site/supplier.php

<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Supplier';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $table ?>
    </p>

    <code><?= __FILE__ ?></code>
</div>
~~~

6. show supplier

~~~
 http://localhost:9999/site/supplier
~~~
