<?php

namespace Spatie\Php7to5;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\NodeVisitorAbstract;

class ReturnTypesRemover extends NodeVisitorAbstract
{
    /**
     * @inheritdoc
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Param) {
            if ($this->isScalar($node->type)) {
                $node->type = null;
            }
        }
    }

    /**
     * @param $type
     * @return bool
     */
    protected function isScalar($type) : bool
    {
        return in_array($type, ['int', 'integer', 'float', 'string', 'bool', 'boolean']);
    }
}
