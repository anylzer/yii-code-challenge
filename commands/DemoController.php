<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\console\widgets\Table;

/**
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Pan Yaolei <anylzer@gmail.com>
 * @since 2.0
 */
class DemoController extends Controller
{
    public $name = 'Yii';

    public function options($actionID)
    {
        return ['name'];
    }

    public function optionAliases()
    {
        return ['n' => 'name'];
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $this->stdout("Hello {$this->name}?\n", Console::BOLD);

        $name = $this->ansiFormat($this->name, Console::FG_YELLOW);
        echo "Hello, my name is $name.\n";

        echo Table::widget([
            'headers' => ['Project', 'Status', 'Participant'],
            'rows' => [
                ['Yii', 'OK', '@samdark'],
                ['Yii', 'OK', '@cebe'],
            ],
        ]);
        return ExitCode::OK;
    }
}
