<?php

namespace Spatie\Php7to5\NodeVisitors;

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
        if (!$node instanceof Coalesce) {
            return;
        }
        switch(true)
        {
            case $node->left instanceof Node\Expr\FuncCall:
            case $node->left instanceof Node\Expr\MethodCall:
            case $node->left instanceof Node\Expr\StaticCall:
                $notEmptyCall = new Node\Expr\BooleanNot(new Node\Expr\FuncCall(new Node\Name('empty'), [$node->left]));
                return new Node\Expr\Ternary($notEmptyCall, $node->left, $node->right);
            case $node->left instanceof Node\Expr\BinaryOp:
                $issetCall = new Node\Expr\FuncCall(new Node\Name('isset'), [$node->left->right]);
                $node->left->right = new Node\Expr\Ternary($issetCall, $node->left->right, $node->right);
                return $node->left;
            default:
                $issetCall = new Node\Expr\FuncCall(new Node\Name('isset'), [$node->left]);
                return new Node\Expr\Ternary($issetCall, $node->left, $node->right);
        }
    }
}