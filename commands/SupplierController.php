<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\console\widgets\Table;
use app\models\Supplier;
use voku\helper\HtmlDomParser as Parser;

/**
 * This command is provided as an example for you to learn how to:
 *
 * 1. crawler website
 * 2. parse html content
 * 3. import data to mysql
 *
 * @author Pan Yaolei <anylzer@gmail.com>
 * @since 2.0
 */
class SupplierController extends Controller
{
    /**
     * This command get names and use widget.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $tableName = Supplier::tableName();
        $schema    = Supplier::getTableSchema()->columns;

        $supplier  = $this->getSupplier(999);

        foreach ($supplier as $sup) {
            $okCnt = Yii::$app->db->createCommand()->insert($tableName, $sup)->execute();
        }

        $rows = Yii::$app->db->createCommand('SELECT * FROM `supplier` ORDER BY `id` DESC LIMIT 6')->queryAll();
        echo Table::widget([
            'headers' => array_keys($schema),
            'rows'    => $rows,
        ]);

        return ExitCode::OK;
    }
    /**
     * Top [1,000] Baby Girl Names in the U.S.
     * @param int $max
     * @return array
     */
    private function getSupplier($max = 1000)
    {
        //google "Baby Girl Names" get url
        $nameUrl  = 'https://www.verywellfamily.com/top-1000-baby-girl-names-2757832';
        $nameHtml = file_get_contents($nameUrl);
        $parser   = Parser::str_get_html($nameHtml);
        $elements = $parser->findOne('#mntl-sc-block_1-0-10');
        $supplier = [];
        foreach ($elements as $key => $element) {
            if ($element->text == '') continue;
            if ($key > $max) continue;
            $key = sprintf("%03d", $key); //char(3)
            $supplier[] = [
                'name'     => $element->text,
                'code'     => (string) $key,
                    't_status' => ['ok', 'hold'][rand(0, 2) % 2], // more `ok`
            ];
        }
        return $supplier;
    }
}
