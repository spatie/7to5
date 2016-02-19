<?php

namespace Spatie\Php7to5;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\NodeVisitorAbstract;

class ReturnTypesRemover extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof FunctionLike) {
            return;
        }

        $node->returnType = null;
    }
}
