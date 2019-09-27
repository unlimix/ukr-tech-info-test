<?php

namespace app\models;

/**
 * Class BinaryTree
 * @package app\models
 */
class BinaryTree
{

    public static function generate()
    {
        for ($level = 1; $level <= 5; $level++) {
            echo "LEVEL $level\n";
            if (1 == $level) {
                $node = new BinaryNode(0, 0);
                $node->save();
            } else {
                /** @var BinaryTreeTable[] $elements */
                $elements = BinaryTreeTable::find()->where(['level' => $level - 1])->all();
                if (!empty($elements)) {
                    foreach ($elements as $elementItem) {
                        for ($position = 1; $position <= 2; $position++) {
                            echo "position $position\n";
                            $node = new BinaryNode($elementItem->id, $position);
                            $node->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * @param int $nodeId
     * @return string
     */
    public static function getTreeByNodeId($nodeId)
    {
        $node = BinaryTreeTable::findOne($nodeId);
        if (empty($node)) {
            return 'Ячейка ' . $nodeId . ' не существует!';
        }
        $query = BinaryTreeTable::find();

        $path = explode('.', $node->path);
        if (1 == count($path)) {
            $query->where(['path' => $node->id]);
            $query->orWhere(['LIKE', 'path', $node->id . '.%', false]);
        } else {
            $query->where(['LIKE', 'path', '%.' . $node->id . '.%', false]);
            $query->orWhere(['LIKE', 'path', '%.' . $node->id, false]);
            $count = count($path);
            for ($i = 0; $i < $count; $i++) {
                unset($path[count($path) - 1]);
                $query->orWhere(['LIKE', 'path', implode('.', $path), false]);
            }
        }

        /**
         * Сделал небольшую конструкцию вывода для проверки
         */
        /** @var BinaryTreeTable[] $elements */
        $elements = $query->orderBy('path', SORT_ASC)->all();
        foreach ($elements as $element) {
            echo $element->path . "\n";
        }

        return '';
    }

    /**
     * @param integer $nodeId
     * @param integer $parentId
     * @param integer $position
     * @return string
     */
    public static function move($nodeId, $parentId, $position)
    {
        $currentNode = BinaryTreeTable::findOne($nodeId);
        if (empty($currentNode)) {
            return 'Ячейка ' . $nodeId . ' не существует!';
        }

        $node = BinaryTreeTable::findOne($parentId);
        if (empty($node)) {
            return 'Ячейка ' . $parentId . ' не существует!';
        }

        $childrenNodes = BinaryTreeTable::find()->where(['parent_id' => $parentId, 'position' => $position])->one();
        if (empty($childrenNodes)) {
            $currentNodePath = $currentNode->path;
            $currentNode->setAttributes([
                'parent_id' => $parentId,
                'position' => $position,
                'path' => $node->path . '.' . $currentNode->id,
                'level' => $node->level + 1,
            ]);

            $currentNode->save();

            /** @var BinaryTreeTable[] $elements */
            $elements = BinaryTreeTable::find()
                ->orWhere(['LIKE', 'path', $currentNodePath . '.%', false])
                ->orderBy('path', SORT_ASC)->all();

            $level = $currentNode->level + 1;

            foreach ($elements as $element) {
                $path = explode('.', $element->path);
                $count = count($path);
                for ($i = 0; $i < $count; $i++) {
                    $item = $path[$i];
                    unset($path[$i]);
                    if ($item == $currentNode->id) {
                        break;
                    }
                }

                $element->setAttributes([
                    'path' => $currentNode->path . '.' . implode('.', $path),
                    'level' => $level,
                ]);
                $element->save();

                if (2 == $element->position) {
                    $level++;
                }
            }

            return 'Ячейка ' . $nodeId . ' перенесена';
        }

        return 'Ячейка ' . $nodeId . ' не перенесена. Место занято!';
    }

}
