<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\BinaryTree;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BinaryController extends Controller
{
    /**
     * @return int Exit code
     */
    public function actionGenerate()
    {
        BinaryTree::generate();
        return ExitCode::OK;
    }

    /**
     * @param int $nodeId
     * @return int
     */
    public function actionGetTreeByNodeId($nodeId)
    {
        echo BinaryTree::getTreeByNodeId($nodeId) . "\n";
        return ExitCode::OK;
    }

    /**
     * @param int $nodeId
     * @param int $parentId
     * @param int $position
     * @return int
     */
    public function actionMove($nodeId, $parentId, $position)
    {
        echo BinaryTree::move($nodeId, $parentId, $position) . "\n";
        return ExitCode::OK;
    }
}
