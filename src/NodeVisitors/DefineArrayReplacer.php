<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/*
 * Converts define() arrays into const arrays
 */

class DefineArrayReplacer extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Node\Expr\FuncCall) {
            return null;
        }

        if ($node->name != 'define') {
            return null;
        }

        $nameNode = $node->args[0]->value;
        $valueNode = $node->args[1]->value;

        if (! $valueNode instanceof Node\Expr\Array_) {
            return null;
        }

        $constNode = new Node\Const_($nameNode->value, $valueNode);

        return new Node\Stmt\Const_([$constNode]);
    }

}