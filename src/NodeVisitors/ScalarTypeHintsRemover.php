<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\NodeVisitorAbstract;

class ScalarTypeHintsRemover extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Param) {
            return;
        }

        if ($node->type === null) {
            return;
        }

        if ($this->isScalar($node->type)) {
            $node->type = null;
        }
    }

    protected function isScalar(string $type) : bool
    {
        return in_array($type, ['int', 'integer', 'float', 'string', 'bool', 'boolean']);
    }
}
