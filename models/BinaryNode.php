<?php

namespace app\models;

/**
 * Class BinaryNode
 * @package app\models
 */
class BinaryNode
{
    /** @var BinaryTreeTable */
    private $node;

    /**
     * BinaryNode constructor.
     * @param integer $parent_id
     * @param integer $position
     */
    public function __construct($parent_id, $position)
    {

        $node = BinaryTreeTable::find()->where(['parent_id' => $parent_id, 'position' => $position])->one();

        if (!empty($node)) {

        }
        if (0 == $parent_id && 0 == $position) {
            $this->node = new BinaryTreeTable([
                'parent_id' => $parent_id,
                'position' => $position,
                'path' => '0',
                'level' => 1,
            ]);
        } else {
            $parentNode = BinaryTreeTable::findOne($parent_id);
            if (empty($parentNode)) {

            }
            $this->node = new BinaryTreeTable([
                'parent_id' => $parent_id,
                'position' => $position,
                'path' => $parentNode->path . '.',
                'level' => $parentNode->level + 1,
            ]);
        }
    }

    public function save()
    {
        if ($this->node->validate()) {
            echo "SAVE {$this->node->save()}\n";
            if ('0' == $this->node->path) {
                $path = $this->node->id;
            } else {
                $path = $this->node->path . $this->node->id;
            }
            $this->node->setAttribute('path', (string)$path);
        } else {
            var_dump($this->node->getErrors());
        }
    }

}
