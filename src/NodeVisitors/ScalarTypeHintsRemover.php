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

        if ($node->type instanceof Node\NullableType) {
            $node->type = $node->type->type;
            if (!$node->default) {
                $node->default = new Node\Expr\ConstFetch(
                    new Node\Name('null')
                );
            }
        }

        if ($this->isScalar($node->type)) {
            $node->type = null;
        }
    }

    /**
     * @param string|null $type
     *
     * @return bool
     */
    protected function isScalar($type)
    {
        return in_array($type, ['int', 'integer', 'float', 'string', 'bool', 'boolean']);
    }
}
