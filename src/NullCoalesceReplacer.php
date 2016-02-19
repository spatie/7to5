<?php

namespace Spatie\Php7to5;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\NodeVisitorAbstract;

class NullCoalesceReplacer extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Coalesce) {
            $issetCall = new Node\Expr\FuncCall(new Node\Name('isset'), [$node->left]);

            return new Node\Expr\Ternary($issetCall, $node->left, $node->right);
        }
    }
}
